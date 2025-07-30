<?php
/**
 * Shipping Model
 * Handles database operations for shipping methods
 */
class Shipping {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Get all active shipping methods
     */
    public function getShippingMethods() {
        $this->db->query('SELECT * FROM shipping_methods WHERE is_active = 1 ORDER BY sort_order, name');
        $results = $this->db->resultSet();
        return $results;
    }
    
    /**
     * Get shipping method by ID
     */
    public function getShippingMethodById($id) {
        $this->db->query('SELECT * FROM shipping_methods WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }
    
    /**
     * Calculate shipping cost
     */
    public function calculateShipping($methodId, $cartTotal, $itemsCount, $weight, $destination) {
        // Get the shipping method
        $method = $this->getShippingMethodById($methodId);
        
        if (!$method) {
            return 0; // Default to free shipping if method not found
        }
        
        // Simple flat rate calculation
        // In a real application, this would be more complex
        $shippingCost = $method->base_price;
        
        // Add additional costs based on weight (if applicable)
        if ($method->is_weight_based && $weight > $method->free_weight_threshold) {
            $extraWeight = $weight - $method->free_weight_threshold;
            $shippingCost += ceil($extraWeight / $method->weight_step) * $method->price_per_step;
        }
        
        return $shippingCost;
    }
    
    /**
     * Get estimated delivery days
     */
    public function getEstimatedDeliveryDays($methodId, $destination) {
        $method = $this->getShippingMethodById($methodId);
        
        if (!$method) {
            return '3-5 business days'; // Default
        }
        
        return $method->estimated_delivery;
    }
}
