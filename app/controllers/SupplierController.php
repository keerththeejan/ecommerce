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
    
    public function index() {
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $data = [
                'name' => isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '',
                'email' => isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '',
                'phone' => isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '',
                'address' => isset($_POST['address']) ? trim(htmlspecialchars($_POST['address'])) : '',
                'product_name' => isset($_POST['product_name']) ? trim(htmlspecialchars($_POST['product_name'])) : '',
                'name_err' => '',
                'email_err' => '',
                'phone_err' => ''
            ];
            
            // Validate data
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter supplier name';
            }
            
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone number';
            }
            
            // Make sure no errors
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_err'])) {
                try {
                    // Add supplier to database
                    $result = $this->supplierModel->addSupplier($data);
                    
                    if ($result) {
                        // Get the newly added supplier with products
                        $newSupplier = $this->supplierModel->getSupplierById($result);
                        
                        if ($this->isAjaxRequest()) {
                            // Get all suppliers to ensure we have the latest data
                            $suppliers = $this->supplierModel->getAllSuppliersWithProducts();
                            
                            // Format the response data
                            $response = [
                                'success' => true,
                                'message' => 'Supplier added successfully',
                                'supplier' => [
                                    'id' => $newSupplier->id,
                                    'name' => $newSupplier->name,
                                    'supplier_name' => $newSupplier->supplier_name,
                                    'email' => $newSupplier->email,
                                    'phone' => $newSupplier->phone,
                                    'address' => $newSupplier->address,
                                    'product_name' => $newSupplier->product_name,
                                    'products' => $newSupplier->products
                                ],
                                'suppliers' => array_map(function($supplier) {
                                    return [
                                        'id' => $supplier->id,
                                        'name' => $supplier->name,
                                        'supplier_name' => $supplier->supplier_name,
                                        'email' => $supplier->email,
                                        'phone' => $supplier->phone,
                                        'address' => $supplier->address,
                                        'product_name' => $supplier->product_name,
                                        'products' => $supplier->products
                                    ];
                                }, $suppliers)
                            ];
                            
                            $this->sendJsonResponse($response);
                            return;
                        } else {
                            flash('supplier_success', 'Supplier added successfully');
                            redirect('supplier');
                            return;
                        }
                    } else {
                        throw new Exception('Failed to add supplier to database. Please check the error logs for more details.');
                    }
                } catch (PDOException $e) {
                    error_log('Database Error: ' . $e->getMessage());
                    error_log('SQL State: ' . $e->getCode());
                    error_log('Query: ' . ($e->getTrace()[0]['args'][0] ?? 'N/A'));
                    
                    $errorMessage = 'Database error: ' . $e->getMessage();
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse([
                            'success' => false,
                            'message' => $errorMessage,
                            'errors' => $data,
                            'debug' => [
                                'code' => $e->getCode(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine()
                            ]
                        ]);
                    } else {
                        flash('supplier_error', $errorMessage, 'alert alert-danger');
                        $data['suppliers'] = $this->supplierModel->getAllSuppliersWithProducts();
                        $this->view('admin/suppliers/index', $data);
                    }
                } catch (Exception $e) {
                    error_log('General Error adding supplier: ' . $e->getMessage());
                    error_log('Stack trace: ' . $e->getTraceAsString());
                    
                    $errorMessage = 'Failed to add supplier. ' . $e->getMessage();
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse([
                            'success' => false,
                            'message' => $errorMessage,
                            'errors' => $data,
                            'debug' => [
                                'file' => $e->getFile(),
                                'line' => $e->getLine()
                            ]
                        ]);
                    } else {
                        flash('supplier_error', $errorMessage, 'alert alert-danger');
                        $data['suppliers'] = $this->supplierModel->getAllSuppliersWithProducts();
                        $this->view('admin/suppliers/index', $data);
                    }
                }
            } else {
                // Load view with errors
                $this->view('admin/suppliers/index', $data);
            }
        } else {
            // Get all suppliers with their products
            $suppliers = $this->supplierModel->getAllSuppliersWithProducts();
            
            // Check if this is an AJAX request
            if ($this->isAjaxRequest() && isset($_GET['ajax'])) {
                $this->sendJsonResponse([
                    'success' => true,
                    'suppliers' => $suppliers
                ]);
                return;
            }
            
            $data = [
                'title' => 'Manage Suppliers',
                'suppliers' => $suppliers,
                'name' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
                'product_name' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_err' => ''
            ];
            
            $this->view('admin/suppliers/index', $data);
        }
    }
    
    public function details($id = null) {
        if (!$id) {
            redirect('supplier');
        }
        
        // Get supplier details
        $supplier = $this->supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            flash('supplier_error', 'Supplier not found', 'alert alert-danger');
            redirect('supplier');
        }
        
        $data = [
            'title' => 'Supplier Details',
            'supplier' => $supplier
        ];
        
        // Check if it's an AJAX request for sidebar
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'supplier' => [
                    'id' => $supplier['id'],
                    'name' => htmlspecialchars($supplier['name']),
                    'email' => $supplier['email'] ? htmlspecialchars($supplier['email']) : null,
                    'phone' => $supplier['phone'] ? htmlspecialchars($supplier['phone']) : null,
                    'address' => $supplier['address'] ? nl2br(htmlspecialchars($supplier['address'])) : null
                ]
            ]);
            exit;
        }
        
        $this->view('admin/suppliers/details', $data);
    }
}
