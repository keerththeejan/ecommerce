<?php
/**
 * Cart Model
 */
class Cart extends Model {
    protected $table = 'cart';
    
    /**
     * Add item to cart
     * 
     * @param int $userId User ID
     * @param int $productId Product ID
     * @param int $quantity Quantity
     * @return int|bool
     */
    public function addToCart($userId, $productId, $quantity = 1) {
        // Check if product already in cart
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $existing = $this->db->single();
        
        if($existing) {
            // Update quantity
            $this->db->query("UPDATE {$this->table} SET quantity = quantity + :quantity WHERE id = :id");
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':id', $existing['id']);
            return $this->db->execute();
        } else {
            // Add new item
            $data = [
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ];
            
            return $this->create($data);
        }
    }
    
    /**
     * Update cart item quantity
     * 
     * @param int $cartId Cart item ID
     * @param int $quantity New quantity
     * @return bool
     */
    public function updateQuantity($cartId, $quantity) {
        $this->db->query("UPDATE {$this->table} SET quantity = :quantity WHERE id = :id");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $cartId);
        return $this->db->execute();
    }
    
    /**
     * Remove item from cart
     * 
     * @param int $cartId Cart item ID
     * @return bool
     */
    public function removeFromCart($cartId) {
        return $this->delete($cartId);
    }
    
    /**
     * Clear user's cart
     * 
     * @param int $userId User ID
     * @return bool
     */
    public function clearCart($userId) {
        $this->db->query("DELETE FROM {$this->table} WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    /**
     * Get user's cart items with product details
     * 
     * @param int $userId User ID
     * @return array
     */
    public function getCartItems($userId) {
        $this->db->query("SELECT c.*, p.name, p.price, p.sale_price, p.image, p.stock_quantity, p.sku
                         FROM {$this->table} c
                         JOIN products p ON c.product_id = p.id
                         WHERE c.user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    /**
     * Get cart total
     * 
     * @param int $userId User ID
     * @return float
     */
    public function getCartTotal($userId) {
        $this->db->query("SELECT SUM(
                            c.quantity * 
                            IF(p.sale_price IS NOT NULL AND p.sale_price > 0, 
                               p.sale_price, 
                               p.price)
                          ) as total
                         FROM {$this->table} c
                         JOIN products p ON c.product_id = p.id
                         WHERE c.user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }
    
    /**
     * Get cart count
     * 
     * @param int $userId User ID
     * @return int
     */
    public function getCartCount($userId) {
        $this->db->query("SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result['count'];
    }
    
    /**
     * Check if product is in cart
     * 
     * @param int $userId User ID
     * @param int $productId Product ID
     * @return bool
     */
    public function isInCart($userId, $productId) {
        $this->db->query("SELECT * FROM {$this->table} WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $result = $this->db->single();
        return $result ? true : false;
    }
}
