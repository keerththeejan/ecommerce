<?php
class UpdateTaxRatesTable {
    public function up($db) {
        // Rename old table for backup
        $db->query('RENAME TABLE tax_rates TO tax_rates_old');
        
        // Create new tax_rates table
        $sql = "CREATE TABLE IF NOT EXISTS tax_rates (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            rate DECLIMAL(10,2) NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $db->query($sql);
        
        // Migrate data from old table if it exists
        $db->query('SELECT * FROM tax_rates_old LIMIT 1');
        $oldRates = $db->single();
        
        if ($oldRates) {
            $rates = [
                ['name' => 'Tax 1', 'rate' => $oldRates->tax1 ?? 0],
                ['name' => 'Tax 2', 'rate' => $oldRates->tax2 ?? 0],
                ['name' => 'Tax 3', 'rate' => $oldRates->tax3 ?? 0],
                ['name' => 'Tax 4', 'rate' => $oldRates->tax4 ?? 0]
            ];
            
            foreach ($rates as $rate) {
                if ($rate['rate'] > 0) {
                    $db->query('INSERT INTO tax_rates (name, rate) VALUES (:name, :rate)');
                    $db->bind(':name', $rate['name']);
                    $db->bind(':rate', $rate['rate']);
                    $db->execute();
                }
            }
        } else {
            // Insert default tax rate if no old data exists
            $db->query('INSERT INTO tax_rates (name, rate) VALUES (\'Standard Tax\', 0.00)');
            $db->execute();
        }
    }
    
    public function down($db) {
        // Drop new table and restore old one if needed
        $db->query('DROP TABLE IF EXISTS tax_rates');
        $db->query('RENAME TABLE tax_rates_old TO tax_rates');
    }
}
