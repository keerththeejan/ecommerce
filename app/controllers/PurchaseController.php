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

    // Show the create purchase form
    public function create() {
        try {
            // Get all suppliers
            $suppliers = $this->supplierModel->getAllSuppliers();
            
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
            
            // First, let's see what suppliers we have
            $this->db->query("SELECT * FROM suppliers");
            $allSuppliers = $this->db->resultSet();
            $response['debug']['all_suppliers'] = $allSuppliers;
            
            // Get the selected supplier
            $this->db->query("SELECT * FROM suppliers WHERE id = :id");
            $this->db->bind(':id', $supplierId);
            $supplier = $this->db->single();
            
            // Debug the supplier data
            $response['debug']['supplier_raw'] = $supplier;
            
            if (empty($supplier)) {
                throw new Exception('Supplier not found in database for ID: ' . $supplierId);
            }
            
            // Convert to object if it's an array
            if (is_array($supplier)) {
                $supplier = (object)$supplier;
            }
            
            if (!property_exists($supplier, 'name')) {
                throw new Exception('Supplier name not found for ID: ' . $supplierId);
            }
            
            $supplierName = trim($supplier->name);
            
            // Get all products to see what we're working with
            $this->db->query("SELECT * FROM products WHERE status = 'active'");
            $allProducts = $this->db->resultSet();
            $response['debug']['all_products_sample'] = array_slice($allProducts, 0, 5);
            
            // Get products for this supplier - using only the supplier name
            $this->db->query("SELECT * FROM products 
                             WHERE status = 'active' 
                             AND supplier = :supplier_name
                             ORDER BY name ASC");
            $this->db->bind(':supplier_name', $supplierName);
            $products = $this->db->resultSet();
            
            // Debug: Show the actual SQL query being executed
            $response['debug']['sql_query'] = "SELECT * FROM products WHERE status = 'active' AND supplier = '" . $supplierName . "' ORDER BY name ASC";
            
            // If no products found, try case-insensitive and partial match
            if (empty($products)) {
                $this->db->query("SELECT * FROM products 
                                 WHERE status = 'active' 
                                 AND LOWER(supplier) LIKE LOWER(CONCAT('%', :supplier_name, '%'))
                                 ORDER BY name ASC");
                $this->db->bind(':supplier_name', $supplierName);
                $products = $this->db->resultSet();
                
                $response['debug']['fallback_query_used'] = true;
                $response['debug']['fallback_query'] = "SELECT * FROM products WHERE status = 'active' AND LOWER(supplier) LIKE LOWER('%" . $supplierName . "%') ORDER BY name ASC";
            }
            
            // Debug information
            $response['debug']['query'] = [
                'supplier_id' => $supplierId,
                'supplier_name' => $supplierName,
                'products_found' => count($products)
            ];
            
            $response['success'] = true;
            $response['products'] = $products;
            $response['message'] = 'Showing products for ' . $supplier->name;
            $response['debug']['supplier'] = $supplier->name;
            $response['debug']['products_count'] = count($products);
            
            if (empty($products)) {
                $response['message'] = 'No products found for ' . $supplier->name;
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
                // Sanitize and validate input
                $supplierId = filter_input(INPUT_POST, 'supplier_id', FILTER_VALIDATE_INT);
                $purchaseDate = filter_input(INPUT_POST, 'purchase_date');
                $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
                $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
                $items = $_POST['items'] ?? [];

                // Basic validation
                if (!$supplierId || !$purchaseDate || empty($items)) {
                    throw new Exception('Please fill in all required fields.');
                }

                // Prepare purchase data
                $purchaseData = [
                    'supplier_id' => $supplierId,
                    'purchase_date' => $purchaseDate,
                    'status' => $status,
                    'notes' => $notes,
                    'items' => $items
                ];

                // Create the purchase
                $purchaseId = $this->purchaseModel->create($purchaseData);

                if ($purchaseId) {
                    if ($this->isAjaxRequest()) {
                        echo json_encode(['success' => true, 'message' => 'Purchase created successfully!']);
                        exit;
                    } else {
                        flash('success', 'Purchase created successfully!');
                        redirect('purchase/index');
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
                    redirect('purchase/create');
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

            $data = [
                'title' => 'Purchase Details',
                'purchase' => $purchase
            ];
            $this->view('admin/purchases/show', $data);
        } catch (Exception $e) {
            error_log('Error in PurchaseController::show - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('purchase/index');
        }
    }

    // Show form to edit an existing purchase
    public function edit($id) {
        try {
            $purchase = $this->purchaseModel->getPurchaseById($id);
            $suppliers = $this->supplierModel->getAllSuppliers();
            
            // Get all active products with required fields
            $products = $this->productModel->getActiveProducts();
            
            if (!$purchase) {
                throw new Exception('Purchase not found');
            }
            
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
                $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
                $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
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
                    redirect('purchase/index');
                } else {
                    throw new Exception('Failed to update purchase.');
                }
            } catch (Exception $e) {
                error_log('Error in PurchaseController::update - ' . $e->getMessage());
                flash('error', $e->getMessage());
                
                // Repopulate form data
                $_SESSION['form_data'] = $_POST;
                redirect('purchase/edit/' . $id);
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
            redirect('purchase/index');
        } catch (Exception $e) {
            error_log('Error in PurchaseController::delete - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('purchase/index');
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
}
