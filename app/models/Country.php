<?php
/**
 * Country Model
 */
class Country extends Model {
    protected $table = 'countries';
    
    /**
     * Get all active countries
     * 
     * @return array
     */
    public function getActiveCountries() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY name ASC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', 'active');
        return $this->db->resultSet();
    }
    
    /**
     * Get country by ID
     * 
     * @param int $id Country ID
     * @return array|bool
     */
    public function getCountryById($id) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return false;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get products by country
     * 
     * @param int $countryId Country ID
     * @return array
     */
    public function getProductsByCountry($countryId) {
        // Check if tables exist
        if(!$this->db->tableExists($this->table) || !$this->db->tableExists('products')) {
            return [];
        }
        
        $sql = "SELECT p.*, c.name as category_name, co.name as country_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN {$this->table} co ON p.country_id = co.id
                WHERE p.country_id = :country_id AND p.status = :status";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':country_id', $countryId);
        $this->db->bind(':status', 'active');
        return $this->db->resultSet();
    }
}
