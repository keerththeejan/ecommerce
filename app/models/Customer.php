<?php
/**
 * Customer Model
 * Handles database operations for customers
 */
class Customer {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Get all customers (for admin)
     */
    public function getCustomers() {
        $this->db->query('SELECT * FROM users WHERE role = :role ORDER BY first_name, last_name');
        $this->db->bind(':role', 'customer');
        
        $results = $this->db->resultSet();
        return $results;
    }
    
    /**
     * Get customer by ID
     */
    public function getCustomerById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }
    
    /**
     * Get customer's default shipping address
     */
    public function getDefaultShippingAddress($userId) {
        $this->db->query('SELECT * FROM addresses WHERE user_id = :user_id AND is_default = 1 AND type = "shipping"');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row;
    }
    
    /**
     * Get customer's default billing address
     */
    public function getDefaultBillingAddress($userId) {
        $this->db->query('SELECT * FROM addresses WHERE user_id = :user_id AND is_default = 1 AND type = "billing"');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row;
    }
    
    /**
     * Get customer's recent orders
     */
    public function getRecentOrders($userId, $limit = 5) {
        $this->db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC LIMIT :limit');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        return $results;
    }
    
    /**
     * Update customer profile
     */
    public function updateProfile($data) {
        $this->db->query('UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
