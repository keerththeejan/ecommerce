<?php

class ContactMessagesMigration {
    public function up() {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        
        $sql = "CREATE TABLE IF NOT EXISTS `contacts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `email` varchar(100) NOT NULL,
            `phone` varchar(20) DEFAULT NULL,
            `subject` varchar(255) NOT NULL,
            `message` text NOT NULL,
            `status` enum('unread','read','replied') NOT NULL DEFAULT 'unread',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `status` (`status`),
            KEY `created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        $connection->exec($sql);
    }
    
    public function down() {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        
        $sql = "DROP TABLE IF EXISTS `contacts`";
        $connection->exec($sql);
    }
}
