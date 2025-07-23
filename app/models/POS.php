<?php
/**
 * POS Model
 */
class POS extends Model {
    protected $table = 'pos_sessions';
    
    /**
     * Open a new POS session
     * 
     * @param int $staffId Staff ID
     * @param float $openingBalance Opening balance
     * @param string $notes Notes
     * @return int|bool
     */
    public function openSession($staffId, $openingBalance, $notes = '') {
        // Check if staff already has an open session
        $this->db->query("SELECT * FROM {$this->table} WHERE staff_id = :staff_id AND status = :status");
        $this->db->bind(':staff_id', $staffId);
        $this->db->bind(':status', 'open');
        $existing = $this->db->single();
        
        if($existing) {
            return false; // Session already open
        }
        
        $data = [
            'staff_id' => $staffId,
            'opening_balance' => $openingBalance,
            'status' => 'open',
            'notes' => $notes,
            'opened_at' => getCurrentDateTime()
        ];
        
        return $this->create($data);
    }
    
    /**
     * Close a POS session
     * 
     * @param int $sessionId Session ID
     * @param float $closingBalance Closing balance
     * @param string $notes Notes
     * @return bool
     */
    public function closeSession($sessionId, $closingBalance, $notes = '') {
        $this->db->query("UPDATE {$this->table} 
                         SET status = :status, 
                             closing_balance = :closing_balance, 
                             closed_at = :closed_at,
                             notes = CONCAT(notes, '\n', :notes)
                         WHERE id = :id AND status = :open_status");
        $this->db->bind(':status', 'closed');
        $this->db->bind(':closing_balance', $closingBalance);
        $this->db->bind(':closed_at', getCurrentDateTime());
        $this->db->bind(':notes', $notes);
        $this->db->bind(':id', $sessionId);
        $this->db->bind(':open_status', 'open');
        return $this->db->execute();
    }
    
    /**
     * Get active session for staff
     * 
     * @param int $staffId Staff ID
     * @return object|bool
     */
    public function getActiveSession($staffId) {
        $this->db->query("SELECT * FROM {$this->table} WHERE staff_id = :staff_id AND status = :status");
        $this->db->bind(':staff_id', $staffId);
        $this->db->bind(':status', 'open');
        return $this->db->single();
    }
    
    /**
     * Check if staff has active session
     * 
     * @param int $staffId Staff ID
     * @return bool
     */
    public function hasActiveSession($staffId) {
        $session = $this->getActiveSession($staffId);
        return $session ? true : false;
    }
    
    /**
     * Create a POS order
     * 
     * @param array $orderData Order data
     * @param array $items Order items
     * @return int|bool Order ID or false on failure
     */
    public function createPOSOrder($orderData, $items) {
        $this->db->beginTransaction();
        
        try {
            // Create order
            $this->db->query("INSERT INTO orders (user_id, total_amount, status, payment_status, payment_method, notes) 
                             VALUES (:user_id, :total_amount, :status, :payment_status, :payment_method, :notes)");
            $this->db->bind(':user_id', $orderData['user_id']);
            $this->db->bind(':total_amount', $orderData['total_amount']);
            $this->db->bind(':status', $orderData['status'] ?? 'processing');
            $this->db->bind(':payment_status', $orderData['payment_status'] ?? 'paid');
            $this->db->bind(':payment_method', $orderData['payment_method']);
            $this->db->bind(':notes', $orderData['notes'] ?? 'POS Order');
            
            if(!$this->db->execute()) {
                throw new Exception("Failed to create order");
            }
            
            $orderId = $this->db->lastInsertId();
            
            // Create order items
            foreach($items as $item) {
                $this->db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                 VALUES (:order_id, :product_id, :quantity, :price)");
                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':price', $item['price']);
                
                if(!$this->db->execute()) {
                    throw new Exception("Failed to create order item");
                }
                
                // Update product stock
                $this->db->query("UPDATE products SET stock_quantity = stock_quantity - :quantity 
                                 WHERE id = :product_id");
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':product_id', $item['product_id']);
                
                if(!$this->db->execute()) {
                    throw new Exception("Failed to update product stock");
                }
            }
            
            // Create transaction record
            $this->db->query("INSERT INTO transactions (order_id, payment_method, amount, status) 
                             VALUES (:order_id, :payment_method, :amount, :status)");
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':payment_method', $orderData['payment_method']);
            $this->db->bind(':amount', $orderData['total_amount']);
            $this->db->bind(':status', 'completed');
            
            if(!$this->db->execute()) {
                throw new Exception("Failed to create transaction");
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $orderId;
        } catch(Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            
            // Log error
            error_log($e->getMessage());
            
            return false;
        }
    }
    
    /**
     * Get daily sales report
     * 
     * @param int $sessionId Session ID
     * @return array
     */
    public function getDailySalesReport($sessionId = null) {
        $whereClause = '';
        if($sessionId) {
            $session = $this->getById($sessionId);
            if($session) {
                $whereClause = " AND o.created_at BETWEEN '{$session['opened_at']}' AND ";
                $whereClause .= $session['closed_at'] ? "'{$session['closed_at']}'" : "NOW()";
            }
        } else {
            $whereClause = " AND DATE(o.created_at) = CURDATE()";
        }
        
        $this->db->query("SELECT 
                         COUNT(o.id) as order_count,
                         SUM(o.total_amount) as total_sales,
                         SUM(CASE WHEN o.payment_method = 'cash' THEN o.total_amount ELSE 0 END) as cash_sales,
                         SUM(CASE WHEN o.payment_method = 'card' THEN o.total_amount ELSE 0 END) as card_sales,
                         SUM(CASE WHEN o.payment_method NOT IN ('cash', 'card') THEN o.total_amount ELSE 0 END) as other_sales
                         FROM orders o
                         WHERE o.payment_status = 'paid'" . $whereClause);
        
        return $this->db->single();
    }
    
    /**
     * Get session history for staff
     * 
     * @param int $staffId Staff ID
     * @param int $limit Number of sessions to return
     * @return array
     */
    public function getSessionHistory($staffId, $limit = 10) {
        $this->db->query("SELECT s.*, u.first_name, u.last_name 
                         FROM {$this->table} s
                         JOIN users u ON s.staff_id = u.id
                         WHERE s.staff_id = :staff_id
                         ORDER BY s.opened_at DESC
                         LIMIT :limit");
        $this->db->bind(':staff_id', $staffId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
