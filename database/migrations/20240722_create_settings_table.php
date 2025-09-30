<?php
/**
 * Migration to create settings table
 */

class CreateSettingsTable {
    public function up() {
        $db = new Database();
        
        // Create settings table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS `settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `key` varchar(255) NOT NULL,
            `value` text,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `key` (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $db->query($sql);
        return $db->execute();
    }
    
    public function down() {
        $db = new Database();
        $sql = "DROP TABLE IF EXISTS `settings`";
        $db->query($sql);
        return $db->execute();
    }
}
