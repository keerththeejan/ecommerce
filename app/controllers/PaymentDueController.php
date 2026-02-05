<?php
class PaymentDueController {
    private $purchaseModel;
    private $supplierModel;
    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new Database;
        
        // Load models
        require_once __DIR__ . '/../models/Purchase.php';
        require_once __DIR__ . '/../models/Supplier.php';
        $this->purchaseModel = new Purchase();
        $this->supplierModel = new Supplier();
    }

    // List all payment dues
    public function index() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        $search = '';
        $customerId = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
        $dueOnly = isset($_GET['due_only']) && $_GET['due_only'] == 1;
        
        // Get all customers for dropdown
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User();
        
        // Get customers based on filter
        if ($dueOnly) {
            $customers = $this->purchaseModel->getCustomersWithDuePayments();
        } else {
            $customers = $userModel->getAllCustomers();
        }
        
        // If customer is selected, get their purchases
        $purchases = [];
        if ($customerId > 0) {
            // Find the selected customer
            $selectedCustomer = null;
            foreach ($customers as $customer) {
                if ($customer['id'] == $customerId) {
                    $selectedCustomer = $customer;
                    break;
                }
            }
            
            if ($selectedCustomer) {
                // Set search to the customer's name to filter purchases
                $search = $selectedCustomer['name'];
            }
        }
        
        // Get purchases with payment due, optionally filtered by customer
        $purchases = $this->purchaseModel->getPurchasesWithDuePayment($search);

        // Build customer dues dataset for the Customer Due table
        $customerDues = [];
        if (!empty($purchases)) {
            foreach ($purchases as $p) {
                // Normalize to array for consistent access
                if (is_object($p)) {
                    $pArr = [
                        'id' => $p->id ?? null,
                        'invoice_no' => $p->invoice_no ?? ($p->purchase_no ?? ''),
                        'customer_name' => $p->supplier_name ?? '',
                        'phone' => $p->supplier_phone ?? ($p->phone ?? ''),
                        'purchase_date' => $p->purchase_date ?? null,
                        'total_amount' => $p->total_amount ?? 0,
                        'paid_amount' => $p->paid_amount ?? 0,
                        // Map generic amount for UI (same as total if not separately tracked)
                        'amount' => $p->total_amount ?? 0,
                        'due_amount' => $p->due_amount ?? (max(($p->total_amount ?? 0) - ($p->paid_amount ?? 0), 0)),
                        'payment_status' => $p->payment_status ?? 'unpaid',
                    ];
                } else {
                    $pArr = [
                        'id' => $p['id'] ?? null,
                        'invoice_no' => $p['invoice_no'] ?? ($p['purchase_no'] ?? ''),
                        'customer_name' => $p['supplier_name'] ?? '',
                        'phone' => $p['supplier_phone'] ?? ($p['phone'] ?? ''),
                        'purchase_date' => $p['purchase_date'] ?? null,
                        'total_amount' => $p['total_amount'] ?? 0,
                        'paid_amount' => $p['paid_amount'] ?? 0,
                        'amount' => $p['total_amount'] ?? 0,
                        'due_amount' => $p['due_amount'] ?? (max(($p['total_amount'] ?? 0) - ($p['paid_amount'] ?? 0), 0)),
                        'payment_status' => $p['payment_status'] ?? 'unpaid',
                    ];
                }
                $customerDues[] = $pArr;
            }
        }

        $data = [
            'title' => $dueOnly ? 'Customers with Payment Dues' : 'Payment Dues',
            'purchases' => $purchases,
            'search' => $search,
            'customers' => $customers,
            'selected_customer_id' => $customerId,
            'due_only' => $dueOnly,
            // Pass normalized customer dues to the view
            'customer_dues' => $customerDues,
        ];

        // Get all suppliers for the add payment form
        $suppliersResult = $this->supplierModel->getAllSuppliers();
        
        // Initialize empty suppliers array
        $suppliers = [];
        
        // Check if we got a valid result
        if (is_array($suppliersResult)) {
            // If it's an array, check if it's an error response
            if (isset($suppliersResult['success']) && $suppliersResult['success'] === false) {
                // Log the error but continue with empty array
                error_log('Error fetching suppliers: ' . ($suppliersResult['message'] ?? 'Unknown error'));
            } else {
                // It's a valid array of suppliers
                $suppliers = $suppliersResult;
            }
        } elseif (is_object($suppliersResult)) {
            // If it's a single object, convert to array
            $suppliers = [$suppliersResult];
        }
        
        $data['suppliers'] = $suppliers;
        
        // Load view
        require_once __DIR__ . '/../views/admin/payment_due/index.php';
    }

    /**
     * Handle Add Customer Payment (Customer Due) modal submission
     * For now, just validate, flash, and redirect back to index.
     */
    public function addCustomerDue() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URLROOT . '/?controller=PaymentDue&action=index');
            exit;
        }

        // Sanitize POST
        $invoiceNo = trim($_POST['invoice_no'] ?? '');
        $customerName = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $purchaseDate = trim($_POST['purchase_date'] ?? '');
        $paymentDate = trim($_POST['payment_date'] ?? '');
        $totalAmount = (float)($_POST['total_amount'] ?? 0);
        $paidAmount = (float)($_POST['paid_amount'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $dueAmount = (float)($_POST['due_amount'] ?? 0);
        $paymentStatus = trim($_POST['payment_status'] ?? 'unpaid');
        $notes = trim($_POST['notes'] ?? '');

        // Basic validation
        $errors = [];
        if ($invoiceNo === '') { $errors[] = 'Invoice No is required'; }
        if ($customerName === '') { $errors[] = 'Customer Name is required'; }
        if ($totalAmount < 0) { $errors[] = 'Total must be >= 0'; }
        if ($paidAmount < 0) { $errors[] = 'Amount Paid must be >= 0'; }

        if (!empty($errors)) {
            if (function_exists('flash')) {
                flash('payment_error', implode("\n", $errors));
            }
            header('Location: ' . URLROOT . '/?controller=PaymentDue&action=index');
            exit;
        }

        // TODO: Persist to database once customer dues storage is finalized.
        if (function_exists('flash')) {
            flash('payment_message', 'Customer payment saved (demo).');
        }

        header('Location: ' . URLROOT . '/?controller=PaymentDue&action=index');
        exit;
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

        require_once __DIR__ . '/../views/admin/payment_due/view.php';
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

        require_once __DIR__ . '/../views/admin/payment_due/report.php';
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

    // Add new payment
    public function add() {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || !isAdmin()) {
            redirect('users/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Prepare data
            $data = [
                'invoice_no' => trim($_POST['invoice_no']),
                'supplier_id' => trim($_POST['supplier_id']),
                'purchase_date' => trim($_POST['purchase_date']),
                'total_amount' => trim($_POST['total_amount']),
                'paid_amount' => trim($_POST['paid_amount']),
                'due_amount' => trim($_POST['due_amount']),
                'payment_status' => trim($_POST['payment_status']),
                'payment_date' => !empty($_POST['payment_date']) ? trim($_POST['payment_date']) : date('Y-m-d'),
                'notes' => trim($_POST['notes'] ?? ''),
                'invoice_no_err' => '',
                'supplier_id_err' => '',
                'total_amount_err' => '',
                'paid_amount_err' => ''
            ];

            // Validate data
            if (empty($data['invoice_no'])) {
                $data['invoice_no_err'] = 'Please enter invoice number';
            }

            if (empty($data['supplier_id'])) {
                $data['supplier_id_err'] = 'Please select a supplier';
            }

            if (empty($data['total_amount']) || $data['total_amount'] <= 0) {
                $data['total_amount_err'] = 'Please enter a valid total amount';
            }

            if (empty($data['paid_amount']) || $data['paid_amount'] < 0) {
                $data['paid_amount_err'] = 'Please enter a valid paid amount';
            }

            // Make sure errors are empty
            if (empty($data['invoice_no_err']) && empty($data['supplier_id_err']) && 
                empty($data['total_amount_err']) && empty($data['paid_amount_err'])) {
                
                // Format amounts
                $data['total_amount'] = (float)$data['total_amount'];
                $data['paid_amount'] = (float)$data['paid_amount'];
                $data['due_amount'] = (float)$data['due_amount'];
                
                // Insert into purchases table
                $this->db->query('INSERT INTO purchases (
                    invoice_no, 
                    supplier_id, 
                    purchase_date, 
                    total_amount, 
                    paid_amount, 
                    due_amount, 
                    payment_status, 
                    payment_date, 
                    notes,
                    created_at,
                    updated_at
                ) VALUES (
                    :invoice_no, 
                    :supplier_id, 
                    :purchase_date, 
                    :total_amount, 
                    :paid_amount, 
                    :due_amount, 
                    :payment_status, 
                    :payment_date, 
                    :notes,
                    NOW(),
                    NOW()
                )');

                // Bind values
                $this->db->bind(':invoice_no', $data['invoice_no']);
                $this->db->bind(':supplier_id', $data['supplier_id']);
                $this->db->bind(':purchase_date', $data['purchase_date']);
                $this->db->bind(':total_amount', $data['total_amount']);
                $this->db->bind(':paid_amount', $data['paid_amount']);
                $this->db->bind(':due_amount', $data['due_amount']);
                $this->db->bind(':payment_status', $data['payment_status']);
                $this->db->bind(':payment_date', $data['payment_date']);
                $this->db->bind(':notes', $data['notes']);

                // Execute
                if ($this->db->execute()) {
                    // If payment was made, record it in the payments table
                    if ($data['paid_amount'] > 0) {
                        $purchaseId = $this->db->lastInsertId();
                        
                        $this->db->query('INSERT INTO payments (
                            purchase_id,
                            amount,
                            payment_date,
                            payment_method,
                            reference_no,
                            notes,
                            created_at
                        ) VALUES (
                            :purchase_id,
                            :amount,
                            :payment_date,
                            :payment_method,
                            :reference_no,
                            :notes,
                            NOW()
                        )');

                        $this->db->bind(':purchase_id', $purchaseId);
                        $this->db->bind(':amount', $data['paid_amount']);
                        $this->db->bind(':payment_date', $data['payment_date']);
                        $this->db->bind(':payment_method', 'cash'); // Default to cash, you can modify this
                        $this->db->bind(':reference_no', $data['invoice_no']);
                        $this->db->bind(':notes', 'Initial payment - ' . $data['notes']);
                        
                        $this->db->execute();
                    }
                    
                    flash('payment_message', 'Payment added successfully');
                    redirect('paymentdue');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                require_once __DIR__ . '/../views/admin/payment_due/index.php';
            }
        } else {
            // Init data
            $data = [
                'invoice_no' => '',
                'supplier_id' => '',
                'purchase_date' => date('Y-m-d'),
                'total_amount' => '',
                'paid_amount' => '',
                'due_amount' => '',
                'payment_status' => 'unpaid',
                'payment_date' => date('Y-m-d'),
                'notes' => '',
                'invoice_no_err' => '',
                'supplier_id_err' => '',
                'total_amount_err' => '',
                'paid_amount_err' => ''
            ];

            // Load view
            require_once __DIR__ . '/../views/admin/payment_due/index.php';
        }
    }
}