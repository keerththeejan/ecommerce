<?php

class CreatePurchasePaymentsTable {
    public function up() {
        $db = new Database();
        
        $sql = "CREATE TABLE IF NOT EXISTS `purchase_payments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `purchase_id` int(11) NOT NULL,
            `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
            `payment_date` datetime NOT NULL,
            `payment_method` varchar(50) NOT NULL,
            `transaction_id` varchar(100) DEFAULT NULL,
            `notes` text DEFAULT NULL,
            `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'completed',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `purchase_id` (`purchase_id`),
            CONSTRAINT `fk_purchase_payment` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        
        $db->query($sql);
        $db->execute();
        
        // Add payment_status and due_date columns to purchases table if they don't exist
        $db->query("SHOW COLUMNS FROM `purchases` LIKE 'payment_status'");
        if($db->rowCount() == 0) {
            $db->query("ALTER TABLE `purchases` 
                ADD COLUMN `payment_status` ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid' AFTER `status`,
                ADD COLUMN `due_date` DATE NULL AFTER `payment_status`");
            
            // Set default due date to 30 days from purchase date for existing records
            $db->query("UPDATE `purchases` SET `due_date` = DATE_ADD(`purchase_date`, INTERVAL 30 DAY) WHERE `due_date` IS NULL");
            $db->execute();
        }
    }
    
    public function down() {
        $db = new Database();
        $db->query("DROP TABLE IF EXISTS `purchase_payments`");
        $db->execute();
        
        // Don't drop the columns in case they're being used by other parts of the system
        // $db->query("ALTER TABLE `purchases` DROP COLUMN IF EXISTS `payment_status`, DROP COLUMN IF EXISTS `due_date`");
        // $db->execute();
    }
}
