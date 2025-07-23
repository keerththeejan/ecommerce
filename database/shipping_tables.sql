-- Create shipping_methods table
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_weight_based` tinyint(1) NOT NULL DEFAULT '0',
  `free_weight_threshold` decimal(10,2) DEFAULT NULL,
  `weight_step` decimal(10,2) DEFAULT NULL,
  `price_per_step` decimal(10,2) DEFAULT NULL,
  `free_shipping_threshold` decimal(10,2) DEFAULT NULL,
  `estimated_delivery` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample shipping methods
INSERT INTO `shipping_methods` (`name`, `description`, `base_price`, `is_weight_based`, `free_weight_threshold`, `weight_step`, `price_per_step`, `free_shipping_threshold`, `estimated_delivery`, `is_active`, `sort_order`) VALUES
('Standard Shipping', 'Standard delivery within 3-5 business days', 5.99, 1, 5.00, 1.00, 0.50, 50.00, '3-5 business days', 1, 1),
('Express Shipping', 'Faster delivery within 1-2 business days', 12.99, 1, 5.00, 1.00, 1.00, 100.00, '1-2 business days', 1, 2),
('Free Shipping', 'Free standard shipping', 0.00, 0, NULL, NULL, NULL, 50.00, '5-7 business days', 1, 3);

-- Create addresses table if it doesn't exist
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('billing','shipping') NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
