<?php
/**
 * Newsletter Model
 */
class Newsletter extends Model {
    protected $table = 'newsletter_subscribers';
    
    /**
     * Ensure table exists on model instantiation
     */
    public function __construct() {
        parent::__construct();
        // Create table if it doesn't exist to avoid runtime failures on first access
        if (method_exists($this->db, 'tableExists')) {
            if(!$this->db->tableExists($this->table)) {
                $this->createTable();
            }
        } else {
            // Fallback: attempt to create with IF NOT EXISTS (safe in MySQL)
            $this->createTable();
        }
    }
    
    /**
     * Check if email already exists
     * 
     * @param string $email Email address
     * @return bool
     */
    public function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':email', $email);
        $result = $this->db->single();
        
        return $result && $result['count'] > 0;
    }
    
    /**
     * Add subscriber
     * 
     * @param array $data Subscriber data
     * @return bool
     */
    public function addSubscriber($data) {
        // Check if table exists, create if not
        if(!$this->db->tableExists($this->table)) {
            $this->createTable();
        }
        
        $sql = "INSERT INTO {$this->table} (email, created_at) VALUES (:email, NOW())";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':email', $data['email']);
        
        return $this->db->execute();
    }
    
    /**
     * Create newsletter subscribers table
     * 
     * @return bool
     */
    private function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        try {
            // Use direct exec for DDL to avoid prepare/execute issues
            $pdo = $this->db->getConnection();
            if (!$pdo) {
                return false;
            }
            $pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Newsletter createTable error: ' . $this->lastError);
            return false;
        }
    }
    
    /**
     * Get all subscribers
     * 
     * @param bool $activeOnly Get only active subscribers
     * @return array
     */
    public function getAllSubscribers($activeOnly = true) {
        $sql = "SELECT * FROM {$this->table}";
        
        if($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Unsubscribe email
     * 
     * @param string $email Email address
     * @return bool
     */
    public function unsubscribe($email) {
        $sql = "UPDATE {$this->table} SET active = 0 WHERE email = :email";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }

    /**
     * Get a subscriber by ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return null;
        }
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    /**
     * Get a subscriber by email
     * @param string $email
     * @return array|null
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return null;
        }
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Delete a subscriber by ID
     * @param int $id
     * @return bool
     */
    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }

    /**
     * Update a subscriber by ID
     * @param int $id
     * @param array $data ['email' => string, 'active' => int|bool]
     * @return bool
     */
    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table} SET email = :email, active = :active, updated_at = NOW() WHERE id = :id";
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':active', (int)!empty($data['active']));
        $this->db->bind(':id', (int)$id);
        return $this->db->execute();
    }
}
