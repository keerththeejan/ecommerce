<?php

class OptimizeLowStockIndexesMigration {
    public function up() {
        $db = new Database();
        $connection = $db->getConnection();

        $this->addIndex($connection, 'products', 'idx_products_stock_quantity', 'stock_quantity');
        $this->addIndex($connection, 'products', 'idx_products_stock_category', 'stock_quantity, category_id');
        $this->addIndex($connection, 'stock_movements', 'idx_stock_movements_product_id', 'product_id');
        $this->addIndex($connection, 'order_items', 'idx_order_items_product_id', 'product_id');
    }

    public function down() {
        $db = new Database();
        $connection = $db->getConnection();

        $this->dropIndex($connection, 'products', 'idx_products_stock_category');
        $this->dropIndex($connection, 'products', 'idx_products_stock_quantity');
        $this->dropIndex($connection, 'stock_movements', 'idx_stock_movements_product_id');
        $this->dropIndex($connection, 'order_items', 'idx_order_items_product_id');
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
