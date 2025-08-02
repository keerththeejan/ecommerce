<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax_model extends CI_Model {
    
    private $table = 'tax_rates';
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->create_table();
    }
    
    private function create_table() {
        // Create tax_rates table if it doesn't exist
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . $this->db->dbprefix($this->table) . "` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `tax1` decimal(10,2) DEFAULT 0.00,
                `tax2` decimal(10,2) DEFAULT 0.00,
                `tax3` decimal(10,2) DEFAULT 0.00,
                `tax4` decimal(10,2) DEFAULT 0.00,
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
            -- Insert default values if table is empty
            INSERT IGNORE INTO `" . $this->db->dbprefix($this->table) . "` 
            (`tax1`, `tax2`, `tax3`, `tax4`) 
            SELECT 0.00, 0.00, 0.00, 0.00 
            WHERE NOT EXISTS (SELECT 1 FROM `" . $this->db->dbprefix($this->table) . "` LIMIT 1);
        ");
    }
    
    public function get_tax_rates() {
        $query = $this->db->get($this->table, 1);
        return $query->row_array();
    }
    
    public function update_tax_rates($data) {
        // Ensure we only update the first record (there should only be one)
        $this->db->limit(1);
        return $this->db->update($this->table, $data);
    }
}
