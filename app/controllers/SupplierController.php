<?php

class SupplierController extends Controller {
    private $supplierModel;
    
    public function __construct() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect('user/login');
        }

        $this->supplierModel = $this->model('Supplier');
    }

    /**
     * Update an existing supplier
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => isset($_POST['id']) ? (int) $_POST['id'] : 0,
                'name' => trim($_POST['name'] ?? ''),
                'product_name' => trim($_POST['product_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'name_err' => '',
                'email_err' => ''
            ];

            if (empty($data['id'])) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['success' => false, 'message' => 'Invalid supplier ID']);
                exit;
            }

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }

            if (empty($data['name_err']) && empty($data['email_err'])) {
                if ($this->supplierModel->update($data)) {
                    $supplier = $this->supplierModel->getSupplierById($data['id']);
                    while (ob_get_level()) { ob_end_clean(); }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'success' => true,
                        'message' => 'Supplier updated successfully',
                        'supplier' => [
                            'id' => $supplier['id'],
                            'name' => $supplier['name'],
                            'product_name' => $supplier['product_name'] ?? null,
                            'email' => $supplier['email'] ?? null,
                            'phone' => $supplier['phone'] ?? null,
                            'address' => $supplier['address'] ?? null
                        ]
                    ]);
                    exit;
                } else {
                    while (ob_get_level()) { ob_end_clean(); }
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['success' => false, 'message' => 'Failed to update supplier']);
                    exit;
                }
            } else {
                while (ob_get_level()) { ob_end_clean(); }
                header('HTTP/1.1 422 Unprocessable Entity');
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'errors' => [
                        'name' => $data['name_err'],
                        'email' => $data['email_err']
                    ]
                ]);
                exit;
            }
        } else {
            redirect('?controller=supplier&action=index');
        }
    }

    /**
     * Delete a supplier
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

            if (!$id) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['success' => false, 'message' => 'Invalid supplier ID']);
                exit;
            }

            if ($this->supplierModel->delete($id)) {
                while (ob_get_level()) { ob_end_clean(); }
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true, 'message' => 'Supplier deleted successfully']);
                exit;
            } else {
                while (ob_get_level()) { ob_end_clean(); }
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Failed to delete supplier']);
                exit;
            }
        } else {
            redirect('?controller=supplier&action=index');
        }
    }
    
    public function index() {
        // Get all suppliers
        $suppliers = $this->supplierModel->getAllSuppliers();
        
        $data = [
            'title' => 'Manage Suppliers',
            'suppliers' => $suppliers
        ];
        
        $this->view('admin/suppliers/index', $data);
    }
    
    public function details($id = null) {
        if (!$id) {
            redirect('?controller=supplier&action=index');
        }
        
        // Get supplier details
        $supplier = $this->supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            flash('supplier_error', 'Supplier not found', 'alert alert-danger');
            redirect('?controller=supplier&action=index');
        }
        
        $data = [
            'title' => 'Supplier Details',
            'supplier' => $supplier
        ];
        
        // Check if it's an AJAX request for sidebar
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            while (ob_get_level()) { ob_end_clean(); }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'supplier' => [
                    'id' => $supplier['id'],
                    'name' => htmlspecialchars($supplier['name']),
                    'product_name' => !empty($supplier['product_name']) ? htmlspecialchars($supplier['product_name']) : null,
                    'email' => $supplier['email'] ? htmlspecialchars($supplier['email']) : null,
                    'phone' => $supplier['phone'] ? htmlspecialchars($supplier['phone']) : null,
                    'address' => $supplier['address'] ? nl2br(htmlspecialchars($supplier['address'])) : null
                ]
            ]);
            exit;
        }
        
        $this->view('admin/suppliers/details', $data);
    }
    
    /**
     * Create a new supplier
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Initialize data
            $data = [
                'name' => trim($_POST['name']),
                'product_name' => trim($_POST['product_name'] ?? ''),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'name_err' => '',
                'email_err' => ''
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }
            
            // Validate email if provided
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            // Make sure there are no errors
            if (empty($data['name_err']) && empty($data['email_err'])) {
                // Save supplier
                $supplierId = $this->supplierModel->create($data);
                if ($supplierId) {
                    // Get the newly created supplier
                    $supplier = $this->supplierModel->getSupplierById($supplierId);
                    
                    // Return JSON response for AJAX requests
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        while (ob_get_level()) { ob_end_clean(); }
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode([
                            'success' => true,
                            'message' => 'Supplier added successfully',
                            'supplier' => [
                                'id' => $supplier['id'],
                                'name' => $supplier['name'],
                                'product_name' => $supplier['product_name'] ?? null,
                                'email' => $supplier['email'] ?? null,
                                'phone' => $supplier['phone'] ?? null,
                                'address' => $supplier['address'] ?? null
                            ]
                        ]);
                        exit;
                    } else {
                        flash('supplier_success', 'Supplier added successfully');
                        redirect('?controller=supplier&action=index');
                    }
                } else {
                    $error = 'Something went wrong. Please try again.';
                    
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        while (ob_get_level()) { ob_end_clean(); }
                        header('HTTP/1.1 500 Internal Server Error');
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode([
                            'success' => false,
                            'message' => $error
                        ]);
                        exit;
                    } else {
                        flash('supplier_error', $error, 'alert alert-danger');
                        $this->view('admin/suppliers/index', $data);
                    }
                }
            } else {
                // Return validation errors for AJAX
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    while (ob_get_level()) { ob_end_clean(); }
                    header('HTTP/1.1 422 Unprocessable Entity');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode([
                        'success' => false,
                        'errors' => [
                            'name' => $data['name_err'],
                            'email' => $data['email_err']
                        ]
                    ]);
                    exit;
                } else {
                    // Load view with errors for non-AJAX requests
                    $this->view('admin/suppliers/index', $data);
                }
            }
        } else {
            // If not a POST request, redirect to suppliers
            redirect('?controller=supplier&action=index');
        }
    }
}
