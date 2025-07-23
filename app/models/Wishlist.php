<?php
/**
 * Wishlist Model
 * Handles database operations for wishlist functionality
 */
class Wishlist extends Model {
    
    public function __construct() {
        parent::__construct();
        $this->createWishlistTableIfNotExists();
    }
    
    /**
     * Create wishlist table if it doesn't exist
     */
    private function createWishlistTableIfNotExists() {
        try {
            // First, check if the table exists
            $this->db->query("SHOW TABLES LIKE 'wishlist'");
            $tableExists = $this->db->single();
            
            if (!$tableExists) {
                // Table doesn't exist, create it
                $sql = "
                CREATE TABLE `wishlist` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `product_id` int(11) NOT NULL,
                    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `user_product` (`user_id`,`product_id`),
                    KEY `product_id` (`product_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                
                $this->db->query($sql);
                $this->db->execute();
                
                // Add foreign key constraints if they don't exist
                try {
                    $this->db->query("
                        ALTER TABLE `wishlist`
                        ADD CONSTRAINT `wishlist_ibfk_1`
                        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    ");
                    $this->db->execute();
                    
                    $this->db->query("
                        ALTER TABLE `wishlist`
                        ADD CONSTRAINT `wishlist_ibfk_2`
                        FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
                    ");
                    $this->db->execute();
                } catch (Exception $e) {
                    // If foreign key constraints fail, continue without them
                    error_log('Warning: Could not add foreign key constraints: ' . $e->getMessage());
                }
            }
        } catch (Exception $e) {
            error_log('Error creating wishlist table: ' . $e->getMessage());
        }
    }
    /**
     * Add product to user's wishlist
     */
    public function addToWishlist($userId, $productId) {
        // Check if already in wishlist
        if ($this->isInWishlist($userId, $productId)) {
            return true; // Already exists, consider it a success
        }
        
        $this->db->query('INSERT INTO wishlist (user_id, product_id, created_at) VALUES (:user_id, :product_id, NOW())');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        
        return $this->db->execute();
    }
    
    /**
     * Remove product from user's wishlist
     */
    public function removeFromWishlist($userId, $productId) {
        $this->db->query('DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        
        return $this->db->execute();
    }
    
    /**
     * Check if product is in user's wishlist
     */
    public function isInWishlist($userId, $productId) {
        $this->db->query('SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        
        $row = $this->db->single();
        
        return $row ? true : false;
    }
    
    /**
     * Get user's wishlist with product details
     */
    public function getUserWishlist($userId) {
        $this->db->query('SELECT p.*, w.id as wishlist_id, w.created_at as added_date 
                         FROM products p 
                         JOIN wishlist w ON p.id = w.product_id 
                         WHERE w.user_id = :user_id 
                         ORDER BY w.created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get wishlist count for a user
     */
    public function getWishlistCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM wishlist WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }
}
