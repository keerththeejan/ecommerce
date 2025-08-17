<?php
/**
 * Purchase Model
 * Handles purchase orders and inventory management
 */
class Purchase {
    private $db;
    private $table = 'purchases';
    private $itemsTable = 'purchase_items';
    
    public function __construct($db = null) {
        if ($db instanceof Database) {
            $this->db = $db;
        } else {
            $this->db = new Database;
        }
    }
    
    /**
     * Set the database connection
     * @param Database $db Database connection
     */
    public function setDb($db) {
        $this->db = $db;
    }
    
    /**
     * Get all purchases
     */
    public function getAllPurchases($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $this->db->query("SELECT p.*, s.name as supplier_name, 
                         COUNT(pi.id) as total_items, 
                         SUM(pi.quantity * pi.unit_price) as total_amount
                         FROM {$this->table} p
                         LEFT JOIN suppliers s ON p.supplier_id = s.id
                         LEFT JOIN {$this->itemsTable} pi ON p.id = pi.purchase_id
                         GROUP BY p.id
                         ORDER BY p.purchase_date DESC
                         LIMIT :limit OFFSET :offset");
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get recent purchases
     */
    public function getRecentPurchases($limit = 5) {
        try {
            // Check if tables exist
            $this->db->query("SHOW TABLES LIKE '{$this->table}'");
            $purchasesTableExists = $this->db->single();
            
            $this->db->query("SHOW TABLES LIKE 'suppliers'");
            $suppliersTableExists = $this->db->single();
            
            if (!$purchasesTableExists || !$suppliersTableExists) {
                return [];
            }
            
            $this->db->query("SELECT p.*, s.name as supplier_name, 
                             COUNT(pi.id) as total_items, 
                             SUM(pi.quantity * pi.unit_price) as total_amount
                             FROM {$this->table} p
                             LEFT JOIN suppliers s ON p.supplier_id = s.id
                             LEFT JOIN {$this->itemsTable} pi ON p.id = pi.purchase_id
                             GROUP BY p.id
                             ORDER BY p.purchase_date DESC
                             LIMIT :limit");
            
            $this->db->bind(':limit', $limit);
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Error in getRecentPurchases: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get purchase by ID
     */
    public function getPurchaseById($id) {
        $this->db->query("SELECT p.*, s.name as supplier_name, s.email as supplier_email, 
                         s.phone as supplier_phone, s.address as supplier_address
                         FROM {$this->table} p
                         LEFT JOIN suppliers s ON p.supplier_id = s.id
                         WHERE p.id = :id");
        $this->db->bind(':id', $id);
        
        $purchase = $this->db->single();
        
        if ($purchase) {
            // Get purchase items
            $this->db->query("SELECT pi.*, p.name as product_name, p.sku, p.image
                             FROM {$this->itemsTable} pi
                             LEFT JOIN products p ON pi.product_id = p.id
                             WHERE pi.purchase_id = :purchase_id");
            $this->db->bind(':purchase_id', $id);
            $purchase->items = $this->db->resultSet();
        }
        
        return $purchase;
    }
    
    /**
     * Create a new purchase
     */
    public function create($data) {
        try {
            $this->db->beginTransaction();
            
            // Insert purchase
            $this->db->query("INSERT INTO {$this->table} 
                             (supplier_id, reference_no, purchase_date, status, notes, document_path, created_at)
                             VALUES (:supplier_id, :reference_no, :purchase_date, :status, :notes, :document_path, NOW())");
            
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->bind(':reference_no', $data['reference_no']);
            $this->db->bind(':purchase_date', $data['purchase_date']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':document_path', $data['document_path'] ?? null);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to create purchase');
            }
            
            $purchaseId = $this->db->lastInsertId();
            $totalAmount = 0;
            
            // Insert purchase items
            foreach ($data['items'] as $item) {
                $this->db->query("INSERT INTO {$this->itemsTable} 
                                 (purchase_id, product_id, quantity, unit_price, total_price)
                                 VALUES (:purchase_id, :product_id, :quantity, :unit_price, :total_price)");
                
                $total = $item['quantity'] * $item['unit_price'];
                
                $this->db->bind(':purchase_id', $purchaseId);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':unit_price', $item['unit_price']);
                $this->db->bind(':total_price', $total);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to add purchase items');
                }
                
                $totalAmount += $total;
                
                // Update product stock if purchase is received
                if ($data['status'] === 'received') {
                    $this->updateProductStock($item['product_id'], $item['quantity']);
                }
            }
            
            // Update purchase total amount
            $this->db->query("UPDATE {$this->table} SET total_amount = :total_amount WHERE id = :id");
            $this->db->bind(':total_amount', $totalAmount);
            $this->db->bind(':id', $purchaseId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to update purchase total');
            }
            
            $this->db->commit();
            return $purchaseId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Update product stock
     */
    private function updateProductStock($productId, $quantity) {
        $this->db->query("UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE id = :id");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $productId);
        return $this->db->execute();
    }
    
    /**
     * Update purchase status
     */
    public function updateStatus($id, $status) {
        try {
            $this->db->beginTransaction();
            
            // Get current status
            $purchase = $this->getPurchaseById($id);
            
            // If changing to 'received', update product stock
            if ($status === 'received' && $purchase->status !== 'received') {
                foreach ($purchase->items as $item) {
                    $this->updateProductStock($item->product_id, $item->quantity);
                }
            }
            
            // Update status
            $this->db->query("UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE id = :id");
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            
            $result = $this->db->execute();
            $this->db->commit();
            
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a purchase
     */
    public function delete($id) {
        try {
            $this->db->beginTransaction();
            
            // Get purchase items to update stock if received
            $purchase = $this->getPurchaseById($id);
            
            if ($purchase->status === 'received') {
                foreach ($purchase->items as $item) {
                    $this->updateProductStock($item->product_id, -$item->quantity);
                }
            }
            
            // Delete purchase items
            $this->db->query("DELETE FROM {$this->itemsTable} WHERE purchase_id = :purchase_id");
            $this->db->bind(':purchase_id', $id);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to delete purchase items');
            }
            
            // Delete purchase
            $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
            $this->db->bind(':id', $id);
            
            $result = $this->db->execute();
            $this->db->commit();
            
            return $result;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
}
