<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_tax_rates_table extends CI_Migration {

    public function up() {
        // Create tax_rates table
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'tax1' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'tax2' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'tax3' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'tax4' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP'
            ]
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tax_rates');
        
        // Insert default values
        $this->db->insert('tax_rates', [
            'tax1' => 0.00,
            'tax2' => 0.00,
            'tax3' => 0.00,
            'tax4' => 0.00
        ]);
    }

    public function down() {
        $this->dbforge->drop_table('tax_rates');
    }
}
