<?php
/**
 * Invoice Controller
 * Handles invoice generation and management
 */
class InvoiceController extends Controller {
    private $invoiceModel;
    private $orderModel;
    
    public function __construct() {
        $this->invoiceModel = $this->model('Invoice');
        $this->orderModel = $this->model('Order');
    }
    
    /**
     * Display customer invoices
     */
    public function index() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
            return;
        }
        
        try {
            // Get invoices
            $invoices = $this->invoiceModel->getInvoicesByUser($_SESSION['user_id']);
            
            // Load view with invoices
            $this->view('customer/invoices/index', [
                'invoices' => $invoices
            ]);
            
        } catch (Exception $e) {
            // Log the error
            error_log('Error in InvoiceController::index: ' . $e->getMessage());
            
            // Set error message for the user
            $_SESSION['error'] = 'Unable to load invoices. Please try again later.';
            
            // Redirect to home or show error page
            redirect('home');
        }
    }
    
    /**
     * View invoice details
     * 
     * @param int $invoiceId Invoice ID
     */
    public function show($invoiceId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get invoice with order details
        $invoice = $this->invoiceModel->getInvoiceWithOrder($invoiceId);
        
        // Check if invoice exists and belongs to user
        if(!$invoice || $invoice['user_id'] != $_SESSION['user_id']) {
            flash('invoice_error', 'Invoice not found', 'alert alert-danger');
            redirect('invoice');
        }
        
        // Load view
        $this->view('customer/invoices/show', [
            'invoice' => $invoice
        ]);
    }
    
    /**
     * Download invoice as PDF
     * 
     * @param int $invoiceId Invoice ID
     */
    public function download($invoiceId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get invoice with order details
        $invoice = $this->invoiceModel->getInvoiceWithOrder($invoiceId);
        
        // Check if invoice exists and belongs to user
        if(!$invoice || $invoice['user_id'] != $_SESSION['user_id']) {
            flash('invoice_error', 'Invoice not found', 'alert alert-danger');
            redirect('invoice');
        }
        
        // Generate PDF (you'll need to implement this method)
        $this->invoiceModel->generatePdf($invoiceId);
    }
    
    /**
     * Print invoice
     * 
     * @param int $invoiceId Invoice ID
     */
    public function print($invoiceId) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get invoice with order details
        $invoice = $this->invoiceModel->getInvoiceWithOrder($invoiceId);
        
        // Check if invoice exists and belongs to user
        if(!$invoice || $invoice['user_id'] != $_SESSION['user_id']) {
            flash('invoice_error', 'Invoice not found', 'alert alert-danger');
            redirect('invoice');
        }
        
        // Load print view
        $this->view('customer/invoices/print', [
            'invoice' => $invoice
        ]);
    }
}
