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
            redirect('supplier');
        }
        
        // Get supplier details
        $result = $this->supplierModel->getSupplierById($id);
        if (!$result || (is_array($result) && empty($result['success']))) {
            $message = is_array($result) && isset($result['message']) ? $result['message'] : 'Supplier not found';
            if (!empty($_GET['ajax'])) {
                echo json_encode(['success' => false, 'message' => $message]);
                return;
            } else {
                flash('supplier_error', $message, 'alert alert-danger');
                redirect('supplier');
            }
        }
        $supplierData = is_array($result) && isset($result['data']) ? $result['data'] : $result;
        
        // If AJAX requested, return JSON
        if (!empty($_GET['ajax'])) {
            echo json_encode(['success' => true, 'supplier' => $supplierData]);
            return;
        }
        
        $data = [
            'title' => 'Supplier Details',
            'supplier' => $supplierData
        ];
        
        $this->view('admin/suppliers/details', $data);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'id' => isset($_POST['id']) ? trim($_POST['id']) : '',
                'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
                'product_name' => isset($_POST['product_name']) ? trim($_POST['product_name']) : '',
                'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
                'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
                'address' => isset($_POST['address']) ? trim($_POST['address']) : '',
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
            
            // Check for errors
            if (empty($data['name_err']) && empty($data['email_err'])) {
                // Update supplier
                $result = $this->supplierModel->update($data);
                if (is_array($result)) {
                    echo json_encode([
                        'success' => !empty($result['success']),
                        'message' => $result['message'] ?? (!empty($result['success']) ? 'Supplier updated successfully' : 'Failed to update supplier')
                    ]);
                } else {
                    echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Supplier updated successfully' : 'Failed to update supplier']);
                }
            } else {
                // Return validation errors
                echo json_encode([
                    'success' => false, 
                    'errors' => [
                        'name' => $data['name_err'],
                        'email' => $data['email_err']
                    ]
                ]);
            }
        } else {
            redirect('supplier');
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = isset($_POST['id']) ? trim($_POST['id']) : '';
            
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'Invalid supplier ID']);
                return;
            }
            
            // Delete supplier
            $result = $this->supplierModel->delete($id);
            if (is_array($result)) {
                echo json_encode([
                    'success' => !empty($result['success']),
                    'message' => $result['message'] ?? (!empty($result['success']) ? 'Supplier deleted successfully' : 'Failed to delete supplier')
                ]);
            } else {
                echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Supplier deleted successfully' : 'Failed to delete supplier']);
            }
        } else {
            redirect('supplier');
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
                'product_name' => isset($_POST['product_name']) ? trim($_POST['product_name']) : '',
                'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
                'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
                'address' => isset($_POST['address']) ? trim($_POST['address']) : ''
            ];
            
            // Basic validation
            if (empty($data['name'])) {
                echo json_encode(['success' => false, 'errors' => ['name' => 'Please enter supplier name']]);
                return;
            }
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'errors' => ['email' => 'Please enter a valid email']]);
                return;
            }
            
            // Create supplier via model
            $result = $this->supplierModel->create($data);
            if (is_array($result) && !empty($result['success'])) {
                // Fetch the created supplier to return complete data
                $supplier = $this->supplierModel->getSupplierById($result['id']);
                $supplierData = is_array($supplier) && isset($supplier['data']) ? $supplier['data'] : $supplier;
                echo json_encode([
                    'success' => true,
                    'message' => $result['message'] ?? 'Supplier added successfully',
                    'supplier' => $supplierData
                ]);
            } else {
                $message = is_array($result) && isset($result['message']) ? $result['message'] : 'Failed to add supplier';
                echo json_encode(['success' => false, 'message' => $message]);
            }
        } else {
            redirect('supplier');
        }
    }
}
