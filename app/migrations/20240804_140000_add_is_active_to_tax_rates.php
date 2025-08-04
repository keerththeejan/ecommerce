<?php
class AddIsActiveToTaxRates {
    public function up($db) {
        try {
            // Check if is_active column exists
            $db->query("SHOW COLUMNS FROM tax_rates LIKE 'is_active'");
            $columnExists = $db->single();
            
            if (!$columnExists) {
                // Add is_active column
                $db->query("ALTER TABLE tax_rates ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER rate");
                $db->execute();
                
                // Update existing records to be active by default
                $db->query("UPDATE tax_rates SET is_active = 1 WHERE is_active IS NULL");
                $db->execute();
                
                echo "Successfully added is_active column to tax_rates table.\n";
            } else {
                echo "is_active column already exists in tax_rates table.\n";
            }
            
            return true;
        } catch (Exception $e) {
            echo "Error in migration: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    public function down($db) {
        try {
            // Remove is_active column
            $db->query("ALTER TABLE tax_rates DROP COLUMN is_active");
            $db->execute();
            echo "Successfully removed is_active column from tax_rates table.\n";
            return true;
        } catch (Exception $e) {
            echo "Error rolling back migration: " . $e->getMessage() . "\n";
            return false;
        }
    }
}
