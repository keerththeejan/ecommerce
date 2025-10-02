<?php
/**
 * POS Controller
 * Handles Point of Sale functionality
 */
class PosController extends Controller {
    private $posModel;
    private $productModel;
    private $userModel;
    
    public function __construct() {
        $this->posModel = $this->model('POS');
        $this->productModel = $this->model('Product');
        $this->userModel = $this->model('User');
    }
    
    /**
     * POS Dashboard
     */
    public function index() {
        // Check if staff
        if(!isStaff()) {
            redirect('user/login');
        }
        
        // Check if staff has active session
        $activeSession = $this->posModel->getActiveSession($_SESSION['user_id']);
        
        if(!$activeSession) {
            redirect('pos/session');
        }
        
        // Get products for POS
        $products = $this->productModel->getActiveProducts();
        
        // Get categories for filter
        $categories = $this->model('Category')->getActiveCategories();

        // Build category => tax rate map (percentage) for the view
        $categoryTaxMap = [];
        try {
            $taxModel = $this->model('TaxModel');
            foreach ($categories as $cat) {
                $rate = 0.0;
                if (isset($cat['tax_id']) && $cat['tax_id']) {
                    $tax = $taxModel->getTaxRateById($cat['tax_id']);
                    if ($tax && isset($tax->rate)) {
                        $rate = (float)$tax->rate; // stored as percent
                    }
                }
                $categoryTaxMap[(int)$cat['id']] = $rate;
            }
        } catch (Exception $e) {
            // Fallback to zero tax if models/tables not available
        }
        
        // Optional: preload items from an existing order
        $preloadItems = [];
        $orderId = $this->get('order_id');
        if (!empty($orderId)) {
            try {
                $orderModel = $this->model('Order');
                $orderWithItems = $orderModel->getOrderWithItems((int)$orderId);
                if ($orderWithItems && isset($orderWithItems['items'])) {
                    foreach ($orderWithItems['items'] as $it) {
                        // Fetch product to get category id and current name if needed
                        $product = $this->productModel->getById($it['product_id']);
                        if ($product) {
                            $preloadItems[] = [
                                'id' => (int)$it['product_id'],
                                'name' => isset($product['name']) ? $product['name'] : (isset($it['product_name']) ? $it['product_name'] : ('Product #' . (int)$it['product_id'])),
                                'price' => (float)$it['price'],
                                'quantity' => (int)$it['quantity'],
                                'categoryId' => isset($product['category_id']) ? (int)$product['category_id'] : 0,
                            ];
                        }
                    }
                }
            } catch (Exception $e) {
                // ignore preload errors
            }
        }

        // Load view
        $this->view('pos/index', [
            'products' => $products,
            'categories' => $categories,
            'session' => $activeSession,
            'categoryTaxMap' => $categoryTaxMap,
            'preloadItems' => $preloadItems
        ]);
    }
    
    /**
     * POS Session management
     */
    public function session() {
        // Check if staff
        if(!isStaff()) {
            redirect('user/login');
        }
        
        // Check if staff has active session
        $activeSession = $this->posModel->getActiveSession($_SESSION['user_id']);
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Check if closing session
            if($this->post('action') == 'close' && $activeSession) {
                // Sanitize POST data
                $data = [
                    'closing_balance' => $this->post('closing_balance'),
                    'notes' => sanitize($this->post('notes'))
                ];
                
                // Validate data
                $errors = $this->validate($data, [
                    'closing_balance' => 'required|numeric'
                ]);
                
                // Make sure there are no errors
                if(empty($errors)) {
                    // Close session
                    if($this->posModel->closeSession($activeSession['id'], $data['closing_balance'], $data['notes'])) {
                        flash('pos_success', 'Session closed successfully');
                        redirect('pos/session');
                    } else {
                        flash('pos_error', 'Failed to close session', 'alert alert-danger');
                        redirect('pos/session');
                    }
                } else {
                    // Load view with errors
                    $this->view('pos/session', [
                        'errors' => $errors,
                        'data' => $data,
                        'session' => $activeSession
                    ]);
                }
            } else if(!$activeSession) {
                // Opening new session
                
                // Sanitize POST data
                $data = [
                    'opening_balance' => $this->post('opening_balance'),
                    'notes' => sanitize($this->post('notes'))
                ];
                
                // Validate data
                $errors = $this->validate($data, [
                    'opening_balance' => 'required|numeric'
                ]);
                
                // Make sure there are no errors
                if(empty($errors)) {
                    // Open session
                    if($this->posModel->openSession($_SESSION['user_id'], $data['opening_balance'], $data['notes'])) {
                        flash('pos_success', 'Session opened successfully');
                        redirect('pos');
                    } else {
                        flash('pos_error', 'Failed to open session', 'alert alert-danger');
                        redirect('pos/session');
                    }
                } else {
                    // Load view with errors
                    $this->view('pos/session', [
                        'errors' => $errors,
                        'data' => $data,
                        'session' => $activeSession
                    ]);
                }
            }
        } else {
            // Init data
            $data = [
                'opening_balance' => '',
                'closing_balance' => '',
                'notes' => ''
            ];
            
            // Load view
            $this->view('pos/session', [
                'data' => $data,
                'session' => $activeSession,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Process POS sale
     */
    public function processSale() {
        // Check if staff
        if(!isStaff()) {
            redirect('user/login');
        }
        
        // Check if staff has active session
        $activeSession = $this->posModel->getActiveSession($_SESSION['user_id']);
        
        if(!$activeSession) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'No active session']);
            } else {
                redirect('pos/session');
            }
            return;
        }
        
        // Check for POST
        if($this->isPost()) {
            try {
                // Get items from POST
                $itemsRaw = $this->post('items');
                $items = json_decode($itemsRaw, true);
                $customerId = $this->post('customer_id');
                $paymentMethod = strtolower((string)$this->post('payment_method'));
                $totalAmount = (float)$this->post('total_amount');
                $amountTendered = (float)$this->post('amount_tendered');
                $tax = (float)$this->post('tax');
                $shippingFee = (float)$this->post('shipping_fee');
                $notes = sanitize($this->post('notes'));

                // Validate data
                if(empty($items) || !is_array($items)) {
                    return $this->json(['success' => false, 'message' => 'No items in cart']);
                }

                // Normalize items schema and basic validation
                $normalizedItems = [];
                foreach ($items as $it) {
                    $pid = isset($it['product_id']) ? (int)$it['product_id'] : 0;
                    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                    $price = isset($it['price']) ? (float)$it['price'] : 0.0;
                    if ($pid <= 0 || $qty <= 0 || $price < 0) {
                        return $this->json(['success' => false, 'message' => 'Invalid item data']);
                    }
                    $normalizedItems[] = [
                        'product_id' => $pid,
                        'quantity' => $qty,
                        'price' => $price,
                    ];
                }

                if ($totalAmount <= 0) {
                    return $this->json(['success' => false, 'message' => 'Invalid total amount']);
                }
                if (empty($paymentMethod)) {
                    $paymentMethod = 'cash';
                }
                if ($amountTendered < 0) { $amountTendered = 0; }

                // Set customer ID to guest if not provided
                if(empty($customerId)) {
                    // Get or create guest user
                    $guestUser = $this->userModel->getSingleBy('username', 'guest');
                    if(!$guestUser) {
                        // Create guest user
                        $guestData = [
                            'username' => 'guest',
                            'email' => 'guest@example.com',
                            'password' => password_hash('guest123', PASSWORD_DEFAULT),
                            'first_name' => 'Guest',
                            'last_name' => 'User',
                            'role' => 'customer'
                        ];
                        $customerId = $this->userModel->create($guestData);
                    } else {
                        $customerId = $guestUser['id'];
                    }
                }

                // Prepare order data
                $paymentStatus = ($amountTendered + 0.0001) >= $totalAmount ? 'paid' : 'pending';
                $orderData = [
                    'user_id' => $customerId,
                    'total_amount' => $totalAmount,
                    'payment_method' => $paymentMethod,
                    'notes' => $notes,
                    'tax' => $tax,
                    'shipping_fee' => $shippingFee,
                    'payment_status' => $paymentStatus,
                    'status' => 'processing',
                    'amount_tendered' => $amountTendered,
                ];

                // Create POS order
                $orderId = $this->posModel->createPOSOrder($orderData, $normalizedItems);

                if($orderId) {
                    return $this->json([
                        'success' => true,
                        'message' => 'Sale completed successfully',
                        'order_id' => $orderId
                    ]);
                }

                return $this->json(['success' => false, 'message' => 'Failed to process sale']);
            } catch (Exception $e) {
                // Log and return JSON error to avoid AJAX generic error handler
                error_log('POS processSale error: ' . $e->getMessage());
                return $this->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            }
        } else {
            redirect('pos');
        }
    }
    
    /**
     * Display POS receipt
     * 
     * @param int $orderId Order ID
     */
    public function receipt($orderId) {
        // Staff only
        if(!isStaff()) {
            redirect('user/login');
        }
        
        $orderId = (int)$orderId;
        $order = $this->model('Order')->getOrderWithItems($orderId);
        if(!$order) {
            flash('pos_error', 'Order not found', 'alert alert-danger');
            redirect('pos');
        }

        // Compute financial summary
        $items = isset($order['items']) && is_array($order['items']) ? $order['items'] : [];
        $subtotal = 0.0;
        foreach ($items as $it) {
            $price = isset($it['price']) ? (float)$it['price'] : 0.0;
            $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
            $subtotal += $price * $qty;
        }

        $ord = isset($order['order']) ? $order['order'] : [];
        $tax = isset($ord['tax']) ? (float)$ord['tax'] : 0.0;
        $shipping = isset($ord['shipping_fee']) ? (float)$ord['shipping_fee'] : 0.0;
        $total = isset($ord['total_amount']) ? (float)$ord['total_amount'] : ($subtotal + $tax + $shipping);
        $discount = max(0, round(($subtotal + $tax + $shipping) - $total, 2));

        // Sum paid amount from transactions
        $db = new Database();
        $db->query("SELECT COALESCE(SUM(amount),0) AS paid FROM transactions WHERE order_id = :oid AND status IN ('completed','paid')");
        $db->bind(':oid', $orderId);
        $tx = $db->single();
        $paid = $tx && isset($tx['paid']) ? (float)$tx['paid'] : 0.0;

        $balance = max(0, round($total - $paid, 2));
        $change = max(0, round($paid - $total, 2));

        $summary = [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'paid' => $paid,
            'balance' => $balance,
            'change' => $change,
        ];

        $this->view('pos/receipt', [
            'order' => $order,
            'summary' => $summary,
        ]);
    }
    
    /**
     * Get products by category for POS (AJAX)
     */
    public function getProductsByCategory() {
        // Check if staff
        if(!isStaff()) {
            $this->json(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        // Get category ID
        $categoryId = $this->get('category_id', '');
        
        // Get products
        $products = [];
        if(!empty($categoryId)) {
            $products = $this->productModel->getProductsByCategory($categoryId);
        } else {
            $products = $this->productModel->getActiveProducts();
        }
        
        // Return JSON response
        $this->json([
            'success' => true,
            'products' => $products
        ]);
    }
    
    /**
     * Get product details for POS (AJAX)
     */
    public function getProductDetails() {
        // Check if staff
        if(!isStaff()) {
            $this->json(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        // Get product ID
        $productId = $this->get('product_id', '');
        
        // Get product
        $product = $this->productModel->getById($productId);
        
        // Return JSON response
        $this->json([
            'success' => true,
            'product' => $product
        ]);
    }
    
    /**
     * Search customers for POS (AJAX)
     */
    public function searchCustomers() {
        // Check if staff
        if(!isStaff()) {
            $this->json(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        // Get search keyword
        $keyword = $this->get('keyword', '');
        
        // Get customers
        $customers = [];
        if(!empty($keyword)) {
            $this->db = new Database();
            $this->db->query("SELECT id, username, email, first_name, last_name 
                             FROM users 
                             WHERE role = 'customer' AND 
                                  (username LIKE :keyword OR 
                                   email LIKE :keyword OR 
                                   first_name LIKE :keyword OR 
                                   last_name LIKE :keyword)
                             LIMIT 10");
            $this->db->bind(':keyword', '%' . $keyword . '%');
            $customers = $this->db->resultSet();
        }
        
        // Return JSON response
        $this->json([
            'success' => true,
            'customers' => $customers
        ]);
    }
    
    /**
     * Get sales report
     */
    public function report() {
        // Check if staff
        if(!isStaff()) {
            redirect('user/login');
        }
        
        // Get session ID
        $sessionId = $this->get('session_id');
        
        // Get sales report
        $report = $this->posModel->getDailySalesReport($sessionId);
        
        // Get session history
        $sessions = $this->posModel->getSessionHistory($_SESSION['user_id']);
        
        // Load view
        $this->view('pos/report', [
            'report' => $report,
            'sessions' => $sessions,
            'sessionId' => $sessionId
        ]);
    }
}
