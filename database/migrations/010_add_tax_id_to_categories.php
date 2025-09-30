<?php

class AddTaxIdToCategories {
    public function up() {
        $db = Database::getInstance();
        
        // Add tax_id column to categories table
        $sql = "ALTER TABLE categories 
                ADD COLUMN tax_id INT NULL,
                ADD CONSTRAINT fk_categories_tax 
                FOREIGN KEY (tax_id) REFERENCES tax_rates(id) 
                ON DELETE SET NULL";
                
        $db->query($sql);
    }
    
    public function down() {
        $db = Database::getInstance();
        
        // Remove the foreign key constraint first
        $sql = "ALTER TABLE categories DROP FOREIGN KEY fk_categories_tax";
        $db->query($sql);
        
        // Then drop the column
        $sql = "ALTER TABLE categories DROP COLUMN tax_id";
        $db->query($sql);
    }
}
