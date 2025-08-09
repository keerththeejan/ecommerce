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
        $supplier = $this->supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            flash('supplier_error', 'Supplier not found', 'alert alert-danger');
            redirect('supplier');
        }
        
        $data = [
            'title' => 'Supplier Details',
            'supplier' => $supplier
        ];
        
        $this->view('admin/suppliers/details', $data);
    }
}
