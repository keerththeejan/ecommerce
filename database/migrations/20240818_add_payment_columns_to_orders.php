<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

class AddPaymentColumnsToOrders {
    public function up() {
        $db = new Database();
        
        // Add payment_status column to orders table if it doesn't exist
        $db->query("SHOW COLUMNS FROM `orders` LIKE 'payment_status'");
        if($db->rowCount() == 0) {
            $db->query("ALTER TABLE `orders` 
                ADD COLUMN `payment_status` ENUM('unpaid', 'partial', 'paid') NOT NULL DEFAULT 'unpaid' AFTER `status`");
            $db->execute();
        }
        
        // Add due_date column to orders table if it doesn't exist
        $db->query("SHOW COLUMNS FROM `orders` LIKE 'due_date'");
        if($db->rowCount() == 0) {
            $db->query("ALTER TABLE `orders` 
                ADD COLUMN `due_date` DATE NULL AFTER `payment_status`");
            $db->execute();
            
            // Set default due date to 30 days from created_at for existing records
            $db->query("UPDATE `orders` SET `due_date` = DATE_ADD(`created_at`, INTERVAL 30 DAY) WHERE `due_date` IS NULL");
            $db->execute();
        }
        
        // Create order_payments table if it doesn't exist
        $db->query("SHOW TABLES LIKE 'order_payments'");
        if($db->rowCount() == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `order_payments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` int(11) NOT NULL,
                `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
                `payment_date` datetime NOT NULL,
                `payment_method` varchar(50) NOT NULL,
                `transaction_id` varchar(100) DEFAULT NULL,
                `notes` text DEFAULT NULL,
                `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'completed',
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `order_id` (`order_id`),
                CONSTRAINT `fk_order_payment` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
            
            $db->query($sql);
            $db->execute();
        }
    }
    
    public function down() {
        $db = new Database();
        
        // Drop order_payments table
        $db->query("DROP TABLE IF EXISTS `order_payments`");
        $db->execute();
        
        // Note: We're not dropping the columns from orders table as they might contain important data
    }
}

// Run the migration
$migration = new AddPaymentColumnsToOrders();
$migration->up();

echo "Migration completed successfully.\n";
