<?php
/**
 * Temporary migration runner - delete after use
 */

// Load required files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/migrations/20260505_optimize_low_stock_indexes.php';

try {
    $migration = new OptimizeLowStockIndexesMigration();
    $migration->up();
    echo "✅ Migration completed successfully!<br>";
    echo "Indexes added: idx_products_stock_quantity, idx_products_stock_category, idx_categories_name<br>";
} catch (Exception $e) {
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
}

// Self-delete warning
echo "<br><br><strong>Security:</strong> Delete this file after running: <code>public/run_migration.php</code>";
