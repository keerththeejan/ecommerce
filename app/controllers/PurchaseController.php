<?php

// Include required model files
require_once APP_PATH . 'models/Purchase.php';
require_once APP_PATH . 'models/Supplier.php';
require_once APP_PATH . 'models/Product.php';

class PurchaseController {
    private $purchaseModel;
    private $supplierModel;
    private $productModel;

    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new Database();
        
        // Initialize models with database connection
        $this->purchaseModel = new Purchase($this->db);
        $this->supplierModel = new Supplier($this->db);
        $this->productModel = new Product($this->db);
        
        // Load helpers
        require_once APP_PATH . 'helpers/currency_helper.php';
    }

    // Show the Purchase Return list (purchase3)
    public function purchase3() {
        try {
            // Simple defaults for filters/locations; can be wired to DB later
            $locations = [
                ['id' => 'all', 'name' => 'All'],
                ['id' => 'BL0001', 'name' => 'S.N PASUMAI KALANJIYAM (BL0001)'],
                ['id' => 'BL0002', 'name' => 'Location 2'],
                ['id' => 'BL0003', 'name' => 'Location 3']
            ];

            // Optional: selected product context from product list link
            $selectedProduct = null;
            if (!empty($_GET['product_id'])) {
                $pid = (int)$_GET['product_id'];
                if ($pid > 0) {
                    $prod = $this->productModel->getById($pid);
                    if ($prod) {
                        // normalize to array for the view
                        $selectedProduct = is_object($prod) ? (array)$prod : $prod;
                        // attach last purchased qty for default return value
                        if (method_exists($this->purchaseModel, 'getLastPurchasedQtyByProduct')) {
                            $selectedProduct['last_purchase_qty'] = (float)$this->purchaseModel->getLastPurchasedQtyByProduct($pid);
                        }
                    }
                }
            }

            // Load suppliers so the view can render a supplier dropdown if needed
            $suppliers = $this->supplierModel->getAllSuppliers();
            if (is_array($suppliers)) {
                $suppliers = array_map(function($row){ return (array)$row; }, $suppliers);
            } else {
                $suppliers = [];
            }

            // Try to resolve original purchase id for selected product to attach returns
            $originalPurchaseId = 0;
            if (!empty($selectedProduct['id'])) {
                $pid = (int)$selectedProduct['id'];
                $sid = !empty($selectedProduct['supplier_id']) ? (int)$selectedProduct['supplier_id'] : null;
                if (method_exists($this->purchaseModel, 'findMostRecentPurchaseIdByProduct')) {
                    $originalPurchaseId = (int)$this->purchaseModel->findMostRecentPurchaseIdByProduct($pid, $sid);
                }
            }

            $data = [
                'title' => 'Purchase Return',
                'locations' => $locations,
                'returns' => [], // placeholder list
                'selectedProduct' => $selectedProduct,
                'suppliers' => $suppliers,
                'original_purchase_id' => $originalPurchaseId,
            ];

            $this->view('admin/purchases/purchase3', $data);
        } catch (Exception $e) {
            error_log('Error in PurchaseController::purchase3 - ' . $e->getMessage());
            $this->view('errors/500', ['message' => 'Failed to load purchase returns']);
        }
    }

    // Display all purchases
    public function index() {
        try {
            $purchases = $this->purchaseModel->getAllPurchases();
            $data = [
                'title' => 'Manage Purchases',
                'purchases' => $purchases
            ];
            $this->view('admin/purchases/index', $data);
        } catch (Exception $e) {
            error_log('Error in PurchaseController::index - ' . $e->getMessage());
            flash('error', 'An error occurred while loading purchases.');
            redirect('home/admin');
        }
    }

    // Show the alternate purchase form (purchase2)
    public function purchase2() {
        try {
            $suppliers = $this->supplierModel->getAllSuppliers();
            // Normalize suppliers to array-of-arrays for view compatibility
            if (is_array($suppliers)) {
                $suppliers = array_map(function($row){ return (array)$row; }, $suppliers);
            } else {
                $suppliers = [];
            }
            $products = $this->productModel->getActiveProducts();

            $data = [
                'title' => 'Add Purchase',
                'suppliers' => $suppliers,
                'products' => $products
            ];

            $this->view('admin/purchases/purchase2', $data);
        } catch (Exception $e) {
            error_log('Error in PurchaseController::purchase2 - ' . $e->getMessage());
            // Fallback to existing create view with empty data
            $this->view('admin/purchases/create', [
                'title' => 'Add Purchase',
                'suppliers' => [],
                'products' => []
            ]);
        }
    }

    // Show the create purchase form
    public function create() {
        try {
            // Get all suppliers
            $suppliers = $this->supplierModel->getAllSuppliers();
            if (is_array($suppliers)) {
                $suppliers = array_map(function($row){ return (array)$row; }, $suppliers);
            } else {
                $suppliers = [];
            }
            
            // Get all active products with required fields
            $products = $this->productModel->getActiveProducts();
            
            $data = [
                'title' => 'Create New Purchase',
                'suppliers' => $suppliers,
                'products' => $products
            ];
            
            $this->view('admin/purchases/create', $data);
            
        } catch (Exception $e) {
            error_log('Error in PurchaseController::create - ' . $e->getMessage());
            
            // Set empty data on error
            $data = [
                'title' => 'Create New Purchase',
                'suppliers' => [],
                'products' => []
            ];
            
            $this->view('admin/purchases/create', $data);
        }
    }

    // Get products by supplier
    public function getProductsBySupplier() {
        // Start output buffering to catch any unexpected output
        ob_start();
        
        // Set JSON header first to ensure consistent output
        header('Content-Type: application/json');
        
        // Initialize response array
        $response = [
            'success' => false,
            'message' => '',
            'products' => [],
            'debug' => []
        ];
        
        try {
            // Get supplier ID from request
            $supplierId = isset($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : 0;
            $response['debug']['supplier_id'] = $supplierId;
            $response['debug']['request_data'] = $_GET;
            
            if (empty($supplierId)) {
                throw new Exception('Please select a supplier');
            }
            
            // Verify supplier exists
            $this->db->query("SELECT id, name FROM suppliers WHERE id = :id");
            $this->db->bind(':id', $supplierId);
            $supplier = $this->db->single();

            if (empty($supplier)) {
                throw new Exception('Supplier not found in database for ID: ' . $supplierId);
            }

            // Detect schema: prefer supplier_id if present; otherwise use supplier name column
            $this->db->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
            $colCheck = $this->db->resultSet();

            if (!empty($colCheck)) {
                // Schema has supplier_id FK
                $this->db->query("SELECT id, name, sku AS code, price, sale_price, price2, price3, stock_quantity, status, supplier_id
                                  FROM products
                                  WHERE status = 'active' AND supplier_id = :supplier_id
                                  ORDER BY name ASC");
                $this->db->bind(':supplier_id', $supplierId);
                $products = $this->db->resultSet();
                $response['debug']['mode'] = 'by_supplier_id';
            } else {
                // Fallback schema uses supplier name string column
                $supplierName = is_object($supplier) ? $supplier->name : (is_array($supplier) ? ($supplier['name'] ?? '') : '');
                $supplierName = trim((string)$supplierName);
                $this->db->query("SELECT id, name, sku AS code, price, sale_price, price2, price3, stock_quantity, status, supplier as supplier_name
                                  FROM products
                                  WHERE status = 'active' AND (supplier = :supplier_name OR LOWER(supplier) LIKE LOWER(CONCAT('%', :supplier_name_like, '%')))
                                  ORDER BY name ASC");
                $this->db->bind(':supplier_name', $supplierName);
                $this->db->bind(':supplier_name_like', $supplierName);
                $products = $this->db->resultSet();
                $response['debug']['mode'] = 'by_supplier_name';
                $response['debug']['supplier_name_used'] = $supplierName;
            }

            // Debug information
            $response['debug']['query'] = [
                'supplier_id' => $supplierId,
                'products_found' => is_array($products) ? count($products) : 0
            ];

            $response['success'] = true;
            $response['products'] = $products ?: [];
            $response['message'] = 'Showing products for supplier #' . $supplierId;
            $response['debug']['products_count'] = is_array($products) ? count($products) : 0;
            
            if (empty($products)) {
                $response['message'] = 'No products found for this supplier.';
                $response['products'] = [];
            }
            
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            $response['debug']['error'] = $e->getMessage();
            
            // Log the error
            error_log('Error in getProductsBySupplier: ' . $e->getMessage());
            error_log('Trace: ' . $e->getTraceAsString());
        }
        
        // Get any output that might have been generated
        $output = ob_get_clean();
        if (!empty($output)) {
            $response['debug']['unexpected_output'] = $output;
            error_log('Unexpected output detected: ' . $output);
        }
        
        // Ensure we have clean output
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        // Send the JSON response
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Store a new purchase
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Idempotency: prevent duplicate submissions creating multiple purchases
                if (session_status() === PHP_SESSION_NONE) { @session_start(); }
                $submitToken = isset($_POST['submit_token']) ? (string)$_POST['submit_token'] : '';
                if (!isset($_SESSION['used_purchase_tokens'])) { $_SESSION['used_purchase_tokens'] = []; }
                if ($submitToken !== '') {
                    if (isset($_SESSION['used_purchase_tokens'][$submitToken])) {
                        // Already processed this request; return success without creating again
                        if ($this->isAjaxRequest()) {
                            echo json_encode(['success' => true, 'message' => 'Purchase already processed.']);
                            exit;
                        } else {
                            flash('success', 'Purchase already processed.');
                            redirect('?controller=purchase&action=index');
                        }
                    }
                    // Mark token as used
                    $_SESSION['used_purchase_tokens'][$submitToken] = time();
                }

                // Sanitize and validate input
                $supplierId = isset($_POST['supplier_id']) ? trim((string)$_POST['supplier_id']) : null;
                $purchaseDate = isset($_POST['purchase_date']) ? trim((string)$_POST['purchase_date']) : null;
                $status = isset($_POST['status']) ? trim(strip_tags((string)$_POST['status'])) : '';
                $notes = isset($_POST['notes']) ? trim(strip_tags((string)$_POST['notes'])) : '';
                $locationId = isset($_POST['business_location']) && $_POST['business_location'] !== '' ? trim($_POST['business_location']) : 'BL0001';
                $items = $_POST['items'] ?? [];

                // If the user checked "Update stock?", force status to 'received' so stock increments
                $updateStockChecked = isset($_POST['update_stock']) && (int)$_POST['update_stock'] === 1;
                if ($updateStockChecked) {
                    $status = 'received';
                }

                // Determine if this submission is a Return
                $isReturn = isset($_POST['is_return']) && (int)$_POST['is_return'] === 1;
                if ($isReturn) {
                    // Tag notes so it is visible in details; non-breaking if column absent
                    $notes = ($notes !== '') ? ('[RETURN] ' . $notes) : '[RETURN]';
                }

                // Normalize and sanitize item fields (quantity/unit_price numeric)
                if (is_array($items)) {
                    foreach ($items as $k => $it) {
                        if (!is_array($it)) { unset($items[$k]); continue; }
                        $items[$k]['product_id'] = isset($it['product_id']) ? (int)$it['product_id'] : (int)$k;
                        $items[$k]['quantity'] = isset($it['quantity']) ? (float)$it['quantity'] : 0;
                        $items[$k]['unit_price'] = isset($it['unit_price']) ? (float)$it['unit_price'] : 0;
                        // Optional base stock sent from UI to ensure server uses the same base used for projection
                        if (isset($it['base_stock']) && $it['base_stock'] !== '') {
                            $items[$k]['base_stock'] = (float)$it['base_stock'];
                        }
                        // Drop items with invalid quantities
                        if ($items[$k]['product_id'] <= 0 || $items[$k]['quantity'] <= 0) {
                            unset($items[$k]);
                        }
                    }
                }

                // Handle optional document upload
                $documentPath = null;
                if (!empty($_FILES['document']['name'])) {
                    $uploadDir = PUBLIC_PATH . 'uploads/purchases/';
                    if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0777, true);
                    }
                    $ext = pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION);
                    $safeName = 'purchase_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . ($ext ? ('.' . strtolower($ext)) : '');
                    $target = $uploadDir . $safeName;
                    if (is_uploaded_file($_FILES['document']['tmp_name'])) {
                        if (@move_uploaded_file($_FILES['document']['tmp_name'], $target)) {
                            // store relative to public directory for web access
                            $documentPath = 'uploads/purchases/' . $safeName;
                        }
                    }
                }

                // Basic validation
                if (!$supplierId || !$purchaseDate || empty($items)) {
                    throw new Exception('Please fill in all required fields.');
                }

                // If this is a return and client provided an original purchase ID,
                // append return items under the SAME purchase id instead of creating a new purchase.
                $purchaseId = false;
                $originalId = isset($_POST['original_purchase_id']) ? (int)$_POST['original_purchase_id'] : 0;
                if ($isReturn && $originalId <= 0) {
                    // Auto resolve original id from first item product when not provided
                    $firstItem = reset($items);
                    $prodIdForReturn = is_array($firstItem) ? (int)($firstItem['product_id'] ?? 0) : 0;
                    if ($prodIdForReturn > 0 && method_exists($this->purchaseModel, 'findMostRecentPurchaseIdByProduct')) {
                        $originalId = (int)$this->purchaseModel->findMostRecentPurchaseIdByProduct($prodIdForReturn, (int)$supplierId);
                    }
                }

                if ($isReturn && $originalId > 0) {
                    // 1) Insert negative items to original purchase and adjust totals
                    $ok = $this->purchaseModel->insertReturnItems($originalId, $items);
                    if (!$ok) {
                        throw new Exception('Failed to record return items for the original purchase.');
                    }
                    // 2) Append return note for traceability
                    $this->purchaseModel->appendNote($originalId, $notes !== '' ? $notes : '[RETURN]');
                    // 3) Use the original id as the resulting purchase id for downstream logic (stock, payments)
                    $purchaseId = $originalId;
                } else {
                    // Generate a unique purchase number (with DB check) and create a NEW purchase
                    $attempts = 0;
                    while ($attempts < 3 && !$purchaseId) {
                        $attempts++;
                        // Use PR- prefix for returns so list can visually identify them
                        $prefix = $isReturn ? 'PR-' : 'PO-';
                        $purchaseNo = $this->purchaseModel->generateUniquePurchaseNo($prefix);

                        // Prepare purchase data
                        $purchaseData = [
                            'supplier_id' => $supplierId,
                            'purchase_no' => $purchaseNo,
                            'location_id' => $locationId,
                            'purchase_date' => $purchaseDate,
                            'status' => $status,
                            'notes' => $notes,
                            'document_path' => $documentPath,
                            'items' => $items
                        ];

                        // Try to create the purchase
                        $purchaseId = $this->purchaseModel->create($purchaseData);
                    }
                }

                if ($purchaseId) {
                    // Apply stock adjustments ALWAYS: increment for purchases, decrement for returns
                    foreach ($items as $it) {
                        $pid = (int)($it['product_id'] ?? 0);
                        $qty = (float)($it['quantity'] ?? 0);
                        if ($pid <= 0 || $qty <= 0) { continue; }
                        try {
                            // Prefer client-sent base_stock to perfectly match UI projection; fallback to DB current stock
                            $current = isset($it['base_stock']) ? (float)$it['base_stock'] : (float)$this->productModel->getProductStock($pid);
                            $delta = $isReturn ? -$qty : +$qty;
                            $newStock = $current + $delta;
                            if ($newStock < 0) { $newStock = 0; }
                            $this->productModel->updateStock($pid, $newStock);
                        } catch (Exception $e) {
                            error_log('Stock adjust failed for product #' . $pid . ': ' . $e->getMessage());
                        }
                    }
                    // If an advance payment was provided, record it
                    if (isset($_POST['payment']) && is_array($_POST['payment'])) {
                        $pay = $_POST['payment'];
                        $amount = isset($pay['amount']) ? (float)$pay['amount'] : 0.0;
                        if ($amount > 0) {
                            $this->purchaseModel->addPayment([
                                'purchase_id' => $purchaseId,
                                'amount' => $amount,
                                'payment_method' => $pay['method'] ?? 'cash',
                                'transaction_id' => $pay['transaction_id'] ?? null,
                                'notes' => $pay['note'] ?? null,
                            ]);
                        }
                    }

                    if ($this->isAjaxRequest()) {
                        echo json_encode(['success' => true, 'message' => 'Purchase created successfully!']);
                        exit;
                    } else {
                        flash('success', 'Purchase created successfully!');
                        $redirectTo = isset($_POST['redirect_to']) ? trim((string)$_POST['redirect_to']) : '';
                        if ($redirectTo !== '') {
                            redirect($redirectTo);
                        } else {
                            redirect('?controller=purchase&action=index');
                        }
                    }
                } else {
                    throw new Exception('Failed to create purchase.');
                }
            } catch (Exception $e) {
                error_log('Error in PurchaseController::store - ' . $e->getMessage());
                
                if ($this->isAjaxRequest()) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                } else {
                    flash('error', $e->getMessage());
                    // Repopulate form data
                    $_SESSION['form_data'] = $_POST;
                    redirect('?controller=purchase&action=create');
                }
            }
        } else {
            redirect('purchase/create');
        }
    }

    // Display purchase details
    public function show($id) {
        try {
            $purchase = $this->purchaseModel->getPurchaseById($id);
            if (!$purchase) {
                throw new Exception('Purchase not found.');
            }

            // Normalize to array for view and attach items
            if (is_object($purchase)) { $purchase = (array)$purchase; }
            $items = $this->purchaseModel->getPurchaseItems($id);
            $purchase['items'] = is_array($items) ? $items : [];

            $data = [
                'title' => 'Purchase Details',
                'purchase' => $purchase
            ];
            $this->view('admin/purchases/show', $data);
        } catch (Exception $e) {
            error_log('Error in PurchaseController::show - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('?controller=purchase&action=index');
        }
    }

    // Show form to edit an existing purchase
    public function edit($id) {
        try {
            $purchase = $this->purchaseModel->getPurchaseById($id);
            $suppliers = $this->supplierModel->getAllSuppliers();
            if (is_array($suppliers)) {
                $suppliers = array_map(function($row){ return (array)$row; }, $suppliers);
            } else {
                $suppliers = [];
            }
            
            // Get all active products with required fields
            $products = $this->productModel->getActiveProducts();
            
            if (!$purchase) {
                throw new Exception('Purchase not found');
            }
            // Normalize to array and attach items so the view can read $purchase['items'] safely
            if (is_object($purchase)) { $purchase = (array)$purchase; }
            $items = $this->purchaseModel->getPurchaseItems($id);
            $purchase['items'] = is_array($items) ? $items : [];
            
            $data = [
                'title' => 'Edit Purchase #' . $id,
                'purchase' => $purchase,
                'suppliers' => $suppliers,
                'products' => $products
            ];
            
            $this->view('admin/purchases/edit', $data);
            
        } catch (Exception $e) {
            error_log('Error in PurchaseController::edit - ' . $e->getMessage());
            
            // Set empty data on error
            $data = [
                'title' => 'Edit Purchase',
                'purchase' => null,
                'suppliers' => [],
                'products' => []
            ];
            
            $this->view('admin/purchases/edit', $data);
        }
    }

    // Update a purchase
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Sanitize and validate input
                $supplierId = filter_input(INPUT_POST, 'supplier_id', FILTER_VALIDATE_INT);
                $purchaseDate = filter_input(INPUT_POST, 'purchase_date');
                $status = isset($_POST['status']) ? trim(strip_tags((string)$_POST['status'])) : '';
                $notes = isset($_POST['notes']) ? trim(strip_tags((string)$_POST['notes'])) : '';
                $items = $_POST['items'] ?? [];

                // Basic validation
                if (!$supplierId || !$purchaseDate || empty($items)) {
                    throw new Exception('Please fill in all required fields.');
                }


                // Prepare purchase data
                $purchaseData = [
                    'id' => $id,
                    'supplier_id' => $supplierId,
                    'purchase_date' => $purchaseDate,
                    'status' => $status,
                    'notes' => $notes,
                    'items' => $items
                ];

                // Update the purchase
                $updated = $this->purchaseModel->update($purchaseData);

                if ($updated) {
                    flash('success', 'Purchase updated successfully!');
                    redirect('?controller=purchase&action=index');
                } else {
                    throw new Exception('Failed to update purchase.');
                }
            } catch (Exception $e) {
                error_log('Error in PurchaseController::update - ' . $e->getMessage());
                flash('error', $e->getMessage());
                
                // Repopulate form data
                $_SESSION['form_data'] = $_POST;
                redirect('?controller=purchase&action=edit&id=' . $id);
            }
        } else {
            redirect('purchase/index');
        }
    }

    // Delete a purchase
    public function delete($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $deleted = $this->purchaseModel->delete($id);
                
                if ($deleted) {
                    flash('success', 'Purchase deleted successfully!');
                } else {
                    throw new Exception('Failed to delete purchase.');
                }
            }
            redirect('?controller=purchase&action=index');
        } catch (Exception $e) {
            error_log('Error in PurchaseController::delete - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('?controller=purchase&action=index');
        }
    }

    // Update purchase status
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
                
                if (empty($status)) {
                    throw new Exception('Status is required.');
                }

                $updated = $this->purchaseModel->updateStatus($id, $status);
                
                if ($updated) {
                    flash('success', 'Purchase status updated successfully!');
                } else {
                    throw new Exception('Failed to update purchase status.');
                }
            } catch (Exception $e) {
                error_log('Error in PurchaseController::updateStatus - ' . $e->getMessage());
                flash('error', $e->getMessage());
            }
        }
        redirect('purchase/show/' . $id);
    }

    // Helper method to load views
    protected function view($view, $data = []) {
        // Extract data variables to be available in the view
        extract($data);
        
        // Include header
        require_once APP_PATH . 'views/admin/layouts/header.php';
        
        // Include the requested view
        require_once APP_PATH . 'views/' . $view . '.php';
        
        // Include footer
        require_once APP_PATH . 'views/admin/layouts/footer.php';
    }

    // Detect AJAX/fetch requests
    protected function isAjaxRequest() {
        // Classic jQuery/XHR header
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        }
        // Fetch often sets Accept to JSON when expecting JSON
        if (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            return true;
        }
        return false;
    }
}
