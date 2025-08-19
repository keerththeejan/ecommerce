<?php

// Include required model files
require_once APP_PATH . 'models/Purchase.php';
require_once APP_PATH . 'models/Supplier.php';
require_once APP_PATH . 'models/Product.php';
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Notification.php';

class ListPurchaseController {
    private $purchaseModel;
    private $supplierModel;
    private $productModel;
    private $userModel;
    private $notificationModel;
    private $db;

    public function __construct() {
        // Initialize database connection
        $this->db = new Database();
        
// Initialize models with database connection
        $this->purchaseModel = new Purchase($this->db);
        $this->supplierModel = new Supplier($this->db);
        $this->productModel = new Product($this->db);
        $this->userModel = new User($this->db);
        $this->notificationModel = new Notification($this->db);
        
        // Check if user is logged in
        $this->checkLogin();
    }
    
    /**
     * Check if user is logged in
     */
    private function checkLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    /**
     * List all purchases with pagination
     */
    public function index() {
        try {
            // Pagination settings
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 20;
            
            // Get all purchases with pagination
            $purchases = $this->purchaseModel->getAllPurchases($page, $perPage);
            $totalPurchases = $this->purchaseModel->countAllPurchases();
            $totalPages = ceil($totalPurchases / $perPage);
            
            // Get suppliers for filter dropdown
            $suppliers = $this->supplierModel->getAllSuppliers();
            
            // Prepare data for view
            $data = [
                'title' => 'Purchase List',
                'purchases' => $purchases,
                'suppliers' => $suppliers,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalPurchases' => $totalPurchases,
                'perPage' => $perPage
            ];
            
            // Load the view
            $this->view('purchases/list', $data);
            
        } catch (Exception $e) {
            error_log('Error in ListPurchaseController::index - ' . $e->getMessage());
            $this->view('errors/500');
        }
    }
    
    /**
     * View a single purchase
     */
    public function view($id) {
        try {
            // Get purchase details
            $purchase = $this->purchaseModel->getPurchaseById($id);
            
            if (!$purchase) {
                throw new Exception('Purchase not found');
            }
            
            // Get purchase items
            $items = $this->purchaseModel->getPurchaseItems($id);
            
            // Get payment history if any
            $payments = $this->purchaseModel->getPurchasePayments($id);
            
            // Prepare data for view
            $data = [
                'title' => 'View Purchase #' . $purchase['id'],
                'purchase' => $purchase,
                'items' => $items,
                'payments' => $payments
            ];
            
            // Load the view
            $this->view('purchases/view', $data);
            
        } catch (Exception $e) {
            error_log('Error in ListPurchaseController::view - ' . $e->getMessage());
            $this->view('errors/404');
        }
    }
    
    /**
     * Filter purchases by various criteria
     */
    public function filter() {
        try {
            $filters = [
                'start_date' => $_GET['start_date'] ?? '',
                'end_date' => $_GET['end_date'] ?? '',
                'supplier_id' => !empty($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : null,
                'status' => $_GET['status'] ?? '',
                'payment_status' => $_GET['payment_status'] ?? '',
                'search' => $_GET['search'] ?? ''
            ];
            
            // Pagination
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = 20;
            
            // Get filtered purchases
            $purchases = $this->purchaseModel->getFilteredPurchases($filters, $page, $perPage);
            $totalPurchases = $this->purchaseModel->countFilteredPurchases($filters);
            $totalPages = ceil($totalPurchases / $perPage);
            
            // Get suppliers for filter dropdown
            $suppliers = $this->supplierModel->getAllSuppliers();
            
            // Prepare data for view
            $data = [
                'title' => 'Filtered Purchases',
                'purchases' => $purchases,
                'suppliers' => $suppliers,
                'filters' => $filters,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalPurchases' => $totalPurchases,
                'perPage' => $perPage
            ];
            
            // Return JSON for AJAX requests
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'data' => $purchases,
                    'pagination' => [
                        'currentPage' => $page,
                        'totalPages' => $totalPages,
                        'totalItems' => $totalPurchases
                    ]
                ]);
                return;
            }
            
            // Load the view for regular requests
            $this->view('purchases/list', $data);
            
        } catch (Exception $e) {
            error_log('Error in ListPurchaseController::filter - ' . $e->getMessage());
            
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error filtering purchases: ' . $e->getMessage()
                ]);
            } else {
                $this->view('errors/500');
            }
        }
    }
    
    /**
     * Export purchases to CSV
     */
    public function export() {
        try {
            $filters = [
                'start_date' => $_GET['start_date'] ?? '',
                'end_date' => $_GET['end_date'] ?? '',
                'supplier_id' => !empty($_GET['supplier_id']) ? (int)$_GET['supplier_id'] : null,
                'status' => $_GET['status'] ?? '',
                'payment_status' => $_GET['payment_status'] ?? ''
            ];
            
            // Get all matching purchases (no pagination for export)
            $purchases = $this->purchaseModel->getFilteredPurchases($filters, 1, 10000);
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=purchases_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fputs($output, "\xEF\xBB\xBF");
            
            // Add headers
            fputcsv($output, [
                'Purchase ID',
                'Date',
                'Supplier',
                'Total Amount',
                'Paid Amount',
                'Balance',
                'Status',
                'Payment Status',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($purchases as $purchase) {
                fputcsv($output, [
                    $purchase['id'],
                    $purchase['purchase_date'],
                    $purchase['supplier_name'],
                    $purchase['total_amount'],
                    $purchase['paid_amount'] ?? 0,
                    $purchase['balance'] ?? $purchase['total_amount'],
                    ucfirst($purchase['status']),
                    ucfirst($purchase['payment_status']),
                    $purchase['created_at']
                ]);
            }
            
            fclose($output);
            exit();
            
        } catch (Exception $e) {
            error_log('Error in ListPurchaseController::export - ' . $e->getMessage());
            
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error exporting purchases: ' . $e->getMessage()
                ]);
            } else {
                // Redirect back with error message
                $_SESSION['error'] = 'Error exporting purchases: ' . $e->getMessage();
                header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'listpurchase');
                exit();
            }
        }
    }
    
    /**
     * Delete a purchase
     */
    public function delete($id) {
        try {
            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                throw new Exception('Permission denied');
            }
            
            $result = $this->purchaseModel->deletePurchase($id);
            
            if ($result) {
                $_SESSION['success'] = 'Purchase deleted successfully';
            } else {
                throw new Exception('Failed to delete purchase');
            }
            
        } catch (Exception $e) {
            error_log('Error in ListPurchaseController::delete - ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }
        
        // Redirect back
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'listpurchase'));
        exit();
    }
    
    /**
     * Check if request is AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Get purchase statistics for dashboard
     */
    public function getStatistics() {
        try {
            return [
                'total_purchases' => $this->purchaseModel->getTotalPurchases(),
                'pending_approvals' => $this->purchaseModel->countPurchasesByStatus('pending'),
                'monthly_spending' => $this->purchaseModel->getMonthlySpending(),
                'pending_payments' => $this->purchaseModel->getPendingPaymentsTotal()
            ];
        } catch (Exception $e) {
            error_log('Error in getStatistics: ' . $e->getMessage());
            return [];
        }
    }

    // Process payment for a purchase
    public function processPayment($purchaseId) {
        try {
            $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
            if (!$amount) throw new Exception('Invalid amount');

            $paymentData = [
                'purchase_id' => $purchaseId,
                'amount' => $amount,
                'payment_method' => filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING),
                'notes' => filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING),
                'created_by' => $_SESSION['user_id']
            ];

            if ($this->purchaseModel->addPayment($paymentData)) {
                $this->notificationModel->create([
                    'user_id' => $_SESSION['user_id'],
                    'title' => 'Payment Processed',
                    'message' => 'Payment of ' . $amount . ' for Purchase #' . $purchaseId,
                    'type' => 'payment',
                    'related_id' => $purchaseId
                ]);
                $_SESSION['success'] = 'Payment processed successfully';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: ' . BASE_URL . 'listpurchase/view/' . $purchaseId);
        exit();
    }

    // Handle purchase returns
    public function processReturn($purchaseId) {
        try {
            $items = $_POST['items'] ?? [];
            if (empty($items)) throw new Exception('No items selected');

            $returnData = [
                'purchase_id' => $purchaseId,
                'items' => $items,
                'reason' => filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING),
                'refund_amount' => filter_input(INPUT_POST, 'refund_amount', FILTER_VALIDATE_FLOAT) ?: 0,
                'processed_by' => $_SESSION['user_id']
            ];

            if ($this->purchaseModel->processReturn($returnData)) {
                foreach ($items as $itemId => $qty) {
                    $this->productModel->updateStock($itemId, $qty, 'return');
                }
                $_SESSION['success'] = 'Return processed successfully';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: ' . BASE_URL . 'listpurchase/view/' . $purchaseId);
        exit();
    }

    private function view($view, $data = []) {
        extract($data);
        $viewFile = APP_PATH . 'views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            require_once APP_PATH . 'views/errors/404.php';
        }
    }
}
