<?php
/**
 * Order Model
 */
class Order extends Model {
    protected $table = 'orders';
    
    /**
     * Get paginated results with order items
     * 
     * @param int $page The current page
     * @param int $perPage Number of items per page
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @param array $filters Additional filters
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $order = 'DESC', $filters = []) {
        // Validate parameters
        $page = max(1, intval($page));
        $perPage = max(1, intval($perPage));
        
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Start building the query
        $sql = "SELECT o.*, 
                       CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                       u.email as customer_email
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE 1=1 ";
        
        // Add filters
        $params = [];
        if (!empty($filters['status'])) {
            $sql .= " AND o.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['payment_status'])) {
            $sql .= " AND o.payment_status = :payment_status";
            $params['payment_status'] = $filters['payment_status'];
        }
        
        if (!empty($filters['search'])) {
            $search = "%{$filters['search']}%";
            $sql .= " AND (
                o.id LIKE :search OR 
                u.first_name LIKE :search OR 
                u.last_name LIKE :search OR 
                u.email LIKE :search OR 
                o.status LIKE :search
            )";
            $params['search'] = $search;
        }
        
        // Add sorting
        $sql .= " ORDER BY o.{$orderBy} {$order}";
        
        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";
        
        // Execute the query
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        
        // Bind pagination parameters
        $this->db->bind(':limit', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        $results = $this->db->resultSet();
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} o 
                    LEFT JOIN users u ON o.user_id = u.id 
                    WHERE 1=1 ";
        
        // Add the same filters to the count query
        if (!empty($filters['status'])) {
            $countSql .= " AND o.status = :status";
        }
        
        if (!empty($filters['payment_status'])) {
            $countSql .= " AND o.payment_status = :payment_status";
        }
        
        if (!empty($filters['search'])) {
            $countSql .= " AND (
                o.id LIKE :search OR 
                u.first_name LIKE :search OR 
                u.last_name LIKE :search OR 
                u.email LIKE :search OR 
                o.status LIKE :search
            )";
        }
        
        // Add filter to exclude deleted orders if needed
        if (!empty($filters['exclude_deleted'])) {
            $sql .= " AND o.id IS NOT NULL"; // This is a placeholder, will be handled by the actual deletion
            $countSql .= " AND o.id IS NOT NULL";
        }
        
        $this->db->query($countSql);
        
        // Bind parameters for count query
        foreach ($params as $key => $value) {
            $this->db->bind(":{$key}", $value);
        }
        
        $totalResult = $this->db->single();
        $totalCount = $totalResult ? $totalResult['total'] : 0;
        $totalPages = ceil($totalCount / $perPage);
        
        // Get order items for each order
        foreach ($results as &$order) {
            $order['items'] = $this->getOrderItems($order['id']);
        }
        
        return [
            'data' => $results,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalCount,
            'total_pages' => $totalPages
        ];
    }
    
    /**
     * Get order items for a specific order
     * 
     * @param int $orderId Order ID
     * @return array
     */
    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name as product_name, p.image as product_image
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id";
        
        $this->db->query($sql);
        $this->db->bind(':order_id', $orderId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get order by ID
     * 
     * @param int $id Order ID
     * @return array|bool Order data or false if not found
     */
    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind(':id', $id);
        
        $result = $this->db->single();
        return $result ?: false;
    }
    
    /**
     * Delete order items by order ID
     * 
     * @param int $orderId Order ID
     * @return bool True on success, false on failure
     */
    public function deleteOrderItems($orderId) {
        try {
            // First, get the order items to update product quantities if needed
            $this->db->query("SELECT product_id, quantity FROM order_items WHERE order_id = :order_id");
            $this->db->bind(':order_id', $orderId);
            $items = $this->db->resultSet();
            
            // Delete the order items
            $this->db->query("DELETE FROM order_items WHERE order_id = :order_id");
            $this->db->bind(':order_id', $orderId);
            
            $result = $this->db->execute();
            
            if ($result && !empty($items)) {
                // Update product quantities (if needed)
                foreach ($items as $item) {
                    $this->db->query("UPDATE products SET stock = stock + :quantity WHERE id = :product_id");
                    $this->db->bind(':quantity', $item['quantity']);
                    $this->db->bind(':product_id', $item['product_id']);
                    $this->db->execute();
                }
            }
            
            // Force a hard delete by running an additional query to ensure deletion
            $this->db->query("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';");
            $this->db->query("OPTIMIZE TABLE order_items;");
            
            return $result;
        } catch (Exception $e) {
            error_log('Error deleting order items for order #' . $orderId . ': ' . $e->getMessage());
            return false;
        }
    }
    
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
    
    /**
     * Get orders with due payments
     * 
     * @return array
     */
    public function getOrdersWithDuePayment() {
        try {
            $sql = "SELECT o.*, u.first_name, u.last_name, u.email,
                           COALESCE(SUM(op.amount), 0) as paid_amount,
                           (o.total_amount - COALESCE(SUM(op.amount), 0)) as due_amount
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN order_payments op ON o.id = op.order_id AND op.status = 'completed'
                    WHERE o.payment_status != 'paid' 
                    GROUP BY o.id
                    HAVING due_amount > 0
                    ORDER BY o.due_date ASC, o.created_at DESC";
            
            $this->db->query($sql);
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Error in getOrdersWithDuePayment: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get order with payment details
     * 
     * @param int $orderId Order ID
     * @return object|bool Order object with payments or false if not found
     */
    public function getOrderWithPayments($orderId) {
        try {
            // Get order details
            $sql = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
                           COALESCE(SUM(op.amount), 0) as paid_amount,
                           (o.total_amount - COALESCE(SUM(op.amount), 0)) as due_amount
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN order_payments op ON o.id = op.order_id AND op.status = 'completed'
                    WHERE o.id = :order_id
                    GROUP BY o.id";
            
            $this->db->query($sql);
            $this->db->bind(':order_id', $orderId);
            $order = $this->db->single();
            
            if (!$order) {
                return false;
            }
            
            // Get payment history
            $sql = "SELECT * FROM order_payments 
                    WHERE order_id = :order_id 
                    ORDER BY payment_date DESC";
            
            $this->db->query($sql);
            $this->db->bind(':order_id', $orderId);
            $payments = $this->db->resultSet();
            
            $order->payments = $payments;
            
            return $order;
            
        } catch (Exception $e) {
            error_log('Error in getOrderWithPayments: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Record a payment for an order
     * 
     * @param array $paymentData Payment data including:
     *   - order_id: int
     *   - amount: float
     *   - payment_date: string (Y-m-d H:i:s)
     *   - payment_method: string
     *   - transaction_id: string (optional)
     *   - notes: string (optional)
     *   - status: string (default: 'completed')
     * @return int|bool Payment ID on success, false on failure
     */
    public function recordPayment($paymentData) {
        $this->db->beginTransaction();
        
        try {
            // Insert payment record
            $sql = "INSERT INTO order_payments (
                        order_id, amount, payment_date, payment_method, 
                        transaction_id, notes, status, created_at, updated_at
                    ) VALUES (
                        :order_id, :amount, :payment_date, :payment_method, 
                        :transaction_id, :notes, :status, NOW(), NOW()
                    )";
            
            $this->db->query($sql);
            $this->db->bind(':order_id', $paymentData['order_id']);
            $this->db->bind(':amount', $paymentData['amount']);
            $this->db->bind(':payment_date', $paymentData['payment_date']);
            $this->db->bind(':payment_method', $paymentData['payment_method']);
            $this->db->bind(':transaction_id', $paymentData['transaction_id'] ?? null);
            $this->db->bind(':notes', $paymentData['notes'] ?? null);
            $this->db->bind(':status', $paymentData['status'] ?? 'completed');
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to record payment');
            }
            
            $paymentId = $this->db->lastInsertId();
            
            // Update order payment status
            $orderId = $paymentData['order_id'];
            $order = $this->getOrderWithPayments($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            $newPaymentStatus = 'partial';
            if ($order->due_amount <= 0.01) { // Account for floating point precision
                $newPaymentStatus = 'paid';
            }
            
            $this->updatePaymentStatus($orderId, $newPaymentStatus);
            
            // Update due date if this is the first payment
            if (empty($order->payments)) {
                $dueDate = date('Y-m-d', strtotime('+30 days'));
                $this->db->query("UPDATE {$this->table} SET due_date = :due_date WHERE id = :id");
                $this->db->bind(':due_date', $dueDate);
                $this->db->bind(':id', $orderId);
                $this->db->execute();
            }
            
            $this->db->endTransaction();
            return $paymentId;
            
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            error_log('Error in recordPayment: ' . $e->getMessage());
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    
    /**
     * Update payment status and related data
     * 
     * @param int|array $data Either order ID or payment data array
     * @param string $status (Optional) New status if first parameter is order ID
     * @return bool True on success, false on failure
     */
    public function updatePaymentStatus($data, $status = null) {
        // If first parameter is order ID and second is status (legacy usage)
        if (is_numeric($data) && $status !== null) {
            $orderId = $data;
            
            // Validate status
            $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
            if (!in_array($status, $validStatuses)) {
                $this->lastError = "Invalid payment status";
                return false;
            }
            
            // Check if order exists
            if (!$this->exists($orderId)) {
                $this->lastError = "Order not found";
                return false;
            }
            
            $sql = "UPDATE {$this->table} SET payment_status = :status WHERE id = :id";
            
            if (!$this->db->query($sql)) {
                $this->lastError = $this->db->getError();
                return false;
            }
            
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $orderId);
            
            return $this->db->execute();
        } 
        // If first parameter is an array (new usage with full payment data)
        elseif (is_array($data)) {
            return $this->recordPayment($data) !== false;
        }
        
        $this->lastError = "Invalid parameters for updatePaymentStatus";
        return false;
    }
    
    /**
     * Get payment due report
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array
     */
    public function getPaymentDueReport($startDate = null, $endDate = null) {
        try {
            $sql = "SELECT 
                        o.id, o.order_number, o.total_amount, o.payment_status, o.due_date,
                        u.first_name, u.last_name, u.email,
                        COALESCE(SUM(op.amount), 0) as paid_amount,
                        (o.total_amount - COALESCE(SUM(op.amount), 0)) as due_amount,
                        DATEDIFF(o.due_date, CURDATE()) as days_until_due
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN order_payments op ON o.id = op.order_id AND op.status = 'completed'
                    WHERE o.payment_status != 'paid' 
                    AND o.due_date IS NOT NULL";
            
            $params = [];
            
            if ($startDate) {
                $sql .= " AND o.due_date >= :start_date";
                $params['start_date'] = $startDate;
            }
            
            if ($endDate) {
                $sql .= " AND o.due_date <= :end_date";
                $params['end_date'] = $endDate;
            }
            
            $sql .= " GROUP BY o.id
                      HAVING due_amount > 0
                      ORDER BY o.due_date ASC, o.created_at DESC";
            
            $this->db->query($sql);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $this->db->bind(":{$key}", $value);
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Error in getPaymentDueReport: ' . $e->getMessage());
            return [];
        }
    }
}
