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
    /**
     * Get all customers
     * @return array List of customers
     */
    public function getAllCustomers() {
        $this->db->query("SELECT id, CONCAT(first_name, ' ', last_name) as name, email FROM {$this->table} WHERE role = 'customer' ORDER BY first_name, last_name");
        return $this->db->resultSet();
    }

    /**
     * Check if a column exists on the users table
     *
     * @param string $column
     * @return bool
     */
    protected function columnExists($column) {
        // Use database helper which handles quoting safely
        return $this->db->columnExists($this->table, $column);
    }

    /**
     * Search users by keyword across common fields.
     * Supports optional role filter and result limiting.
     *
     * @param string $keyword
     * @param array|string $roles Single role or array of roles to include (e.g., 'customer')
     * @param int $limit Max results to return
     * @return array
     */
    public function searchUsers($keyword, $roles = [], $limit = 10) {
        $keyword = trim((string)$keyword);
        if ($keyword === '') {
            return [];
        }

        $includePhone = $this->columnExists('phone');
        $fields = "id, username, email, first_name, last_name" . ($includePhone ? ", phone" : "");

        $sql = "SELECT {$fields} FROM {$this->table} WHERE 1=1";

        // Role filter (case-insensitive)
        $rolesParam = [];
        if (!empty($roles)) {
            if (!is_array($roles)) { $roles = [$roles]; }
            $placeholders = [];
            foreach ($roles as $idx => $role) {
                $ph = ":role{$idx}";
                $placeholders[] = $ph;
                $rolesParam[$ph] = strtolower($role);
            }
            if (!empty($placeholders)) {
                $sql .= " AND LOWER(role) IN (" . implode(',', $placeholders) . ")";
            }
        }

        // Keyword filter across fields
        $sql .= " AND (username LIKE :kw OR email LIKE :kw OR first_name LIKE :kw OR last_name LIKE :kw";
        if ($includePhone) {
            $sql .= " OR phone LIKE :kw";
        }
        // Append limit safely (cannot bind LIMIT with native prepares)
        $limit = max(1, (int)$limit);
        $sql .= ") LIMIT " . $limit;

        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }

        foreach ($rolesParam as $ph => $val) {
            $this->db->bind($ph, $val);
        }
        $this->db->bind(':kw', '%' . $keyword . '%');
        // Do not bind limit; already applied in SQL

        return $this->db->resultSet();
    }
    
    /**
     * Register a new user
     * 
     * @param array $data User data
     * @return int|bool
     */
    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        // If status column exists and not explicitly provided, default to pending for self-registrations
        if (!isset($data['status']) && $this->columnExists('status')) {
            $data['status'] = 'pending';
        }
        
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
        // If a status column exists, only include accepted/approved customers.
        $hasStatus = $this->columnExists('status');
        $sql = "SELECT * FROM {$this->table} WHERE role = :role" . ($hasStatus ? " AND (status IS NULL OR status IN ('accepted','approved'))" : "");
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':role', 'customer');
        return $this->db->resultSet();
    }

    /**
     * Approve a user (set status=accepted/approved and ensure role=customer)
     */
    public function approveUser($userId) {
        $data = [];
        if ($this->columnExists('status')) {
            $data['status'] = 'accepted';
        }
        // Ensure role is customer on approval
        $data['role'] = 'customer';
        return $this->update((int)$userId, $data);
    }

    /**
     * Reject a user (set status=rejected and keep role unchanged)
     */
    public function rejectUser($userId) {
        $data = [];
        if ($this->columnExists('status')) {
            $data['status'] = 'rejected';
        }
        return $this->update((int)$userId, $data);
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
        $data = [];
        if ($this->db && method_exists($this->db, 'columnExists')) {
            if ($this->db->columnExists($this->table, 'last_activity')) {
                $data['last_activity'] = date('Y-m-d H:i:s');
            }
            if ($this->db->columnExists($this->table, 'ip_address')) {
                $data['ip_address'] = $ipAddress;
            }
            if ($this->db->columnExists($this->table, 'user_agent')) {
                $data['user_agent'] = $userAgent;
            }
        }
        if (empty($data)) {
            return true;
        }
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
