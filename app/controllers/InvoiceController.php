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
     * Admin: Create invoice from an Order
     */
    public function create() {
        // Require admin login
        if (!isAdmin()) {
            redirect('user/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
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

            // Try to create invoice (idempotent behavior)
            if ($this->invoiceModel->createInvoice($orderId)) {
                // Created now; fetch id and go to print
                $invoiceId = $this->invoiceModel->getInvoiceIdByOrderId($orderId);
                flash('invoice_success', 'Invoice created successfully');
                if ($invoiceId) {
                    redirect('?controller=invoice&action=print&id=' . (int)$invoiceId);
                } else {
                    // Fallback: go back to order page
                    redirect('?controller=order&action=adminShow&id=' . (int)$orderId);
                }
                return;
            } else {
                // May already exist; try to find it and redirect
                $existingId = $this->invoiceModel->getInvoiceIdByOrderId($orderId);
                if ($existingId) {
                    flash('invoice_success', 'Invoice already exists for this order');
                    redirect('?controller=invoice&action=print&id=' . (int)$existingId);
                    return;
                }
                // Real failure
                flash('invoice_error', 'Failed to create invoice', 'alert alert-danger');
                redirect('?controller=order&action=adminShow&id=' . (int)$orderId);
                return;
            }
        }

        // GET: show form
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
