<?php
/**
 * Order Controller
 * Handles order processing and management
 */
class OrderController extends Controller {
    private $orderModel;
    private $cartModel;
    private $productModel;
    private $customerModel;
    private $shippingModel;
    
    public function __construct() {
        $this->orderModel = $this->model('Order');
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
        
        // Load required models
        $this->customerModel = $this->model('Customer');
        $this->shippingModel = $this->model('Shipping');
    }
    
    /**
     * Display customer orders
     */
    public function index() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get orders
        $orders = $this->orderModel->getOrdersByUser($_SESSION['user_id']);
        
        // Load view
        $this->view('customer/orders/index', [
            'orders' => $orders
        ]);
    }
    
    /**
     * Display customer order history
     */
    public function history() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get orders
        $orders = $this->orderModel->getOrdersByUser($_SESSION['user_id']);
        
        // Load view
        $this->view('customer/orders/history', [
            'orders' => $orders
        ]);
    }
    
    /**
     * Display order details
     * 
     * @param int $orderId Order ID
     */
    public function show($orderId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get order with items
        $order = $this->orderModel->getOrderWithItems($orderId);
        
        // Check if order exists and belongs to user
        if(!$order || $order['order']['user_id'] != $_SESSION['user_id']) {
            flash('order_error', 'Order not found', 'alert alert-danger');
            redirect('orders');
        }
        
        // Load view
        $this->view('customer/orders/show', [
            'order' => $order
        ]);
    }
    
    /**
     * Checkout process
     */
    public function checkout() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get cart items
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        
        // Check if cart is empty
        if(empty($cartItems)) {
            flash('cart_error', 'Your cart is empty', 'alert alert-danger');
            redirect('cart');
        }
        
        // Calculate cart total
        $cartTotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'shipping_address' => sanitize($this->post('shipping_address')),
                'billing_address' => sanitize($this->post('billing_address')),
                'payment_method' => sanitize($this->post('payment_method')),
                'notes' => sanitize($this->post('notes'))
            ];
            
            // Use billing address as shipping address if same
            if($this->post('same_address')) {
                $data['shipping_address'] = $data['billing_address'];
            }
            
            // Validate data
            $errors = $this->validate($data, [
                'shipping_address' => 'required',
                'billing_address' => 'required',
                'payment_method' => 'required'
            ]);
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Prepare order data
                $orderData = [
                    'user_id' => $_SESSION['user_id'],
                    'total_amount' => $cartTotal,
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $data['payment_method'],
                    'shipping_address' => $data['shipping_address'],
                    'billing_address' => $data['billing_address'],
                    'shipping_fee' => 0, // You can calculate shipping fee based on address
                    'tax' => $cartTotal * 0.1, // 10% tax example
                    'notes' => $data['notes']
                ];
                
                // Prepare order items
                $orderItems = [];
                foreach($cartItems as $item) {
                    $orderItems[] = [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['sale_price'] ?? $item['price']
                    ];
                }
                
                // Create order
                $orderId = $this->orderModel->createOrder($orderData, $orderItems);
                
                if($orderId) {
                    // Clear cart
                    $this->cartModel->clearCart($_SESSION['user_id']);
                    
                    // Process payment (this would be integrated with a payment gateway)
                    if($data['payment_method'] == 'cod') {
                        // Cash on delivery - no payment processing needed
                        flash('order_success', 'Order placed successfully');
                        redirect('orders');
                    } else {
                        // Redirect to payment page
                        redirect('orders/payment/' . $orderId);
                    }
                } else {
                    flash('order_error', 'Failed to place order', 'alert alert-danger');
                    redirect('cart');
                }
            } else {
                // Load view with errors
                $this->view('customer/orders/checkout', [
                    'cartItems' => $cartItems,
                    'cartTotal' => $cartTotal,
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
        } else {
            // Init data
            $data = [
                'shipping_address' => '',
                'billing_address' => '',
                'payment_method' => 'cod',
                'notes' => '',
                'same_address' => true
            ];
            
            // Load view
            $this->view('customer/orders/checkout', [
                'cartItems' => $cartItems,
                'cartTotal' => $cartTotal,
                'data' => $data,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Payment processing
     * 
     * @param int $orderId Order ID
     */
    public function payment($orderId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get order
        $order = $this->orderModel->getOrderWithItems($orderId);
        
        // Check if order exists and belongs to user
        if(!$order || $order['order']['user_id'] != $_SESSION['user_id']) {
            flash('order_error', 'Order not found', 'alert alert-danger');
            redirect('orders');
        }
        
        // Check if payment is already completed
        if($order['order']['payment_status'] == 'paid') {
            flash('order_success', 'Payment already completed');
            redirect('orders/show/' . $orderId);
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process payment (this would be integrated with a payment gateway)
            
            // For demonstration, we'll just mark the payment as completed
            if($this->orderModel->updatePaymentStatus($orderId, 'paid')) {
                // Update order status
                $this->orderModel->updateOrderStatus($orderId, 'processing');
                
                flash('order_success', 'Payment completed successfully');
                redirect('orders/show/' . $orderId);
            } else {
                flash('order_error', 'Failed to process payment', 'alert alert-danger');
                redirect('orders/payment/' . $orderId);
            }
        } else {
            // Load view
            $this->view('customer/orders/payment', [
                'order' => $order
            ]);
        }
    }
    
    /**
     * Cancel order
     * 
     * @param int $orderId Order ID
     */
    public function cancel($orderId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get order
        $order = $this->orderModel->getById($orderId);
        
        // Check if order exists and belongs to user
        if(!$order || $order['user_id'] != $_SESSION['user_id']) {
            flash('order_error', 'Order not found', 'alert alert-danger');
            redirect('orders');
        }
        
        // Check if order can be cancelled
        if($order['status'] != 'pending' && $order['status'] != 'processing') {
            flash('order_error', 'Order cannot be cancelled', 'alert alert-danger');
            redirect('orders/show/' . $orderId);
        }
        
        // Check for POST
        if($this->isPost()) {
            // Cancel order
            if($this->orderModel->updateOrderStatus($orderId, 'cancelled')) {
                flash('order_success', 'Order cancelled successfully');
            } else {
                flash('order_error', 'Failed to cancel order', 'alert alert-danger');
            }
            
            redirect('orders/show/' . $orderId);
        } else {
            // Load view
            $this->view('customer/orders/cancel', [
                'order' => $order
            ]);
        }
    }
    
    /**
     * Admin: Delete an order
     * 
     * @param int $id Order ID
     */
    public function delete($id = null) {
        // For URL like ?controller=order&action=delete&id=123
        if ($id === null) {
            $id = $this->get('id');
        }
        
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax() || $this->isPost()) {
                $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access'], 403);
                return;
            }
            redirect('user/login');
            return;
        }

        // Verify CSRF token for POST/DELETE requests
        $csrfToken = $this->post('csrf_token') ?? $this->getHeader('X-CSRF-TOKEN');
        if (!verifyCsrfToken($csrfToken)) {
            if($this->isAjax() || $this->isPost()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid CSRF token'], 403);
                return;
            }
            flash('order_error', 'Invalid CSRF token', 'alert alert-danger');
            redirect('order/adminIndex');
            return;
        }
        
        // Check if ID is provided and valid
        if(!$id || !is_numeric($id)) {
            if($this->isAjax() || $this->isPost()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid order ID'], 400);
                return;
            }
            flash('order_error', 'Invalid order ID', 'alert alert-danger');
            redirect('order/adminIndex');
            return;
        }
        
        // Check if order exists
        $order = $this->orderModel->getById($id);
        if(!$order) {
            if($this->isAjax() || $this->isPost()) {
                $this->jsonResponse(['success' => false, 'message' => 'Order not found'], 404);
                return;
            }
            flash('order_error', 'Order not found', 'alert alert-danger');
            redirect('order/adminIndex');
            return;
        }
        
        try {
            error_log("Starting to delete order #$id and its items");
            
            // First, delete order items using the model method
            $itemsDeleted = $this->orderModel->deleteOrderItems($id);
            error_log("Deleted order items for order #$id. Result: " . ($itemsDeleted ? 'success' : 'failed'));
            
            if(!$itemsDeleted) {
                $error = $this->db->getError();
                error_log("Error deleting order items: " . $error);
                throw new Exception('Failed to delete order items: ' . $error);
            }
            
            // Then delete the order
            $this->db->query("DELETE FROM " . $this->orderModel->getTableName() . " WHERE id = :id");
            $this->db->bind(':id', $id);
            
            $orderDeleted = $this->db->execute();
            error_log("Deleted order #$id. Result: " . ($orderDeleted ? 'success' : 'failed'));
            
            if(!$orderDeleted) {
                $error = $this->db->getError();
                error_log("Error deleting order: " . $error);
                throw new Exception('Failed to delete order: ' . $error);
            }
            
            // Clear any cached data
            if (function_exists('apc_clear_cache')) {
                apc_clear_cache();
            }
            
            // Force a hard delete by running an additional query to ensure deletion
            $this->db->query("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';");
            $this->db->query("OPTIMIZE TABLE " . $this->orderModel->getTableName() . ";");
            
            error_log("Successfully deleted order #$id and its items");
            
            // Check if this is an AJAX request or regular form submission
            $isAjax = $this->isAjax() || $this->isPost() || !empty($_SERVER['HTTP_X_REQUESTED_WITH']);
            
            if($isAjax) {
                $this->jsonResponse([
                    'success' => true, 
                    'message' => 'Order deleted successfully',
                    'order_id' => $id
                ]);
                return;
            }
            
            // For non-AJAX requests
            flash('order_success', 'Order deleted successfully', 'alert alert-success');
            redirect('order/adminIndex');
            
        } catch (Exception $e) {
            // Rollback the transaction on error
            $this->db->rollBack();
            error_log('Error deleting order #' . $id . ': ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            // Check if this is an AJAX request or regular form submission
            $isAjax = $this->isAjax() || $this->isPost() || !empty($_SERVER['HTTP_X_REQUESTED_WITH']);
            
            if($isAjax) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => 'Failed to delete order: ' . $e->getMessage(),
                    'order_id' => $id
                ], 500);
                return;
            }
            
            // For non-AJAX requests
            flash('order_error', 'Failed to delete order: ' . $e->getMessage(), 'alert alert-danger');
            redirect('order/adminIndex');
            
            // Log database error if available
            if (method_exists($this->db, 'getError')) {
                $dbError = $this->db->getError();
                if ($dbError) {
                    error_log('Database error: ' . $dbError);
                }
            }
            
            $errorMessage = 'Failed to delete order. Please try again.';
            
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false, 
                    'message' => $errorMessage,
                    'debug' => [
                        'error' => $e->getMessage(),
                        'order_id' => $id,
                        'time' => date('Y-m-d H:i:s')
                    ]
                ], 500);
                return;
            }
            
            flash('order_error', $errorMessage, 'alert alert-danger');
        }
        
        // Redirect for non-AJAX requests
        if(!$this->isAjax()) {
            redirect('order/adminIndex');
        }
    }
    
    /**
     * Admin: List all orders
     */
    public function adminIndex() {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access'], 403);
                return;
            }
            redirect('user/login');
            return;
        }
        
        try {
            // Get page number and filter parameters
            $page = (int)$this->get('page', 1);
            // Page size selector (allowed values only)
            $limit = (int)$this->get('limit', 20);
            $allowedLimits = [10, 20, 50, 100];
            if (!in_array($limit, $allowedLimits, true)) {
                $limit = 20;
            }
            $status = $this->get('status', '');
            $paymentStatus = $this->get('payment_status', '');
            $search = $this->get('search', '');
            
            // Prepare filters
            $filters = [];
            if (!empty($status)) {
                $filters['status'] = $status;
            }
            if (!empty($paymentStatus)) {
                $filters['payment_status'] = $paymentStatus;
            }
            if (!empty($search)) {
                $filters['search'] = $search;
            }
            
            // Add a filter to ensure we don't show deleted orders
            $filters['exclude_deleted'] = true;
            
            // Get orders with pagination and filters
            $orders = $this->orderModel->paginate($page, $limit, 'id', 'DESC', $filters);
            
            // Debug: Log the SQL query being executed
            error_log("Fetching orders with filters: " . print_r($filters, true));
            error_log("Total orders found: " . (isset($orders['total']) ? $orders['total'] : 'unknown'));
            
            // For AJAX requests, return JSON
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'data' => $orders['data'],
                    'pagination' => [
                        'current_page' => $orders['current_page'],
                        'total_pages' => $orders['total_pages'],
                        'total_items' => $orders['total'],
                        'per_page' => $limit
                    ]
                ]);
                return;
            }
            
            // For regular requests, load the view
            $this->view('admin/orders/index', [
                'orders' => $orders,
                'filters' => [
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'search' => $search,
                    'limit' => $limit
                ]
            ]);
            
        } catch (Exception $e) {
            error_log('Error in adminIndex: ' . $e->getMessage());
            
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'An error occurred while fetching orders'
                ], 500);
                return;
            }
            
            flash('order_error', 'An error occurred while fetching orders', 'alert alert-danger');
            $this->view('admin/orders/index', ['orders' => []]);
        }
    }
    
    /**
     * Admin: View order details
     * 
     * @param int $orderId Order ID
     */
    public function adminShow($orderId) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access'], 403);
                return;
            }
            redirect('user/login');
            return;
        }
        
        try {
            // Get order with items
            $order = $this->orderModel->getOrderWithItems($orderId);
            
            // Check if order exists
            if(!$order) {
                throw new Exception('Order not found');
            }
            
            // For AJAX requests, return JSON
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'data' => $order
                ]);
                return;
            }
            
            // For regular requests, load the view
            $this->view('admin/orders/show', [
                'order' => $order
            ]);
            
        } catch (Exception $e) {
            error_log('Error in adminShow: ' . $e->getMessage());
            
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
                return;
            }
            
            flash('order_error', 'Order not found', 'alert alert-danger');
            redirect('order/adminIndex');
        }
    }
    
    /**
     * Admin: Update order status
     * 
     * @param int $orderId Order ID
     */
    public function updateStatus($orderId) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access'], 403);
                return;
            }
            redirect('user/login');
            return;
        }
        
        // Check if it's a POST request
        if(!$this->isPost()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
                return;
            }
            flash('order_error', 'Invalid request method', 'alert alert-danger');
            redirect('order/adminIndex');
            return;
        }
        
        // Get and validate status
        $status = $this->post('status');
        if(empty($status)) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Status is required'], 400);
                return;
            }
            flash('order_error', 'Status is required', 'alert alert-danger');
            redirect('order/adminShow/' . $orderId);
            return;
        }
        
        try {
            // Optionally update payment status if provided
            $paymentStatus = $this->post('payment_status');

            // Update order status first
            if(!$this->orderModel->updateOrderStatus($orderId, $status)) {
                throw new Exception($this->orderModel->getLastError() ?? 'Failed to update order status');
            }

            // If payment status provided, update it too
            if (!empty($paymentStatus)) {
                if(!$this->orderModel->updatePaymentStatus($orderId, $paymentStatus)) {
                    throw new Exception($this->orderModel->getLastError() ?? 'Failed to update payment status');
                }
            }

            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Order updated successfully',
                    'status' => $status,
                    'payment_status' => $paymentStatus ?? null
                ]);
                return;
            }
            flash('order_success', 'Order updated successfully');
        } catch (Exception $e) {
            error_log('Error updating order status: ' . $e->getMessage());
            
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
                return;
            }
            
            flash('order_error', $e->getMessage(), 'alert alert-danger');
        }
        
        // Redirect for non-AJAX requests
        if(!$this->isAjax()) {
            redirect('order/adminShow/' . $orderId);
        }
    }
    
    /**
     * Admin: Update payment status
     * 
     * @param int $orderId Order ID
     */
    public function updatePaymentStatus($orderId) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Unauthorized access'], 403);
                return;
            }
            redirect('user/login');
            return;
        }
        
        // Check if it's a POST request
        if(!$this->isPost()) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid request method'], 405);
                return;
            }
            flash('order_error', 'Invalid request method', 'alert alert-danger');
            redirect('order/adminIndex');
            return;
        }
        
        // Get and validate payment status
        $status = $this->post('status');
        if(empty($status)) {
            if($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Payment status is required'], 400);
                return;
            }
            flash('order_error', 'Payment status is required', 'alert alert-danger');
            redirect('order/adminShow/' . $orderId);
            return;
        }
        
        try {
            // Update payment status
            if($this->orderModel->updatePaymentStatus($orderId, $status)) {
                if($this->isAjax()) {
                    $this->jsonResponse([
                        'success' => true,
                        'message' => 'Payment status updated successfully',
                        'payment_status' => $status
                    ]);
                    return;
                }
                flash('order_success', 'Payment status updated successfully');
            } else {
                throw new Exception($this->orderModel->getLastError() ?? 'Failed to update payment status');
            }
        } catch (Exception $e) {
            error_log('Error updating payment status: ' . $e->getMessage());
            
            if($this->isAjax()) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
                return;
            }
            
            flash('order_error', $e->getMessage(), 'alert alert-danger');
        }
        
        // Redirect for non-AJAX requests
        if(!$this->isAjax()) {
            redirect('order/adminShow/' . $orderId);
        }
    }
    
    /**
     * Display order templates
     */
    public function templates() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
            return;
        }
        
        // For now, we'll use sample data
        // In a real application, you would fetch templates from the database
        $templates = [
            ['id' => 1, 'name' => 'Standard Order', 'description' => 'Standard order template', 'created_at' => '2023-01-01'],
            ['id' => 2, 'name' => 'Bulk Order', 'description' => 'Template for bulk orders', 'created_at' => '2023-01-15']
        ];
        
        // Load view
        $this->view('admin/orders/templates', [
            'templates' => $templates
        ]);
    }
    
    /**
     * Quick Order Processing
     * Allows for fast order placement with minimal steps
     */
    /**
     * Quick order processing
     * Allows customers and admins to quickly place orders with minimal steps
     */
    public function speed() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            // Store the intended URL before redirecting
            $_SESSION['intended_url'] = URLROOT . '/order/speed';
            flash('login_required', 'Please log in to use quick order', 'alert alert-danger');
            redirect('user/login');
            return;
        }
        
        // Initialize data array
        $data = [
            'product_sku' => '',
            'quantity' => 1,
            'customer_id' => '',
            'shipping_id' => '',
            'payment_method' => 'cod',
            'products' => [],
            'customers' => [],
            'shipping_methods' => [],
            'product_sku_error' => '',
            'quantity_error' => '',
            'customer_id_error' => '',
            'shipping_id_error' => ''
        ];
        
        try {
            // Get customers (for admin)
            if (isAdmin()) {
                $data['customers'] = $this->customerModel->getCustomers();
            } else {
                $data['customer_id'] = $_SESSION['user_id'];
            }
            
            // Get shipping methods
            $data['shipping_methods'] = $this->shippingModel->getShippingMethods();
        } catch (Exception $e) {
            // Log the error
            error_log('Error in OrderController::speed: ' . $e->getMessage());
            
            // Set error message
            flash('order_error', 'Unable to load required data. Please try again.', 'alert alert-danger');
            
            // Redirect to home or show error page
            redirect('');
            return;
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Initialize product data
            $productData = [
                'product_sku' => trim($_POST['product_sku']),
                'quantity' => trim($_POST['quantity']),
                'customer_id' => isset($_POST['customer_id']) ? trim($_POST['customer_id']) : $_SESSION['user_id'],
                'shipping_id' => trim($_POST['shipping_id']),
                'payment_method' => trim($_POST['payment_method'])
            ];
            
            // Validate product SKU
            if (empty($productData['product_sku'])) {
                $data['product_sku_error'] = 'Please enter a product SKU';
            } else {
                // Check if product exists
                $product = $this->productModel->getProductBySku($productData['product_sku']);
                if (!$product) {
                    $data['product_sku_error'] = 'Product not found';
                } elseif ($product->stock_quantity < $productData['quantity']) {
                    $data['quantity_error'] = 'Insufficient stock';
                }
            }
            
            // Validate quantity
            if (empty($productData['quantity']) || $productData['quantity'] < 1) {
                $data['quantity_error'] = 'Please enter a valid quantity';
            }
            
            // Validate customer (for admin)
            if (isAdmin() && empty($productData['customer_id'])) {
                $data['customer_id_error'] = 'Please select a customer';
            }
            
            // Validate shipping method
            if (empty($productData['shipping_id'])) {
                $data['shipping_id_error'] = 'Please select a shipping method';
            }
            
            // If no errors, process the order
            if (empty($data['product_sku_error']) && empty($data['quantity_error']) && 
                empty($data['customer_id_error']) && empty($data['shipping_id_error'])) {
                
                try {
                    // Get product details
                    $product = $this->productModel->getProductBySku($productData['product_sku']);
                    
                    if (!$product) {
                        throw new Exception('Product not found');
                    }
                    
                    // Get shipping method details
                    $shippingMethod = $this->shippingModel->getShippingMethodById($productData['shipping_id']);
                    if (!$shippingMethod) {
                        throw new Exception('Invalid shipping method');
                    }
                    
                    // Calculate total amount (product price * quantity + shipping cost)
                    $subtotal = $product->price * $productData['quantity'];
                    $shippingCost = $shippingMethod->base_price; // Simple flat rate for now
                    $totalAmount = $subtotal + $shippingCost;
                    
                    // Prepare order data
                    $orderData = [
                        'user_id' => $productData['customer_id'],
                        'shipping_id' => $productData['shipping_id'],
                        'payment_method' => $productData['payment_method'],
                        'status' => 'pending',
                        'total_amount' => $totalAmount,
                        'items' => [
                            [
                                'product_id' => $product->id,
                                'quantity' => $productData['quantity'],
                                'price' => $product->price
                            ]
                        ]
                    ];
                    
                    // Start transaction
                    $this->db->beginTransaction();
                    
                    // Create order
                    $orderId = $this->orderModel->createOrder($orderData);
                    
                    if (!$orderId) {
                        throw new Exception('Failed to create order: ' . $this->orderModel->getError());
                    }
                    
                    // Update product stock
                    if (!$this->productModel->updateStock($product->id, -$productData['quantity'])) {
                        throw new Exception('Failed to update product stock: ' . $this->productModel->getError());
                    }
                    
                    // Commit transaction
                    $this->db->endTransaction();
                    
                    // Redirect to order confirmation
                    flash('order_success', 'Order #' . $orderId . ' placed successfully!', 'alert alert-success');
                    redirect('orders/show/' . $orderId);
                    
                } catch (Exception $e) {
                    // Rollback transaction on error
                    if ($this->db->inTransaction()) {
                        $this->db->cancelTransaction();
                    }
                    
                    // Log error
                    error_log('Quick order failed: ' . $e->getMessage());
                    
                    // Set error message
                    flash('order_error', 'Failed to place order: ' . $e->getMessage(), 'alert alert-danger');
                    
                    // Reload the form with previous input
                    $data['product_sku'] = $productData['product_sku'];
                    $data['quantity'] = $productData['quantity'];
                    $data['shipping_id'] = $productData['shipping_id'];
                    $data['payment_method'] = $productData['payment_method'];
                    
                    if (isAdmin()) {
                        $data['customer_id'] = $productData['customer_id'];
                    }
                }
            } else {
                // Load view with errors
                $this->view('orders/speed', $data);
            }
        } else {
            // Load view
            $this->view('orders/speed', $data);
        }
    }
}
