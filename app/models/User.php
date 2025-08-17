<?php
/**
 * User Model
 */
class User extends Model {
    protected $table = 'users';
    
    /**
     * Register a new user
     * 
     * @param array $data User data
     * @return int|bool
     */
    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $this->create($data);
    }
    
    /**
     * Login a user
     * 
     * @param string $username Username or email
     * @param string $password Password
     * @return array|bool
     */
    public function login($username, $password) {
        // Check if username is email or username
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Find user
        $sql = "SELECT * FROM {$this->table} WHERE {$field} = :username";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':username', $username);
        $user = $this->db->single();
        
        // Check if user exists
        if(!$user) {
            $this->lastError = "User not found";
            return false;
        }
        
        // Verify password
        if(password_verify($password, $user['password'])) {
            return $user;
        } else {
            $this->lastError = "Invalid password";
            return false;
        }
    }
    
    /**
     * Find user by email
     * 
     * @param string $email Email
     * @return array|bool
     */
    public function findUserByEmail($email) {
        return $this->getSingleBy('email', $email);
    }
    
    /**
     * Find user by username
     * 
     * @param string $username Username
     * @return array|bool
     */
    public function findUserByUsername($username) {
        return $this->getSingleBy('username', $username);
    }
    
    /**
     * Get all customers
     * 
     * @return array
     */
    public function getCustomers() {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':role', 'customer');
        return $this->db->resultSet();
    }
    
    /**
     * Get all staff
     * 
     * @return array
     */
    public function getStaff() {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':role', 'staff');
        return $this->db->resultSet();
    }
    
    /**
     * Get all admins
     * 
     * @return array
     */
    public function getAdmins() {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':role', 'admin');
        return $this->db->resultSet();
    }
    
    /**
     * Update user password
     * 
     * @param int $userId User ID
     * @param string $password New password
     * @return bool
     */
    public function updatePassword($userId, $password) {
        $data = [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        
        return $this->update($userId, $data);
    }
    
    /**
     * Paginate users
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
        
        // Get users
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order} LIMIT :limit OFFSET :offset";
        
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
     * Get user by role
     * 
     * @param string $role User role
     * @return array
     */
    public function getUsersByRole($role) {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role ORDER BY id DESC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':role', $role);
        return $this->db->resultSet();
    }
    
    /**
     * Get customer statistics
     * 
     * @return array
     */
    public function getCustomerStatistics() {
        $stats = [
            'total_customers' => 0,
            'new_customers' => 0,
            'active_customers' => 0
        ];
        
        // Get total customers
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE role = 'customer'";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $stats;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $stats['total_customers'] = (int)$result['count'];
        }
        
        // Get new customers (last 30 days)
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE role = 'customer' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $stats;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $stats['new_customers'] = (int)$result['count'];
        }
        
        // Get active customers (with at least one order)
        $sql = "SELECT COUNT(DISTINCT u.id) as count 
                FROM {$this->table} u
                JOIN orders o ON u.id = o.user_id
                WHERE u.role = 'customer'";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return $stats;
        }
        
        $result = $this->db->single();
        
        if($result) {
            $stats['active_customers'] = (int)$result['count'];
        }
        
        return $stats;
    }
    
    /**
     * Get customer report
     * 
     * @param string $sortBy Sort by (orders, spent)
     * @return array
     */
    public function getCustomerReport($sortBy = 'orders') {
        $orderBy = "order_count DESC";
        
        switch($sortBy) {
            case 'spent':
                $orderBy = "total_spent DESC";
                break;
            case 'orders':
            default:
                $orderBy = "order_count DESC";
                break;
        }
        
        $sql = "SELECT u.*, 
                COUNT(o.id) as order_count,
                SUM(o.total_amount) as total_spent,
                MAX(o.created_at) as last_order_date
                FROM {$this->table} u
                LEFT JOIN orders o ON u.id = o.user_id
                WHERE u.role = 'customer'
                GROUP BY u.id
                ORDER BY {$orderBy}";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get active users (users active in the last 15 minutes)
     * 
     * @return array Array of active users
     */
    public function getActiveUsers() {
        try {
            // First, check if the last_activity column exists
            $checkColumn = "SHOW COLUMNS FROM {$this->table} LIKE 'last_activity'";
            
            if(!$this->db->query($checkColumn)) {
                throw new Exception('Could not check for required columns');
            }
            
            $columnExists = (bool)$this->db->single();
            
            if (!$columnExists) {
                throw new Exception('Activity tracking columns not found');
            }
            
            // Now query for active users
            $sql = "SELECT * FROM {$this->table} 
                    WHERE last_activity >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
                    ORDER BY last_activity DESC";
                    
            if(!$this->db->query($sql)) {
                throw new Exception($this->db->getError());
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }
    
    /**
     * Update user's last activity timestamp and IP address
     * 
     * @param int $userId User ID
     * @param string $ipAddress User's IP address
     * @param string $userAgent User's browser user agent
     * @return bool
     */
    public function updateActivity($userId, $ipAddress, $userAgent = '') {
        $data = [
            'last_activity' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ];
        
        return $this->update($userId, $data);
    }
    
    /**
     * Get the last error message
     * 
     * @return string Last error message or empty string if no error
     */
    public function getError() {
        return $this->lastError ?? '';
    }
    
    /**
     * Get the last error that occurred
     * 
     * @return string Last error message or empty string if no error
     */
    public function getLastError() {
        return $this->lastError ?? '';
    }
}
