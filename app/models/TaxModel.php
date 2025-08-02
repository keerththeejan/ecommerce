<?php
class TaxModel {
    private $db;
    private $table = 'tax_rates';

    public function __construct() {
        $this->db = new Database;
    }

    // Get all tax rates
    public function getTaxRates() {
        $this->db->query('SELECT * FROM ' . $this->table . ' LIMIT 1');
        $result = $this->db->resultSet();
        return !empty($result) ? (array)$result[0] : [
            'tax1' => 0.00,
            'tax2' => 0.00,
            'tax3' => 0.00,
            'tax4' => 0.00
        ];
    }

    // Update tax rates
    public function updateTaxRates($data) {
        $this->db->query('UPDATE ' . $this->table . ' SET tax1 = :tax1, tax2 = :tax2, tax3 = :tax3, tax4 = :tax4 WHERE id = 1');
        
        // Bind values
        $this->db->bind(':tax1', $data['tax1']);
        $this->db->bind(':tax2', $data['tax2']);
        $this->db->bind(':tax3', $data['tax3']);
        $this->db->bind(':tax4', $data['tax4']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Create tax rates table if not exists
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->table . " (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            tax1 DECIMAL(10,2) DEFAULT 0.00,
            tax2 DECIMAL(10,2) DEFAULT 0.00,
            tax3 DECIMAL(10,2) DEFAULT 0.00,
            tax4 DECIMAL(10,2) DEFAULT 0.00,
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
            }
            
            // Now check if we have any records
            $this->db->query('SELECT COUNT(*) as count FROM ' . $this->table);
            $result = $this->db->single();
            
            // Check if we have a valid result and if count is 0
            $count = 0;
            if (is_object($result) && isset($result->count)) {
                $count = (int)$result->count;
            } elseif (is_array($result) && isset($result['count'])) {
                $count = (int)$result['count'];
            }
            
            if ($count === 0) {
                $this->db->query('INSERT INTO ' . $this->table . ' (tax1, tax2, tax3, tax4) VALUES (0.00, 0.00, 0.00, 0.00)');
                $this->db->execute();
            }
        } catch (Exception $e) {
            // If there's an error, try to create the table and insert default values
            try {
                $this->createTable();
                $this->db->query('INSERT INTO ' . $this->table . ' (tax1, tax2, tax3, tax4) VALUES (0.00, 0.00, 0.00, 0.00)');
                $this->db->execute();
            } catch (Exception $e) {
                // Log the error if needed
                error_log('Error in ensureTaxRatesExist: ' . $e->getMessage());
            }
        }
    }
}
