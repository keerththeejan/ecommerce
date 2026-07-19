<?php
/**
 * Ensure wishlist table exists with unique (user_id, product_id)
 * Safe to run multiple times.
 *
 * Usage: php database/migrate_wishlist.php
 * Or open: http://localhost/ecommerce/database/migrate_wishlist.php
 */
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/database.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $db = new Database();

    $db->query("SHOW TABLES LIKE 'wishlist'");
    $exists = $db->single();

    if (!$exists) {
        $db->query("
            CREATE TABLE `wishlist` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `product_id` int(11) NOT NULL,
                `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `user_product` (`user_id`,`product_id`),
                KEY `product_id` (`product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        $db->execute();
        echo "Created table wishlist\n";
    } else {
        echo "Table wishlist already exists\n";
        $db->query("SHOW COLUMNS FROM wishlist LIKE 'updated_at'");
        if (!$db->single()) {
            $db->query("ALTER TABLE `wishlist` ADD COLUMN `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`");
            $db->execute();
            echo "Added updated_at column\n";
        }
        $db->query("SHOW INDEX FROM wishlist WHERE Key_name = 'user_product'");
        if (!$db->single()) {
            $db->query("ALTER TABLE `wishlist` ADD UNIQUE KEY `user_product` (`user_id`,`product_id`)");
            $db->execute();
            echo "Added unique user_product index\n";
        }
    }

    echo "Wishlist migration OK\n";
} catch (Exception $e) {
    http_response_code(500);
    echo 'Migration error: ' . $e->getMessage() . "\n";
}
