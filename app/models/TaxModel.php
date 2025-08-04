<?php
class TaxModel {
    private $db;
    private $table = 'tax_rates';

    public function __construct() {
        $this->db = new Database;
    }

    // Get all tax rates
    public function getTaxRates($activeOnly = true) {
        $sql = 'SELECT * FROM ' . $this->table;
        $params = [];
        
        if ($activeOnly) {
            $sql .= ' WHERE is_active = :is_active';
            $params[':is_active'] = 1;
        }
        
        $sql .= ' ORDER BY name ASC';
        
        $this->db->query($sql);
        
        // Bind parameters if any
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        // Get the results as an array of objects
        $results = $this->db->resultSet();
        
        // Convert each array to an object for consistency with the view's expectations
        if (is_array($results)) {
            return array_map(function($item) {
                return (object)$item;
            }, $results);
        }
        
        return [];
    }

    // Get single tax rate by ID
    public function getTaxRateById($id) {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        // Ensure we return an object for consistency with the view's expectations
        if ($row) {
            return (object)$row;
        }
        
        return null;
    }

    // Add new tax rate
    public function addTaxRate($data) {
        $this->db->query('INSERT INTO ' . $this->table . ' (name, rate, is_active) VALUES (:name, :rate, :is_active)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':rate', $data['rate']);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);

        // Execute and return the new tax rate ID
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    // Update tax rate
    public function updateTaxRate($data) {
        $this->db->query('UPDATE ' . $this->table . ' SET name = :name, rate = :rate, is_active = :is_active WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':rate', $data['rate']);
        $this->db->bind(':is_active', $data['is_active'] ?? 1);
        
        return $this->db->execute();
    }

    // Delete tax rate (soft delete by setting is_active to 0)
    public function deleteTaxRate($id) {
        $this->db->query('UPDATE ' . $this->table . ' SET is_active = 0 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Create tax rates table if not exists
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->table . " (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            rate DECIMAL(10,2) NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->db->query($sql);
        $this->db->execute();
    }
    
    // Ensure tax rates exist in the database
    public function ensureTaxRatesExist() {
        try {
            // First check if table exists
            $this->db->query('SHOW TABLES LIKE "' . $this->table . '"');
            $tableExists = $this->db->resultSet();
            
            if (empty($tableExists)) {
                // Table doesn't exist, create it
                $this->createTable();
                
                // Add a default tax rate
                $this->db->query('INSERT INTO ' . $this->table . ' (name, rate, is_active) VALUES (\'Standard Tax\', 0.00, 1)');
                $this->db->execute();
            } else {
                // Check if we have any active tax rates
                $this->db->query('SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE is_active = 1');
                $result = $this->db->single();
                
                $count = 0;
                if (is_object($result) && isset($result->count)) {
                    $count = (int)$result->count;
                } elseif (is_array($result) && isset($result['count'])) {
                    $count = (int)$result['count'];
                }
                
                if ($count === 0) {
                    // Add a default tax rate if none exists
                    $this->db->query('INSERT INTO ' . $this->table . ' (name, rate, is_active) VALUES (\'Standard Tax\', 0.00, 1)');
                    $this->db->execute();
                }
            }
        } catch (Exception $e) {
            // Log the error
            error_log('Error in ensureTaxRatesExist: ' . $e->getMessage());
            
            // Try to create the table and add a default tax rate
            try {
                $this->createTable();
                $this->db->query('INSERT INTO ' . $this->table . ' (name, rate, is_active) VALUES (\'Standard Tax\', 0.00, 1)');
                $this->db->execute();
            } catch (Exception $e) {
                error_log('Error creating tax_rates table: ' . $e->getMessage());
            }
        }
    }
}
