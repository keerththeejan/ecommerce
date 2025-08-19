<?php
/**
 * Invoice Model
 * Handles database operations for invoices
 */
class Invoice {
    private $db;
    private $table = 'invoices';
    private $debug = true; // Set to false in production
    private $lastError = null;
    
    public function __construct() {
        try {
            // Check if database configuration is loaded
            if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_PASS') || !defined('DB_NAME')) {
                throw new Exception('Database configuration is missing');
            }
            
            // The Database class should be already included by the bootstrap process
            if (!class_exists('Database')) {
                throw new Exception('Database class not found');
            }
            
            $this->db = new Database();
            
            if ($this->debug) {
                error_log('Database connection initialized in Invoice model');
            }

            // Ensure invoices table exists
            $this->ensureInvoicesTable();
        } catch (Exception $e) {
            // Log the error
            error_log('Error initializing database in Invoice model: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            
            // For debugging, show the error
            if ($this->debug) {
                die('Database Error: ' . $e->getMessage());
            }
            
            // In production, you might want to show a generic error
            throw new Exception('Failed to initialize database connection');
        }
    }
    
    /**
     * Get last error (for debugging/admin feedback)
     */
    public function getLastError() {
        return $this->lastError;
    }

    /**
     * Ensure the invoices table exists; create it if missing.
     */
    private function ensureInvoicesTable() {
        try {
            if (method_exists($this->db, 'tableExists') && !$this->db->tableExists($this->table)) {
                $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `invoice_date` DATETIME NOT NULL,
  `due_date` DATETIME NULL,
  `status` ENUM('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_invoice_number` (`invoice_number`),
  KEY `idx_invoices_order_id` (`order_id`),
  CONSTRAINT `fk_invoices_order_id`
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                // Execute DDL
                $this->db->query($sql);
                if (!$this->db->execute()) {
                    // Fallback: try without foreign key (e.g., if column types don't match)
                    $this->lastError = method_exists($this->db, 'getError') ? $this->db->getError() : 'Failed to create invoices table';
                    error_log('Failed to auto-create invoices table with FK: ' . $this->lastError . ' - Retrying without FK');
                    $fallbackSql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) NOT NULL,
  `order_id` INT UNSIGNED NOT NULL,
  `invoice_date` DATETIME NOT NULL,
  `due_date` DATETIME NULL,
  `status` ENUM('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_invoice_number` (`invoice_number`),
  KEY `idx_invoices_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                    $this->db->query($fallbackSql);
                    if (!$this->db->execute()) {
                        $this->lastError = method_exists($this->db, 'getError') ? $this->db->getError() : 'Failed to create invoices table (no FK)';
                        error_log('Failed to auto-create invoices table (no FK): ' . $this->lastError);
                    } else if ($this->debug) {
                        error_log('Invoices table created automatically (without FK)');
                    }
                } else if ($this->debug) {
                    error_log('Invoices table created automatically');
                }
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error ensuring invoices table: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all invoices for a user
     * 
     * @param int $userId User ID
     * @return array
     */
    public function getInvoicesByUser($userId) {
        try {
            $sql = 'SELECT i.*, o.order_number, o.total_amount, o.status as order_status 
                   FROM ' . $this->table . ' i 
                   JOIN orders o ON i.order_id = o.id 
                   WHERE o.user_id = :user_id 
                   ORDER BY i.invoice_date DESC';
            
            if ($this->debug) {
                error_log('Executing query: ' . $sql);
                error_log('With user_id: ' . $userId);
            }
            
            $result = $this->db->query($sql);
            if ($result === false) {
                throw new Exception('Failed to prepare query');
            }
            
            $this->db->bind(':user_id', $userId);
            
            $results = $this->db->resultSet();
            
            if ($this->debug) {
                error_log('Query results count: ' . count($results));
            }
            
            return $results;
        } catch (PDOException $e) {
            // Log the PDO error
            $errorInfo = method_exists($this->db, 'errorInfo') ? $this->db->errorInfo() : ['No error info available'];
            error_log('PDO Error in getInvoicesByUser: ' . $e->getMessage());
            error_log('PDO Error Info: ' . print_r($errorInfo, true));
            
            // Create a more descriptive error message
            $errorMsg = 'Database error occurred';
            if (!empty($errorInfo[2])) {
                $errorMsg .= ': ' . $errorInfo[2];
            }
            throw new Exception($errorMsg);
        } catch (Exception $e) {
            // Log other errors
            error_log('Error in getInvoicesByUser: ' . $e->getMessage());
            throw $e; // Re-throw to be handled by the controller
        }
    }
    
    /**
     * Get recent invoices
     * 
     * @param int $limit Number of recent invoices to return
     * @return array
     */
    public function getRecentInvoices($limit = 5) {
        try {
            // Check if tables exist
            $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            $invoicesTableExists = $this->db->single();
            
            $this->db->query("SHOW TABLES LIKE 'orders'");
            $ordersTableExists = $this->db->single();
            
            $this->db->query("SHOW TABLES LIKE 'users'");
            $usersTableExists = $this->db->single();
            
            if (!$invoicesTableExists || !$ordersTableExists || !$usersTableExists) {
                return [];
            }
            
            $sql = 'SELECT i.*, u.name as customer_name, u.email as customer_email
                   FROM ' . $this->table . ' i 
                   LEFT JOIN orders o ON i.order_id = o.id
                   LEFT JOIN users u ON o.user_id = u.id
                   ORDER BY i.invoice_date DESC
                   LIMIT :limit';
                   
            $this->db->query($sql);
            $this->db->bind(':limit', (int)$limit);
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Error in getRecentInvoices: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get total sales for the current month
     * 
     * @return float
     */
    public function getCurrentMonthSales() {
        try {
            $sql = 'SELECT COALESCE(SUM(total_amount), 0) as total 
                   FROM ' . $this->table . ' 
                   WHERE MONTH(invoice_date) = MONTH(CURRENT_DATE())
                   AND YEAR(invoice_date) = YEAR(CURRENT_DATE())';
            
            $this->db->query($sql);
            $result = $this->db->single();
            
            return $result ? (float)$result->total : 0;
            
        } catch (Exception $e) {
            error_log('Error in getCurrentMonthSales: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get count of outstanding invoices
     * 
     * @return int
     */
    public function countOutstandingInvoices() {
        try {
            $sql = 'SELECT COUNT(*) as count 
                   FROM ' . $this->table . ' 
                   WHERE status != "paid"';
            
            $this->db->query($sql);
            $result = $this->db->single();
            
            return $result ? (int)$result->count : 0;
            
        } catch (Exception $e) {
            error_log('Error in countOutstandingInvoices: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get invoice with order details
     * 
     * @param int $invoiceId Invoice ID
     * @return array|bool
     */
    public function getInvoiceWithOrder($invoiceId) {
        try {
            // Get invoice details
            $sql = 'SELECT i.*, o.*, u.name as customer_name, u.email as customer_email,
                           u.phone as customer_phone, u.address as customer_address,
                           u.city as customer_city, u.state as customer_state,
                           u.country as customer_country, u.zip_code as customer_zip
                    FROM ' . $this->table . ' i 
                    JOIN orders o ON i.order_id = o.id
                    JOIN users u ON o.user_id = u.id 
                    WHERE i.id = :id';
                    
            $this->db->query($sql);
            $this->db->bind(':id', $invoiceId);
            
            $invoice = $this->db->single();
            
            if(!$invoice) {
                return false;
            }
            
            // Get order items
            $sql = 'SELECT oi.*, p.name as product_name, p.image as product_image
                   FROM order_items oi
                   JOIN products p ON oi.product_id = p.id
                   WHERE oi.order_id = :order_id';
                   
            $this->db->query($sql);
            $this->db->bind(':order_id', $invoice['order_id']);
            
            $invoice['items'] = $this->db->resultSet();
            
            return $invoice;
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            error_log('Error in getInvoiceWithOrder: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate PDF for an invoice
     * 
     * @param int $invoiceId Invoice ID
     * @return void
     */
    public function generatePdf($invoiceId) {
        // This is a placeholder for PDF generation
        // You would typically use a library like TCPDF, mPDF, or Dompdf here
        
        // For now, we'll just redirect to the print view
        redirect('invoice/print/' . $invoiceId);
    }
    
    /**
     * Create a new invoice
     * 
     * @param int $orderId Order ID
     * @return bool
     */
    public function createInvoice($orderId) {
        try {
            // Check if invoice already exists
            $sql = 'SELECT id FROM ' . $this->table . ' WHERE order_id = :order_id LIMIT 1';
            $this->db->query($sql);
            $this->db->bind(':order_id', $orderId);
            $existing = $this->db->single();
            if (is_object($existing)) { $existing = (array)$existing; }
            if($existing && isset($existing['id'])) {
                return false; // Invoice already exists
            }
            
            // Generate invoice number (you might want a better numbering system)
            $invoiceNumber = 'INV-' . strtoupper(uniqid());
            
            // Create invoice
            $sql = 'INSERT INTO ' . $this->table . ' 
                   (invoice_number, order_id, invoice_date, due_date, status)
                   VALUES (:invoice_number, :order_id, :invoice_date, :due_date, :status)';
            
            $this->db->query($sql);
            
            $this->db->bind(':invoice_number', $invoiceNumber);
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':invoice_date', date('Y-m-d H:i:s'));
            $this->db->bind(':due_date', date('Y-m-d H:i:s', strtotime('+30 days')));
            $this->db->bind(':status', 'unpaid');
            
            $ok = $this->db->execute();
            if (!$ok) {
                $this->lastError = method_exists($this->db, 'getError') ? $this->db->getError() : 'DB execute failed';
            }
            return $ok;
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            error_log('Error in createInvoice: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Get invoice id by order id
     * @param int $orderId
     * @return int|null
     */
    public function getInvoiceIdByOrderId($orderId) {
        try {
            $sql = 'SELECT id FROM ' . $this->table . ' WHERE order_id = :order_id LIMIT 1';
            $this->db->query($sql);
            $this->db->bind(':order_id', $orderId);
            $row = $this->db->single();
            if (is_object($row)) { $row = (array)$row; }
            return ($row && isset($row['id'])) ? (int)$row['id'] : null;
        } catch (Exception $e) {
            error_log('Error in getInvoiceIdByOrderId: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    /**
     * Create an invoice if not exists and return its ID.
     * Returns existing invoice ID if already present.
     *
     * @param int $orderId
     * @return int|null Invoice ID or null on failure
     */
    public function createOrGetInvoiceId($orderId) {
        try {
            // Already has invoice?
            $existingId = $this->getInvoiceIdByOrderId($orderId);
            if ($existingId) {
                return $existingId;
            }

            // Create
            $invoiceNumber = 'INV-' . strtoupper(uniqid());
            $sql = 'INSERT INTO ' . $this->table . ' 
                   (invoice_number, order_id, invoice_date, due_date, status)
                   VALUES (:invoice_number, :order_id, :invoice_date, :due_date, :status)';
            $this->db->query($sql);
            $this->db->bind(':invoice_number', $invoiceNumber);
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':invoice_date', date('Y-m-d H:i:s'));
            $this->db->bind(':due_date', date('Y-m-d H:i:s', strtotime('+30 days')));
            $this->db->bind(':status', 'unpaid');
            if (!$this->db->execute()) {
                $this->lastError = method_exists($this->db, 'getError') ? $this->db->getError() : 'DB execute failed';
                if (method_exists($this->db, 'getError')) {
                    error_log('Invoice insert failed: ' . $this->db->getError());
                }
                return null;
            }
            $newId = method_exists($this->db, 'lastInsertId') ? (int)$this->db->lastInsertId() : null;
            return $newId ?: $this->getInvoiceIdByOrderId($orderId);
        } catch (Exception $e) {
            error_log('Error in createOrGetInvoiceId: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            return null;
        }
    }
}
