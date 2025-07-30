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
     * Get all countries
     * 
     * @return array
     */
    public function getAllCountries() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
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
     * Add a new country
     * 
     * @param array $data Country data
     * @return bool
     */
    public function addCountry($data) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return false;
        }
        
        $sql = "INSERT INTO {$this->table} (name, status, created_at) 
                VALUES (:name, :status, :created_at)";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':created_at', $data['created_at']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            $this->lastError = $this->db->getError();
            return false;
        }
    }
    
    /**
     * Update country
     * 
     * @param array $data Country data
     * @return bool
     */
    public function updateCountry($data) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return false;
        }
        
        // Build the SQL query
        $sql = "UPDATE {$this->table} SET 
                name = :name,
                description = :description,
                status = :status,
                updated_at = :updated_at";
                
        // Add flag_image to query if it exists in data
        if (isset($data['flag_image'])) {
            $sql .= ", flag_image = :flag_image";
        }
        
        $sql .= " WHERE id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description'] ?? '');
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':updated_at', $data['updated_at']);
        
        // Bind flag_image if it exists
        if (isset($data['flag_image'])) {
            $this->db->bind(':flag_image', $data['flag_image']);
        }
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            $this->lastError = $this->db->getError();
            return false;
        }
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
