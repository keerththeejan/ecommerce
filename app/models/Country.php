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
     * Get all countries with their product counts
     * 
     * @return array
     */
    public function getAllCountriesWithProductCounts() {
        // Check if table exists
        if(!$this->db->tableExists($this->table) || !$this->db->tableExists('products')) {
            return [];
        }
        
        $sql = "SELECT c.*, COUNT(p.id) as products_count 
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.country_id 
                GROUP BY c.id 
                ORDER BY c.name ASC
                LIMIT 30";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $result = $this->db->resultSet();
        
        // Ensure products_count is set to 0 for countries with no products
        foreach ($result as &$row) {
            $row['products_count'] = (int)$row['products_count'];
        }
        
        return $result;
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
     * @return int|bool
     */
    public function addCountry($data) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            $this->lastError = "Table '{$this->table}' does not exist";
            return false;
        }
        
        // Prepare the SQL query with all available fields
        $fields = ['name', 'code', 'status', 'description', 'flag_image', 'created_at'];
        $columns = [];
        $placeholders = [];
        
        foreach ($fields as $field) {
            if (isset($data[$field]) || $field === 'created_at') {
                $columns[] = $field;
                $placeholders[] = ":$field";
            }
        }
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->db->bind(":$field", $data[$field]);
            } elseif ($field === 'created_at') {
                $this->db->bind(":$field", date('Y-m-d H:i:s'));
            }
        }
        
        // Execute and return the new country ID
        if($this->db->execute()) {
            return $this->db->lastInsertId();
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
            $this->lastError = "Table '{$this->table}' does not exist";
            return false;
        }
        
        // Build the SQL query with only the fields that exist in the data array
        $updates = [
            "name = :name",
            "status = :status",
            "updated_at = :updated_at"
        ];
        
        // Add optional fields if they exist in the data array
        if (isset($data['description'])) {
            $updates[] = "description = :description";
        }
        
        if (isset($data['flag_image'])) {
            $updates[] = "flag_image = :flag_image";
        }
        
        if (isset($data['code'])) {
            $updates[] = "code = :code";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(", ", $updates) . " WHERE id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':updated_at', date('Y-m-d H:i:s'));
        
        // Optional bindings
        if (isset($data['description'])) {
            $this->db->bind(':description', $data['description']);
        }
        
        if (isset($data['flag_image'])) {
            $this->db->bind(':flag_image', $data['flag_image']);
        }
        
        if (isset($data['code'])) {
            $this->db->bind(':code', $data['code']);
        }
        
        // Execute the query
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
    
    /**
     * Get the last error message
     * 
     * @return string
     */
    public function getLastError() {
        return $this->lastError;
    }
    
    /**
     * Delete a country
     * 
     * @param int $id Country ID
     * @return bool
     */
    public function deleteCountry($id) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            $this->lastError = 'Table does not exist';
            return false;
        }
        
        // First, check if there are any products associated with this country
        $sql = "SELECT COUNT(*) as count FROM products WHERE country_id = :country_id";
        $this->db->query($sql);
        $this->db->bind(':country_id', $id);
        $result = $this->db->single();
        
        if ($result && $result['count'] > 0) {
            $this->lastError = 'Cannot delete country: There are products associated with this country';
            return false;
        }
        
        // Get country data to delete flag image
        $country = $this->getCountryById($id);
        
        // Delete the country
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        
        $result = $this->db->execute();
        
        // If delete was successful, delete the flag image if it exists
        if ($result && !empty($country['flag_image'])) {
            $flagPath = UPLOAD_PATH . 'flags/' . $country['flag_image'];
            if (file_exists($flagPath)) {
                unlink($flagPath);
            }
        }
        
        return $result;
    }
}
