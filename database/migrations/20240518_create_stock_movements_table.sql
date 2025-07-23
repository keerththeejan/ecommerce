-- Create stock_movements table
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `previous_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `new_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `adjustment` decimal(10,2) NOT NULL COMMENT 'Positive for addition, negative for subtraction',
  `action` enum('add','subtract','set') NOT NULL,
  `notes` text,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add foreign key constraints (uncomment after confirming the tables exist)
-- ALTER TABLE `stock_movements`
--   ADD CONSTRAINT `fk_stock_movements_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
--   ADD CONSTRAINT `fk_stock_movements_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
