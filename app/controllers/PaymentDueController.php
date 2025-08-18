<?php
class PaymentDueController {
    private $purchaseModel;
    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new Database;
        
        // Load models
        require_once __DIR__ . '/../models/Purchase.php';
        $this->purchaseModel = new Purchase();
    }

    // List all payment dues
    public function index() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        // Get all purchases with payment due
        $purchases = $this->purchaseModel->getPurchasesWithDuePayment();
        
        $data = [
            'title' => 'Payment Dues',
            'purchases' => $purchases
        ];

        // Load view
        require_once __DIR__ . '/../views/admin/payment_due/index.php';
    }

    // View payment due details
    public function view($id) {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        // Get purchase with payment details
        $purchase = $this->purchaseModel->getPurchaseById($id);
        
        if (!$purchase) {
            flash('payment_error', 'Purchase not found', 'alert alert-danger');
            redirect('paymentdue');
        }

        $data = [
            'title' => 'Payment Due Details',
            'purchase' => $purchase
        ];

        require_once '../views/admin/payment_due/view.php';
    }

    // Update payment status
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'payment_status' => trim($_POST['payment_status']),
                'payment_date' => !empty($_POST['payment_date']) ? trim($_POST['payment_date']) : date('Y-m-d H:i:s'),
                'payment_method' => trim($_POST['payment_method']),
                'transaction_id' => trim($_POST['transaction_id']),
                'notes' => trim($_POST['notes'])
            ];

            // Update payment status
            if ($this->purchaseModel->updatePaymentStatus($data)) {
                flash('payment_message', 'Payment status updated successfully');
                redirect('paymentdue/view/' . $id);
            } else {
                flash('payment_error', 'Failed to update payment status', 'alert alert-danger');
                redirect('paymentdue/view/' . $id);
            }
        } else {
            redirect('paymentdue');
        }
    }

    // Clear payment due
    public function clearDue($id) {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
            return;
        }

        // Get purchase details
        $purchase = $this->purchaseModel->getPurchaseById($id);
        
        if (!$purchase) {
            flash('error', 'Purchase not found', 'alert alert-danger');
            redirect('paymentdue');
            return;
        }

        // Update payment status and clear due amount
        $data = [
            'id' => $id,
            'payment_status' => 'paid',
            'due_amount' => 0,
            'paid_amount' => $purchase->total_amount
        ];

        if ($this->purchaseModel->updatePurchase($data)) {
            // Record payment transaction
            $paymentData = [
                'purchase_id' => $id,
                'amount' => $purchase->due_amount,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_method' => 'cash',
                'notes' => 'Full payment received to clear due',
                'created_by' => $_SESSION['user_id']
            ];
            
            if ($this->purchaseModel->recordPayment($paymentData)) {
                flash('success', 'Payment due cleared successfully');
            } else {
                flash('error', 'Failed to record payment transaction', 'alert alert-danger');
            }
        } else {
            flash('error', 'Failed to update payment status', 'alert alert-danger');
        }

        redirect('paymentdue');
    }

    // Clear all payment dues
    public function clearAllDues() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
            return;
        }

        // Get all purchases with due amount
        $purchases = $this->purchaseModel->getPurchasesWithDuePayment();
        $successCount = 0;
        $errorCount = 0;

        if (!empty($purchases)) {
            foreach ($purchases as $purchase) {
                // Update payment status and clear due amount
                $data = [
                    'id' => $purchase->id,
                    'payment_status' => 'paid',
                    'due_amount' => 0,
                    'paid_amount' => $purchase->total_amount
                ];

                if ($this->purchaseModel->updatePurchase($data)) {
                    // Record payment transaction
                    $paymentData = [
                        'purchase_id' => $purchase->id,
                        'amount' => $purchase->due_amount,
                        'payment_date' => date('Y-m-d H:i:s'),
                        'payment_method' => 'cash',
                        'notes' => 'Bulk payment - All dues cleared',
                        'created_by' => $_SESSION['user_id']
                    ];
                    
                    if ($this->purchaseModel->recordPayment($paymentData)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } else {
                    $errorCount++;
                }
            }

            if ($successCount > 0) {
                flash('success', 'Successfully cleared ' . $successCount . ' payment dues');
            }
            if ($errorCount > 0) {
                flash('error', 'Failed to clear ' . $errorCount . ' payment dues', 'alert alert-danger');
            }
        } else {
            flash('info', 'No pending payment dues found to clear');
        }

        redirect('paymentdue');
    }

    // Generate payment due report
    public function report() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        // Get date range from query parameters
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

        // Get payment due report
        $report = $this->purchaseModel->getPaymentDueReport($startDate, $endDate);
        
        $data = [
            'title' => 'Payment Due Report',
            'report' => $report,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        require_once '../views/admin/payment_due/report.php';
    }

    // Export payment due report to Excel
    public function export() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

        // Get payment due report
        $report = $this->purchaseModel->getPaymentDueReport($startDate, $endDate);

        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="payment_due_report_' . date('Y-m-d') . '.xls"');
        
        // Output Excel content
        echo "Payment Due Report\n\n";
        echo "Period: $startDate to $endDate\n\n";
        echo "ID\tSupplier\tInvoice No\tPurchase Date\tTotal Amount\tPaid Amount\tDue Amount\tStatus\tDue Date\n";
        
        foreach ($report as $item) {
            echo $item->id . "\t";
            echo $item->supplier_name . "\t";
            echo $item->invoice_no . "\t";
            echo $item->purchase_date . "\t";
            echo number_format($item->total_amount, 2) . "\t";
            echo number_format($item->paid_amount, 2) . "\t";
            echo number_format($item->due_amount, 2) . "\t";
            echo ucfirst($item->payment_status) . "\t";
            echo $item->due_date . "\n";
        }
        exit;
    }
}