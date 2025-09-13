<?php
/**
 * Invoice Controller
 * Handles invoice generation and management
 */
class InvoiceController extends Controller {
    private $invoiceModel;
    private $orderModel;
    
    public function __construct() {
        // Initialize base controller (DB connection, etc.)
        parent::__construct();
        $this->invoiceModel = $this->model('Invoice');
        $this->orderModel = $this->model('Order');
    }

    /**
     * Admin: Create invoice from an Order
     */
    public function create() {
        // Require admin login
        if (!isAdmin()) {
            redirect('user/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : (isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0);
            if ($orderId <= 0) {
                flash('invoice_error', 'Please provide a valid Order ID', 'alert alert-danger');
                $this->view('admin/invoices/create');
                return;
            }

            // Ensure order exists
            $order = $this->orderModel->getById($orderId);
            if (!$order) {
                flash('invoice_error', 'Order not found', 'alert alert-danger');
                $this->view('admin/invoices/create');
                return;
            }

            // Create or get existing invoice id, then redirect
            $invoiceId = $this->invoiceModel->createOrGetInvoiceId($orderId);
            if ($invoiceId) {
                flash('invoice_success', 'Invoice ready');
                // Redirect to POS for final processing with original order id to preload items
                $this->redirect(BASE_URL . '?controller=pos&action=index&order_id=' . (int)$orderId);
                return;
            }
            // Failure
            $err = method_exists($this->invoiceModel, 'getLastError') ? $this->invoiceModel->getLastError() : '';
            $msg = 'Failed to create invoice' . ($err ? (': ' . $err) : '');
            flash('invoice_error', $msg, 'alert alert-danger');
            $this->redirect(BASE_URL . '?controller=order&action=adminShow&id=' . (int)$orderId);
            return;
        }

        // GET without order_id: show form
        $this->view('admin/invoices/create');
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
        // Allow admins; otherwise require customer login
        if (!isAdmin() && !isLoggedIn()) {
            redirect('user/login');
            return;
        }

        // Get invoice with order details
        $invoice = $this->invoiceModel->getInvoiceWithOrder($invoiceId);

        if (!$invoice) {
            flash('invoice_error', 'Invoice not found', 'alert alert-danger');
            redirect('invoice');
            return;
        }

        // If not admin, ensure invoice belongs to logged-in customer
        if (!isAdmin()) {
            if (!isset($_SESSION['user_id']) || $invoice['user_id'] != $_SESSION['user_id']) {
                flash('invoice_error', 'Invoice not found', 'alert alert-danger');
                redirect('invoice');
                return;
            }
        }

        // Load print view (reuse existing view)
        $this->view('customer/invoices/print', [
            'invoice' => $invoice
        ]);
    }
}
