-- Safe index creation script for MySQL (run once).
-- Usage: execute on the same DB used by the app (sn).

SET @db_name = DATABASE();

-- products.sku
SET @idx_exists = (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = @db_name
      AND table_name = 'products'
      AND index_name = 'idx_products_sku'
);
SET @sql = IF(@idx_exists = 0, 'CREATE INDEX idx_products_sku ON products (sku)', 'SELECT "idx_products_sku exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- products.category_id
SET @idx_exists = (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = @db_name
      AND table_name = 'products'
      AND index_name = 'idx_products_category_id'
);
SET @sql = IF(@idx_exists = 0, 'CREATE INDEX idx_products_category_id ON products (category_id)', 'SELECT "idx_products_category_id exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- order_items.product_id
SET @idx_exists = (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = @db_name
      AND table_name = 'order_items'
      AND index_name = 'idx_order_items_product_id'
);
SET @sql = IF(@idx_exists = 0, 'CREATE INDEX idx_order_items_product_id ON order_items (product_id)', 'SELECT "idx_order_items_product_id exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- products.status (used frequently in admin/customer listings)
SET @idx_exists = (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = @db_name
      AND table_name = 'products'
      AND index_name = 'idx_products_status'
);
SET @sql = IF(@idx_exists = 0, 'CREATE INDEX idx_products_status ON products (status)', 'SELECT "idx_products_status exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- orders.payment_status, created_at (dashboard + reports)
SET @idx_exists = (
    SELECT COUNT(1)
    FROM information_schema.statistics
    WHERE table_schema = @db_name
      AND table_name = 'orders'
      AND index_name = 'idx_orders_payment_created'
);
SET @sql = IF(@idx_exists = 0, 'CREATE INDEX idx_orders_payment_created ON orders (payment_status, created_at)', 'SELECT "idx_orders_payment_created exists"');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
