-- Add tax_id column to products table for product-level tax override
-- Products can optionally override category tax with a specific tax rate

ALTER TABLE `products` 
ADD COLUMN `tax_id` INT(11) NULL DEFAULT NULL AFTER `category_id`,
ADD KEY `fk_products_tax` (`tax_id`);
