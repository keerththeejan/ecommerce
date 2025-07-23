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
                    flash('success', 'Purchase created successfully!');
                    redirect('purchase/index');
                } else {
                    throw new Exception('Failed to create purchase.');
                }
            } catch (Exception $e) {
                error_log('Error in PurchaseController::store - ' . $e->getMessage());
                flash('error', $e->getMessage());
                
                // Repopulate form data
                $_SESSION['form_data'] = $_POST;
                redirect('purchase/create');
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
