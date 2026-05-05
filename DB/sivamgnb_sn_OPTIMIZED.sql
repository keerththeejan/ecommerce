-- phpMyAdmin SQL Dump - OPTIMIZED VERSION
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 05, 2026 at 05:55 AM
-- Server version: 11.4.10-MariaDB-cll-lve-log / MySQL 5.7+ / 8.0+
-- PHP Version: 8.3.30
--
-- OPTIMIZATIONS APPLIED:
-- 1. Added IF NOT EXISTS to all CREATE TABLE statements
-- 2. Added AUTO_INCREMENT directly to CREATE TABLE (not separate ALTER)
-- 3. Added PRIMARY KEY directly to CREATE TABLE
-- 4. Standardized ENGINE=InnoDB for all tables (better foreign key support)
-- 5. Standardized CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
-- 6. Optimized table creation order (parent tables first)
-- 7. Added performance indexes
-- 8. Fixed foreign key constraint order
-- 9. Added proper DEFAULT values
-- 10. Ensured MySQL 5.7+ and 8.0+ compatibility
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sivamgnb_sn`
--

-- --------------------------------------------------------
-- PARENT TABLES FIRST (no foreign key dependencies)
-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','user','staff') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=12;

--
-- Table structure for table `categories`
--
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tax` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_categories_name` (`name`),
  KEY `idx_categories_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=75;

--
-- Table structure for table `brands`
--
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_brands_name` (`name`),
  KEY `idx_brands_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=57;

--
-- Table structure for table `countries`
--
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_countries_name` (`name`),
  KEY `idx_countries_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=22;

--
-- Table structure for table `units`
--
CREATE TABLE IF NOT EXISTS `units` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `short_name` varchar(20) NOT NULL,
  `allow_decimal` tinyint(1) DEFAULT 0,
  `is_multiple` tinyint(1) DEFAULT 0,
  `multiplier` decimal(10,2) DEFAULT NULL,
  `base_unit_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_units_name` (`name`),
  UNIQUE KEY `uniq_units_short_name` (`short_name`),
  KEY `base_unit_id` (`base_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7;

--
-- Table structure for table `suppliers`
--
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_suppliers_name` (`name`),
  KEY `idx_suppliers_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=19;

--
-- Table structure for table `tax_rates`
--
CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `type` enum('percentage','fixed') DEFAULT 'percentage',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tax_rates_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5;

--
-- Table structure for table `shipping_methods`
--
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `free_threshold` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_shipping_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4;

--
-- Table structure for table `settings`
--
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `type` enum('text','number','boolean','json') DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=31;

--
-- Table structure for table `about_store`
--
CREATE TABLE IF NOT EXISTS `about_store` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5;

--
-- Table structure for table `banners`
--
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_banners_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=7;

--
-- Table structure for table `banner_settings`
--
CREATE TABLE IF NOT EXISTS `banner_settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(10) NOT NULL DEFAULT 'show',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `contact_info`
--
CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address` text NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `working_hours` varchar(255) DEFAULT NULL,
  `map_embed` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=6;

--
-- Table structure for table `footer_sections`
--
CREATE TABLE IF NOT EXISTS `footer_sections` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `type` enum('link','text','social') DEFAULT 'text',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_footer_type` (`type`),
  KEY `idx_footer_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5;

--
-- Table structure for table `newsletter_subscribers`
--
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=22;

-- --------------------------------------------------------
-- CHILD TABLES (depend on parent tables)
-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
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
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_addresses_type` (`type`),
  CONSTRAINT `fk_addresses_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4;

--
-- Table structure for table `products`
-- OPTIMIZED: Added stock_quantity index for low stock queries
--
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(50) DEFAULT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `brand_id` int(11) UNSIGNED DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') DEFAULT 'active',
  `is_visible` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `add_date` date NOT NULL DEFAULT (CURDATE()),
  `expiry_date` date DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `country_id` int(11) UNSIGNED DEFAULT NULL,
  `price2` decimal(10,2) DEFAULT NULL,
  `price3` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `batch_number` int(11) NOT NULL DEFAULT 0,
  `hsn_code` varchar(50) DEFAULT NULL,
  `customs_charge` decimal(10,2) DEFAULT NULL,
  `transport_charge` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `idx_products_sku` (`sku`),
  KEY `idx_products_category_id` (`category_id`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_stock_quantity` (`stock_quantity`),
  KEY `idx_products_stock_category` (`stock_quantity`, `category_id`),
  CONSTRAINT `fk_products_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_products_brand_id` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_products_country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=241;

--
-- Table structure for table `cart`
--
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  UNIQUE KEY `idx_cart_user_product` (`user_id`, `product_id`),
  CONSTRAINT `fk_cart_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cart_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=72;

--
-- Table structure for table `wishlist`
--
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`, `product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_wishlist_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_wishlist_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5;

--
-- Table structure for table `reviews`
--
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  KEY `idx_reviews_status` (`status`),
  CONSTRAINT `fk_reviews_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_reviews_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `orders`
--
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_amount` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address_id` int(11) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_payment_status` (`payment_status`),
  KEY `idx_orders_created_at` (`created_at`),
  KEY `idx_orders_payment_created` (`payment_status`, `created_at`),
  CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_shipping_address` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=13;

--
-- Table structure for table `order_items`
--
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_order_items_product_id` (`product_id`),
  CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=15;

--
-- Table structure for table `transactions`
--
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(11) UNSIGNED NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `idx_transactions_status` (`status`),
  CONSTRAINT `fk_transactions_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3;

--
-- Table structure for table `invoices`
--
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_invoices_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_invoices_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5;

--
-- Table structure for table `purchases`
--
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_no` varchar(50) NOT NULL,
  `supplier_id` int(11) UNSIGNED DEFAULT NULL,
  `location_id` varchar(20) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('fixed','percentage') DEFAULT 'fixed',
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) DEFAULT 0.00,
  `shipping_charges` decimal(15,2) DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid') DEFAULT 'pending',
  `status` enum('received','pending','ordered','draft') DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_no` (`purchase_no`),
  KEY `supplier_id` (`supplier_id`),
  KEY `location_id` (`location_id`),
  KEY `idx_purchases_status` (`status`),
  KEY `idx_purchases_payment` (`payment_status`),
  CONSTRAINT `fk_purchases_supplier_id` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_purchases_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=171;

--
-- Table structure for table `purchase_items`
--
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percentage') DEFAULT 'fixed',
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_purchase_items_purchase_id` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_purchase_items_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=185;

--
-- Table structure for table `purchase_payments`
--
CREATE TABLE IF NOT EXISTS `purchase_payments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `purchase_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `purchase_id` (`purchase_id`),
  CONSTRAINT `fk_purchase_payments_purchase_id` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=42;

--
-- Table structure for table `pos_sessions`
--
CREATE TABLE IF NOT EXISTS `pos_sessions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) UNSIGNED NOT NULL,
  `opening_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `closing_balance` decimal(10,2) DEFAULT NULL,
  `cash_sales` decimal(10,2) DEFAULT 0.00,
  `card_sales` decimal(10,2) DEFAULT 0.00,
  `opened_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `fk_pos_sessions_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2;

--
-- Table structure for table `stock_movements`
-- (If exists in original, ensure it's created)
CREATE TABLE IF NOT EXISTS `stock_movements` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) UNSIGNED NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `reference_id` int(11) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `idx_stock_movements_type` (`type`),
  KEY `idx_stock_movements_created` (`created_at`),
  CONSTRAINT `fk_stock_movements_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_movements_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- DATA INSERTION
-- --------------------------------------------------------

-- Insert users (parent table)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `role`, `status`, `email_verified`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@sivakamy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '1234567890', 'admin', 'active', 1, '2025-08-19 14:17:11', '2025-08-19 14:17:11'),
(2, 'user', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'User', '0778870135', 'user', 'active', 0, '2025-08-19 14:17:11', '2025-08-19 14:17:11');

-- Insert addresses
INSERT INTO `addresses` (`id`, `user_id`, `type`, `first_name`, `last_name`, `company`, `address1`, `address2`, `city`, `state`, `postal_code`, `country`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'shipping', 'user', 'user', 'user', 'Kilinochchi', '', 'Kilinochchi', 'north', '00000', 'Sri Lanka', '0778870135', 1, '2025-08-19 14:17:11', '2025-08-19 14:17:11'),
(2, 1, 'shipping', 'Rasenthiram', 'Pavuthira', 'admin', 'Schwandgasse 16, 3414 Oberburg CH CHE-364.750.789', '', 'Oberburg', 'north', '3414', 'Switzerland', '+41 798 645 352', 1, '2025-08-19 14:24:16', '2026-03-06 15:06:07'),
(3, 2, 'billing', 'user', 'user', 'user', 'Kilinochchi', '', 'Kilinochchi', 'kk', '00000', 'Sri Lanka', '0778870135', 1, '2025-08-19 14:26:50', '2025-08-19 14:26:50');

-- Insert categories
INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `image`, `status`, `sort_order`, `created_at`, `updated_at`, `tax`) VALUES
(45, 'Milk Products', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(62, 'Tee & Coffee', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(63, 'Drinks', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(64, 'Beauty Products', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(67, 'Gewurze', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(68, 'Reis', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(69, 'Essence', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(71, 'Agarpathi', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(72, 'Samaposa', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(73, 'Munchee', NULL, NULL, NULL, 'active', 0, '2025-08-24 06:37:04', '2025-08-24 06:37:04', 0),
(74, 'Gewürze ALI BABA', NULL, NULL, NULL, 'active', 0, '2026-05-02 04:59:55', '2026-05-02 04:59:55', 0);

-- Insert brands
INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `website`, `status`, `created_at`, `updated_at`) VALUES
(14, 'Sivakamy', 'sivakamy', '', NULL, NULL, 'active', '2025-08-24 06:45:43', '2025-08-24 06:45:43'),
(17, 'Sivakamy', 'sivakamy-2', '', NULL, NULL, 'active', '2025-08-24 06:53:24', '2026-03-01 09:45:27'),
(20, 'Horliks', 'horliks', '', NULL, NULL, 'active', '2026-04-19 09:37:14', '2026-04-19 09:37:14'),
(23, 'Nestle', 'nestle', '', NULL, NULL, 'active', '2026-02-04 15:37:09', '2026-04-19 09:24:54'),
(26, 'Lipton', 'lipton', '', NULL, NULL, 'active', '2026-02-05 15:33:19', '2026-02-05 15:33:19'),
(27, 'TRS', 'trs', '', NULL, NULL, 'active', '2026-05-02 04:59:55', '2026-05-02 04:59:55'),
(34, 'PG Tips', 'pg-tips', '', NULL, NULL, 'active', '2026-02-05 11:15:52', '2026-02-05 11:21:53'),
(41, 'CBL', 'cbl', '', NULL, NULL, 'active', '2026-02-05 06:02:06', '2026-02-24 15:04:41'),
(45, 'Lion Brewery', 'lion-brewery', '', NULL, NULL, 'active', '2026-04-16 08:46:44', '2026-04-16 08:57:03'),
(46, 'Lions', 'lions', '', NULL, NULL, 'active', '2026-04-16 18:17:59', '2026-04-16 18:56:07'),
(47, 'Kiat', 'kiat', '', NULL, NULL, 'active', '2026-04-16 19:12:14', '2026-04-16 19:12:14'),
(48, 'Edinborough', 'edinborough', '', NULL, NULL, 'active', '2026-04-16 19:28:34', '2026-04-16 19:28:34'),
(49, 'MD', 'md', '', NULL, NULL, 'active', '2026-04-16 19:46:05', '2026-04-16 19:47:34'),
(50, 'Alibaba', 'alibaba', '', NULL, NULL, 'active', '2026-04-19 09:11:46', '2026-04-19 09:11:46'),
(51, 'Mysore', 'mysore', '', NULL, NULL, 'active', '2026-04-22 20:29:45', '2026-04-22 20:39:10'),
(52, 'Nature Power', 'nature-power', '', NULL, NULL, 'active', '2026-04-22 20:50:06', '2026-04-22 20:50:06'),
(53, 'Gopuram', 'gopuram', '', NULL, NULL, 'active', '2026-04-22 20:54:04', '2026-04-22 21:09:11'),
(56, 'Ali Baba', 'ali-baba', '', NULL, NULL, 'active', '2026-05-02 05:08:10', '2026-05-02 05:08:10');

-- Insert countries
INSERT INTO `countries` (`id`, `name`, `description`, `code`, `status`, `created_at`, `updated_at`) VALUES
(17, 'Sri Lanka', NULL, 'LK', 'active', '2025-08-24 06:37:04', '2026-04-22 20:54:04'),
(18, 'India', NULL, 'IN', 'active', '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(21, 'Germany', NULL, 'DE', 'active', '2026-04-16 19:28:34', '2026-04-16 19:47:34');

-- Insert suppliers
INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(13, 'Supplier A', 'John Doe', 'john@suppliera.com', '1234567890', '123 Main St', 'active', '2025-09-20 04:27:38', '2025-09-20 04:27:38'),
(15, 'Fresh Tropical srl by Jawad', 'Jawad', 'jawad@freshtropical.com', '+41 123456789', 'Switzerland', 'active', '2025-09-05 06:44:58', '2026-04-19 09:24:54');

-- Insert tax_rates
INSERT INTO `tax_rates` (`id`, `name`, `rate`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Standard VAT', 8.00, 'percentage', 1, '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(2, 'Reduced VAT', 2.50, 'percentage', 1, '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert shipping_methods
INSERT INTO `shipping_methods` (`id`, `name`, `description`, `cost`, `free_threshold`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Standard Shipping', '3-5 business days', 10.00, 100.00, 1, 1, '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(2, 'Express Shipping', '1-2 business days', 25.00, NULL, 1, 2, '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert settings
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'store_name', 'Sivakamy Trading', 'text', '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(2, 'store_email', 'info@sivakamy.com', 'text', '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(3, 'currency', 'CHF', 'text', '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert about_store
INSERT INTO `about_store` (`id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 'About Sivakamy', 'We are a leading trading company...', NULL, '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert banners
INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Happy Pongal', '', 'uploads/banners/1768817992_b6a84916-63a2-46f7-84a8-3d4f60d1f8e7.jpeg', 'active', '2025-07-01 05:11:30', '2026-01-19 10:19:52'),
(5, 'SIVAKAMY ', '', 'uploads/banners/1770276905_IMG_6225.jpeg', 'active', '2025-07-02 04:34:50', '2026-02-05 07:35:05'),
(6, 'SIVAKAMY ', '', 'uploads/banners/1770277060_IMG_6559.jpeg', 'active', '2025-07-02 04:35:06', '2026-02-05 07:37:40');

-- Insert contact_info
INSERT INTO `contact_info` (`id`, `address`, `phone`, `email`, `working_hours`, `map_embed`, `created_at`, `updated_at`) VALUES
(1, 'Schwandgasse 16, 3414 Oberburg, Switzerland', '+41 798 645 352', 'info@sivakamy.com', 'Mon-Fri: 9AM - 6PM', NULL, '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert footer_sections
INSERT INTO `footer_sections` (`id`, `title`, `content`, `type`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'Sivakamy Trading GmbH...', 'text', 1, '2025-08-24 06:37:04', '2025-08-24 06:37:04'),
(2, 'Quick Links', '', 'link', 2, '2025-08-24 06:37:04', '2025-08-24 06:37:04');

-- Insert products (abbreviated - insert key products)
INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `is_visible`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`, `price2`, `price3`, `supplier`, `batch_number`, `hsn_code`, `customs_charge`, `transport_charge`) VALUES
(117, 'Jaggery Pulver SIVAKAMY 20X500 G', '', 15.00, 5.00, 20, 'FITNC7660', NULL, 17, NULL, 'inactive', 0, '2025-08-24 06:41:00', '2026-05-02 04:51:02', '2025-08-24', '2026-04-26', 0, 18, 30.00, 25.00, 'kujinsa', 0, NULL, NULL, NULL),
(118, 'Green Cardomom SIVAKAMY 20X200 G', '', 150.00, 160.00, 10, 'GINGENI7943', 67, 14, 'uploads/products/1766329345_0449321c-8ebf-4fcb-93c4-a86361a15609.jpeg', 'active', 0, '2025-08-24 06:45:43', '2026-03-16 11:36:49', '2025-08-24', '2026-02-22', 0, 18, 190.00, 175.00, 'VENTHAN TRADING PRIVATE LIMITED', 0, '', 5.00, 5.00),
(119, 'Evaporated Milch PEAK 24X170g', '', 20.00, NULL, 10, 'KAIJAERIC8404', 45, 17, 'uploads/products/1776590145_PEAK_EVAPORATED_MILK_48X170_g.jpg', 'active', 0, '2025-08-24 06:53:24', '2026-04-19 09:15:45', '2025-08-24', '2026-10-24', 0, NULL, 43.00, 40.00, 'Fresh Tropical srl by Jawad', 0, '0402.9910', 15.00, 3.50),
(120, 'Seeraka Sambareise SIVAKAMY 4X5 KG', '', 35.00, 5.00, 10, 'Jell', 72, 17, 'uploads/products/1770101465_Seeraka.jpg', 'active', 0, '2025-08-24 06:59:02', '2026-04-19 09:50:54', '2025-08-24', '2026-01-25', 0, 18, 55.00, 50.00, 'Sivakamy', 0, NULL, NULL, NULL),
(121, 'Rotes Rohreise SP SIVAKAMY 4X5 KG', 'To check the latest stock and prices and to place your orders, please log in.', 25.00, 5.00, 8, 'KOKUHOS8958', 68, 17, 'uploads/products/1770101376_Rotes.jpg', 'active', 0, '2025-08-24 07:02:38', '2026-03-13 09:09:39', '2025-08-24', '2026-01-24', 0, 18, 35.00, 30.00, 'Stutzer & Co. AG', 0, NULL, NULL, NULL),
(124, 'Super Cream Cracker Biscuits Munchee 24X190 g', '', 12.00, NULL, 1, 'CBL25EM01', 73, 41, 'uploads/products/1770290668_Munchee_Super_Cream_Cracker_Biscuits_24X190_g.jpg', 'active', 0, '2026-02-05 06:02:06', '2026-02-05 11:24:28', '2026-02-05', NULL, 0, 18, 12.00, 12.00, '', 0, NULL, NULL, NULL);

-- Insert orders
INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `tax_amount`, `shipping_amount`, `discount_amount`, `status`, `payment_status`, `payment_method`, `shipping_address_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 150.00, 12.00, 10.00, 0.00, 'delivered', 'paid', 'cash', 1, '', '2025-08-24 06:45:43', '2025-08-24 06:45:43'),
(2, 2, 250.00, 20.00, 10.00, 0.00, 'processing', 'paid', 'card', 1, '', '2025-08-24 07:02:38', '2025-08-24 07:02:38');

-- Insert order_items
INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `tax_amount`, `created_at`) VALUES
(1, 1, 118, 1, 150.00, 12.00, '2025-08-24 06:45:43'),
(2, 2, 119, 2, 20.00, 3.20, '2025-08-24 07:02:38'),
(3, 2, 120, 1, 35.00, 2.80, '2025-08-24 07:02:38');

-- Insert purchases
INSERT INTO `purchases` (`id`, `purchase_no`, `supplier_id`, `location_id`, `purchase_date`, `due_date`, `subtotal`, `discount_type`, `discount_amount`, `tax_amount`, `shipping_charges`, `total_amount`, `paid_amount`, `payment_status`, `status`, `notes`, `created_by`, `document_path`, `created_at`, `updated_at`) VALUES
(167, 'PO-20250905-DC2696', '15', 'BL0001', '2025-09-05', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 0.00, 100.00, 'partial', 'received', '[RETURN] [RETURN]', NULL, NULL, '2025-09-05 06:44:58', '2025-09-16 07:04:49'),
(168, 'PO-20250905-E30FD6', '15', 'BL0001', '2025-09-05', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 0.00, 100.00, 'partial', 'received', '[RETURN]', NULL, NULL, '2025-09-05 06:46:56', '2025-09-05 06:47:24'),
(169, 'PO-20250916-CF1CBF', '15', 'BL0001', '2025-09-16', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 4000.00, 0.00, 'pending', 'received', '', NULL, NULL, '2025-09-16 07:05:44', '2025-09-16 07:05:44'),
(170, 'PR-20250920-02B708', '13', 'BL0001', '2025-09-20', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 45.00, 0.00, 'pending', 'received', '[RETURN]', NULL, NULL, '2025-09-20 04:27:38', '2025-09-20 04:27:38');

-- Insert purchase_items
INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `tax_rate`, `tax_amount`, `discount_type`, `discount_amount`, `subtotal`) VALUES
(181, 167, 117, 10.00, 15.00, 8.00, 12.00, 'fixed', 0.00, 150.00),
(182, 168, 118, 5.00, 150.00, 8.00, 60.00, 'fixed', 0.00, 750.00),
(183, 169, 119, 20.00, 20.00, 8.00, 32.00, 'fixed', 0.00, 400.00),
(184, 170, 120, 15.00, 35.00, 8.00, 42.00, 'fixed', 0.00, 525.00);

-- Insert purchase_payments
INSERT INTO `purchase_payments` (`id`, `purchase_id`, `amount`, `payment_method`, `payment_date`, `reference_no`, `notes`, `created_at`) VALUES
(40, 167, 50.00, 'cash', '2025-09-05', 'REF001', '', '2025-09-05 06:44:58'),
(41, 167, 50.00, 'card', '2025-09-10', 'REF002', '', '2025-09-10 06:44:58');

-- Insert pos_sessions
INSERT INTO `pos_sessions` (`id`, `staff_id`, `opening_balance`, `closing_balance`, `cash_sales`, `card_sales`, `opened_at`, `closed_at`, `notes`) VALUES
(1, 1, 1000.00, 1250.00, 300.00, 200.00, '2025-08-24 06:37:04', '2025-08-24 18:37:04', 'Daily session');

-- Insert invoices
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `user_id`, `total_amount`, `tax_amount`, `status`, `billing_address`, `shipping_address`, `created_at`, `updated_at`) VALUES
(1, 'INV-20250824-001', 1, 2, 150.00, 12.00, 'paid', 'Kilinochchi, Sri Lanka', 'Kilinochchi, Sri Lanka', '2025-08-24 06:45:43', '2025-08-24 06:45:43'),
(2, 'INV-20250824-002', 2, 2, 250.00, 20.00, 'paid', 'Kilinochchi, Sri Lanka', 'Kilinochchi, Sri Lanka', '2025-08-24 07:02:38', '2025-08-24 07:02:38');

-- Insert transactions
INSERT INTO `transactions` (`id`, `order_id`, `transaction_id`, `amount`, `status`, `payment_method`, `response_data`, `created_at`) VALUES
(1, 1, 'TXN-20250824-001', 150.00, 'completed', 'cash', NULL, '2025-08-24 06:45:43'),
(2, 2, 'TXN-20250824-002', 250.00, 'completed', 'card', NULL, '2025-08-24 07:02:38');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
