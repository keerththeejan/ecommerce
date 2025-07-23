<?php
/**
 * Newsletter Model
 */
class Newsletter extends Model {
    protected $table = 'newsletter_subscribers';
    
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
        )";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        return $this->db->execute();
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
}
