<?php
/**
 * Order Model
 */
class Order extends Model {
    protected $table = 'orders';
    
    /**
     * Create a new order with items
     * 
     * @param array $orderData Order data
     * @param array $items Order items
     * @return int|bool Order ID or false on failure
     */
    /**
     * Create a new order with items
     * 
     * @param array $orderData Order data including:
     *   - user_id: int
     *   - shipping_id: int
     *   - payment_method: string
     *   - status: string (optional, defaults to 'pending')
     *   - total_amount: float
     *   - items: array of items with product_id, quantity, and price
     * @return int|bool Order ID on success, false on failure
     */
    public function createOrder($orderData) {
        $this->db->beginTransaction();
        
        try {
            // Set default status if not provided
            if (!isset($orderData['status'])) {
                $orderData['status'] = 'pending';
            }
            
            // Create order
            $sql = "INSERT INTO orders (user_id, shipping_id, payment_method, status, total_amount, order_number, created_at, updated_at)
                    VALUES (:user_id, :shipping_id, :payment_method, :status, :total_amount, :order_number, NOW(), NOW())";
                    
            if(!$this->db->query($sql)) {
                throw new Exception("Failed to prepare order query: " . $this->db->getError());
            }
            
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            
            // Bind order data
            $this->db->bind(':user_id', $orderData['user_id']);
            $this->db->bind(':shipping_id', $orderData['shipping_id']);
            $this->db->bind(':payment_method', $orderData['payment_method']);
            $this->db->bind(':status', $orderData['status']);
            $this->db->bind(':total_amount', $orderData['total_amount']);
            $this->db->bind(':order_number', $orderNumber);
            
            if(!$this->db->execute()) {
                throw new Exception("Failed to create order: " . $this->db->getError());
            }
            
            $orderId = $this->db->lastInsertId();
            
            // Create order items
            if (!empty($orderData['items']) && is_array($orderData['items'])) {
                foreach($orderData['items'] as $item) {
                    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, created_at, updated_at)
                            VALUES (:order_id, :product_id, :quantity, :price, NOW(), NOW())";
                            
                    if(!$this->db->query($sql)) {
                        throw new Exception("Failed to prepare order item query: " . $this->db->getError());
                    }
                    
                    $this->db->bind(':order_id', $orderId);
                    $this->db->bind(':product_id', $item['product_id']);
                    $this->db->bind(':quantity', $item['quantity']);
                    $this->db->bind(':price', $item['price']);
                    
                    if(!$this->db->execute()) {
                        throw new Exception("Failed to create order item: " . $this->db->getError());
                    }
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $orderId;
            
        } catch(Exception $e) {
            // Rollback transaction on error
            $this->db->cancelTransaction();
            
            // Log error
            error_log('Order creation failed: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            
            return false;
        }
    }
    
    /**
     * Get order with items
     * 
     * @param int $orderId Order ID
     * @return array|bool
     */
    public function getOrderWithItems($orderId) {
        // Get order
        $sql = "SELECT o.*, u.first_name, u.last_name, u.email 
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = :id";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $orderId);
        $order = $this->db->single();
        
        if(!$order) {
            $this->lastError = "Order not found";
            return false;
        }
        
        // Get order items
        $sql = "SELECT oi.*, p.name as product_name, p.sku 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':order_id', $orderId);
        $items = $this->db->resultSet();
        
        return [
            'order' => $order,
            'items' => $items
        ];
    }
    
    /**
     * Get orders by user
     * 
     * @param int $userId User ID
     * @return array
     */
    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    /**
     * Update order status
     * 
     * @param int $orderId Order ID
     * @param string $status New status
     * @return bool
     */
    public function updateOrderStatus($orderId, $status) {
        // Validate status
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if(!in_array($status, $validStatuses)) {
            $this->lastError = "Invalid order status";
            return false;
        }
        
        // Check if order exists
        if(!$this->exists($orderId)) {
            $this->lastError = "Order not found";
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);
        
        return $this->db->execute();
    }
    
    /**
     * Delete an order and its items
     * 
     * @param int $orderId Order ID
     * @return bool
     */
    public function deleteOrder($orderId) {
        $this->db->beginTransaction();
        
        try {
            // First, delete order items
            $this->db->query("DELETE FROM order_items WHERE order_id = :order_id");
            $this->db->bind(':order_id', $orderId);
            
            if(!$this->db->execute()) {
                throw new Exception("Failed to delete order items");
            }
            
            // Then delete the order
            $this->db->query("DELETE FROM {$this->table} WHERE id = :id");
            $this->db->bind(':id', $orderId);
            
            if(!$this->db->execute()) {
                throw new Exception("Failed to delete order");
            }
            
            // Commit transaction
            $this->db->endTransaction();
            return true;
            
        } catch(Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    
    /**
     * Update payment status
     * 
     * @param int $orderId Order ID
     * @param string $status New payment status
     * @return bool
     */
    public function updatePaymentStatus($orderId, $status) {
        // Validate status
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
        if(!in_array($status, $validStatuses)) {
            $this->lastError = "Invalid payment status";
            return false;
        }
        
        // Check if order exists
        if(!$this->exists($orderId)) {
            $this->lastError = "Order not found";
            return false;
        }
        
        $sql = "UPDATE {$this->table} SET payment_status = :status WHERE id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $orderId);
        
        return $this->db->execute();
    }
    
    /**
     * Get recent orders
     * 
     * @param int $limit Number of orders to return
     * @return array
     */
    public function getRecentOrders($limit = 10) {
        $sql = "SELECT o.*, u.first_name, u.last_name 
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT :limit";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Get orders by status
     * 
     * @param string $status Order status
     * @return array
     */
    public function getOrdersByStatus($status) {
        $sql = "SELECT o.*, u.first_name, u.last_name 
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                WHERE o.status = :status
                ORDER BY o.created_at DESC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }
    
    /**
     * Get orders by payment status
     * 
     * @param string $status Payment status
     * @return array
     */
    public function getOrdersByPaymentStatus($status) {
        $sql = "SELECT o.*, u.first_name, u.last_name 
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                WHERE o.payment_status = :status
                ORDER BY o.created_at DESC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }
    
    /**
     * Get sales statistics
     * 
     * @param string $period Period (daily, weekly, monthly, yearly)
     * @return array
     */
    public function getSalesStats($period = 'monthly') {
        $groupBy = '';
        $dateFormat = '';
        
        switch($period) {
            case 'daily':
                $groupBy = 'DATE(created_at)';
                $dateFormat = '%Y-%m-%d';
                break;
            case 'weekly':
                $groupBy = 'WEEK(created_at)';
                $dateFormat = '%Y-%u';
                break;
            case 'monthly':
                $groupBy = 'MONTH(created_at), YEAR(created_at)';
                $dateFormat = '%Y-%m';
                break;
            case 'yearly':
                $groupBy = 'YEAR(created_at)';
                $dateFormat = '%Y';
                break;
            default:
                $groupBy = 'MONTH(created_at), YEAR(created_at)';
                $dateFormat = '%Y-%m';
        }
        
        $sql = "SELECT 
                DATE_FORMAT(created_at, '{$dateFormat}') as period,
                COUNT(*) as order_count,
                SUM(total_amount) as total_sales
                FROM {$this->table}
                WHERE payment_status = 'paid'
                GROUP BY {$groupBy}
                ORDER BY created_at DESC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Paginate orders
     * 
     * @param int $page Current page
     * @param int $perPage Items per page
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $order = 'DESC') {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if(!$this->db->query($countSql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => 0,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => 0
            ];
        }
        
        $totalResult = $this->db->single();
        $total = $totalResult['total'];
        $totalPages = ceil($total / $perPage);
        
        // Get orders with user info
        $sql = "SELECT o.*, u.first_name, u.last_name, u.email 
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.{$orderBy} {$order}
                LIMIT :limit OFFSET :offset";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => $total,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages
            ];
        }
        
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        $data = $this->db->resultSet();
        
        return [
            'data' => $data,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
    
    /**
     * Get sales summary
     * 
     * @return array
     */
    public function getSalesSummary() {
        $summary = [
            'total_sales' => 0,
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'avg_order_value' => 0
        ];
        
        // Get total sales and orders
        $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_order_value
                FROM {$this->table}
                WHERE payment_status = 'paid'";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $summary;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $summary['total_orders'] = (int)$result['total_orders'];
            $summary['total_sales'] = (float)$result['total_sales'];
            $summary['avg_order_value'] = (float)$result['avg_order_value'];
        }
        
        // Get pending orders
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $summary;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $summary['pending_orders'] = (int)$result['count'];
        }
        
        // Get completed orders
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'completed'";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $summary;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $summary['completed_orders'] = (int)$result['count'];
        }
        
        // Get cancelled orders
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'cancelled'";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $summary;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $summary['cancelled_orders'] = (int)$result['count'];
        }
        
        return $summary;
    }
    
    /**
     * Get sales report
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array
     */
    public function getSalesReport($startDate, $endDate) {
        $sql = "SELECT 
                DATE(created_at) as date,
                COUNT(*) as order_count,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_order_value
                FROM {$this->table}
                WHERE created_at BETWEEN :start_date AND :end_date
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':start_date', $startDate . ' 00:00:00');
        $this->db->bind(':end_date', $endDate . ' 23:59:59');
        
        return $this->db->resultSet();
    }
}
