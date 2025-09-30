<?php

class CreateFooterSectionsTable {
    public function up() {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        
        $sql = "CREATE TABLE IF NOT EXISTS `footer_sections` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `type` varchar(50) NOT NULL,
            `status` enum('active','inactive') NOT NULL DEFAULT 'active',
            `sort_order` int(11) NOT NULL DEFAULT 0,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $connection->exec($sql);
    }

    public function down() {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        $connection->exec("DROP TABLE IF EXISTS `footer_sections`");
    }
}
