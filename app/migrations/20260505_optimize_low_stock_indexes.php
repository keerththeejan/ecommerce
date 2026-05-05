<?php

class OptimizeLowStockIndexesMigration {
    public function up() {
        $db = new Database();
        $connection = $db->getConnection();

        // Add indexes for low stock queries
        $this->addIndex($connection, 'products', 'idx_products_stock_quantity', 'stock_quantity');
        $this->addIndex($connection, 'products', 'idx_products_stock_category', 'stock_quantity, category_id');
        
        // Add indexes for related tables
        $this->addIndex($connection, 'stock_movements', 'idx_stock_movements_product_id', 'product_id');
        $this->addIndex($connection, 'order_items', 'idx_order_items_product_id', 'product_id');
        
        // Add index on categories name for faster joins
        $this->addIndex($connection, 'categories', 'idx_categories_name', 'name');
    }

    public function down() {
        $db = new Database();
        $connection = $db->getConnection();

        $this->dropIndex($connection, 'categories', 'idx_categories_name');
        $this->dropIndex($connection, 'order_items', 'idx_order_items_product_id');
        $this->dropIndex($connection, 'stock_movements', 'idx_stock_movements_product_id');
        $this->dropIndex($connection, 'products', 'idx_products_stock_category');
        $this->dropIndex($connection, 'products', 'idx_products_stock_quantity');
    }

    private function addIndex(PDO $connection, $table, $index, $columns) {
        if (!$this->tableExists($connection, $table) || $this->indexExists($connection, $table, $index)) {
            return;
        }
        $connection->exec("ALTER TABLE `{$table}` ADD INDEX `{$index}` ({$columns})");
    }

    private function dropIndex(PDO $connection, $table, $index) {
        if (!$this->tableExists($connection, $table) || !$this->indexExists($connection, $table, $index)) {
            return;
        }
        $connection->exec("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
    }

    private function tableExists(PDO $connection, $table) {
        $stmt = $connection->query('SHOW TABLES LIKE ' . $connection->quote($table));
        return (bool)$stmt->fetch();
    }

    private function indexExists(PDO $connection, $table, $index) {
        $stmt = $connection->query("SHOW INDEX FROM `{$table}` WHERE Key_name = " . $connection->quote($index));
        return (bool)$stmt->fetch();
    }
}
