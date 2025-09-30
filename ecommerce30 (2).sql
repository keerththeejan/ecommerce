-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 12, 2025 at 09:20 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce30`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_store`
--

DROP TABLE IF EXISTS `about_store`;
CREATE TABLE IF NOT EXISTS `about_store` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_store`
--

INSERT INTO `about_store` (`id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(3, 'About store', 'Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.', NULL, '2025-08-10 07:55:48', '2025-08-10 15:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('billing','shipping') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(3, 'k', '02', 'uploads/banners/1751346690_11.jpeg', 'active', '2025-07-01 05:11:30', '2025-07-01 05:11:30'),
(4, 'vv', '30', 'uploads/banners/1751430837_25.jpg', 'active', '2025-07-02 04:33:57', '2025-07-02 04:33:57'),
(5, 'pi', '02', 'uploads/banners/1751430890_55.jpeg', 'active', '2025-07-02 04:34:50', '2025-07-02 04:34:50'),
(6, 'th', '05', 'uploads/banners/1751430906_33.jpeg', 'active', '2025-07-02 04:35:06', '2025-07-02 04:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `banner_settings`
--

DROP TABLE IF EXISTS `banner_settings`;
CREATE TABLE IF NOT EXISTS `banner_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'show',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(12, 'Jaisal', 'jaisal', '', 'uploads/brands/682f4226d1c6d.jpg', 'active', '2025-05-18 05:46:22', '2025-05-24 07:03:00'),
(13, 'Ameenah', 'ameenah', '', 'uploads/brands/brand_688cbf88449e78.51236649.png', 'active', '2025-05-22 15:27:25', '2025-08-01 13:22:16'),
(14, 'cool', 'cool', '', 'uploads/brands/68832aed89e6b.jpg', 'active', '2025-07-25 06:57:49', '2025-07-25 06:57:49'),
(15, 'drink', 'drink', '', 'uploads/brands/688340898ebd4.png', 'active', '2025-07-25 08:30:01', '2025-07-25 08:30:01'),
(16, 'sweet', 'sweet', '', 'uploads/brands/68834097ee63f.png', 'active', '2025-07-25 08:30:15', '2025-07-25 08:30:15'),
(17, 'food', 'food', '', 'uploads/brands/688340ab7ebb7.png', 'active', '2025-07-25 08:30:35', '2025-07-25 08:30:35'),
(20, 'moon', 'moon', '', 'uploads/brands/brand_68962ce0ee19e2.76953341.jpg', 'active', '2025-08-08 16:55:47', '2025-08-08 16:59:12');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `tax_id` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tax` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `image`, `parent_id`, `tax_id`, `status`, `created_at`, `updated_at`, `tax`) VALUES
(40, 'hwh', '5', NULL, 'uploads/categories/689a012dabc5c.png', NULL, 2, 1, '2025-08-11 14:41:49', '2025-08-12 04:59:28', 0),
(41, 'jsksk', '', NULL, 'uploads/categories/689abb87d1883.png', 40, 3, 1, '2025-08-12 03:56:55', '2025-08-12 04:58:33', 0),
(42, 'shhs', NULL, NULL, 'uploads/categories/689abcc20e384.png', 40, 3, 1, '2025-08-12 04:02:10', '2025-08-12 04:02:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `code` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `flag_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `description`, `code`, `flag_image`, `status`, `created_at`, `updated_at`) VALUES
(17, 'china', NULL, 'CH', 'flag_1753981933_688ba3eda73e3.png', 'active', '2025-07-31 17:12:13', '2025-07-31 17:12:13'),
(16, 'Korea', NULL, 'KO', 'flag_1753981908_688ba3d43005f.png', 'active', '2025-07-31 17:11:48', '2025-07-31 17:11:48'),
(18, 'india', NULL, 'IN', 'flag_1753983494_688baa06f33a4.png', 'active', '2025-07-31 17:38:14', '2025-07-31 17:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `billing_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `shipping_fee` decimal(10,2) DEFAULT '0.00',
  `tax` decimal(10,2) DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_sessions`
--

DROP TABLE IF EXISTS `pos_sessions`;
CREATE TABLE IF NOT EXISTS `pos_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `staff_id` int NOT NULL,
  `opening_balance` decimal(10,2) NOT NULL,
  `closing_balance` decimal(10,2) DEFAULT NULL,
  `status` enum('open','closed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'open',
  `opened_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_sessions`
--

INSERT INTO `pos_sessions` (`id`, `staff_id`, `opening_balance`, `closing_balance`, `status`, `opened_at`, `closed_at`, `notes`) VALUES
(1, 1, 2.00, NULL, 'open', '2025-04-26 05:58:29', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `sku` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `is_visible` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `add_date` date NOT NULL DEFAULT (curdate()),
  `expiry_date` date DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `country_id` int DEFAULT NULL,
  `price2` decimal(10,2) DEFAULT NULL,
  `price3` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `batch_number` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `fk_products_brands` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `is_visible`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`, `price2`, `price3`, `supplier`, `batch_number`) VALUES
(115, 'Ashwiny', '58', 88.00, 88.00, 5, '55', 41, NULL, 'uploads/products/1754982260_1748023140_chin_aloe_vera-removebg-preview.png', 'active', 0, '2025-08-12 07:04:20', '2025-08-12 07:54:31', '2025-08-12', '2025-08-05', 0, NULL, 59.00, 59.00, 'kujinsa', 5),
(116, 'Ashwinyhh', '85', 75.00, 85.00, 2, '52', 42, NULL, 'uploads/products/1754982315_1748023546_tang_orange_2.5kg-removebg-preview (1).png', 'active', 0, '2025-08-12 07:05:15', '2025-08-12 07:54:01', '2025-08-12', '2025-08-23', 0, NULL, 78.00, 45.00, 'pirathi', 52),
(113, 'kujinsha', '5', 65.00, 85.00, 2, '254', 41, NULL, 'uploads/products/1754981643_1748021992_60-removebg-preview.png', 'active', 0, '2025-08-12 06:54:03', '2025-08-12 06:54:03', '2025-08-12', '2025-08-23', 0, NULL, 59.00, 55.00, 'kujinsa', 52),
(114, 'keethan', '85', 89.00, 89.00, 2, '524', 41, NULL, 'uploads/products/1754981916_1748025672_8-removebg-preview (1).png', 'active', 0, '2025-08-12 06:58:36', '2025-08-12 06:58:36', '2025-08-12', '2025-08-23', 0, NULL, 87.00, 86.00, 'kujinsa', 26);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'store_name', 'Sivakamy', 'general', '2025-04-25 07:59:26', '2025-05-18 09:26:29'),
(2, 'store_email', 'info@example.com', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(3, 'store_phone', '+1234567890', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(4, 'store_address', '123 Main St, City, Country', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(5, 'currency_symbol', '$', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(6, 'site_name', 'Sivakamy', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(7, 'site_description', '', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(8, 'site_email', 'Sivakamy@gmail.com', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(9, 'site_phone', '', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(10, 'site_address', '', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(11, 'site_logo', '6829a015abaff.jpg', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(12, 'site_favicon', '', 'general', '2025-04-26 05:02:09', '2025-05-18 09:26:29'),
(13, 'store_tagline', '', 'general', '2025-04-26 05:02:44', '2025-04-26 05:02:56'),
(14, 'store_logo', 'uploads/logo_1745643764.jpg', 'general', '2025-04-26 05:02:44', '2025-04-26 05:02:44'),
(15, 'store_currency', 'Swiss Franc', 'general', '2025-05-11 08:46:12', '2025-05-18 09:26:26'),
(16, 'store_currency_symbol', 'CHF', 'general', '2025-05-11 08:46:12', '2025-05-18 09:26:26'),
(17, 'store_tax_type_1_name', 'GST', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(18, 'store_tax_type_1_rate', '18', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(19, 'store_tax_type_1_applies_to', 'all', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(20, 'store_tax_type_2_name', 'Service Tax', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(21, 'store_tax_type_2_rate', '5', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(22, 'store_tax_type_2_applies_to', 'all', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(23, 'store_tax_type_3_name', 'Custom Tax', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(24, 'store_tax_type_3_rate', '12', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(25, 'store_tax_type_3_applies_to', 'all', 'general', '2025-05-11 08:46:12', '2025-05-11 08:46:12'),
(26, 'store_shipping_flat_rate', '100', 'general', '2025-05-11 08:46:12', '2025-05-18 09:26:26'),
(27, 'store_free_shipping_threshold', '1000', 'general', '2025-05-11 08:46:12', '2025-05-18 09:26:26'),
(28, 'store_inventory_management', '0', 'general', '2025-05-11 08:46:12', '2025-05-18 09:26:26'),
(29, 'store_tax_rate', '18', 'general', '2025-05-17 17:18:19', '2025-05-18 09:26:26'),
(30, 'store_low_stock_threshold', '5', 'general', '2025-05-17 17:18:19', '2025-05-18 09:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
CREATE TABLE IF NOT EXISTS `shipping_methods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_weight_based` tinyint(1) NOT NULL DEFAULT '0',
  `free_weight_threshold` decimal(10,2) DEFAULT NULL,
  `weight_step` decimal(10,2) DEFAULT NULL,
  `price_per_step` decimal(10,2) DEFAULT NULL,
  `free_shipping_threshold` decimal(10,2) DEFAULT NULL,
  `estimated_delivery` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `base_price`, `is_weight_based`, `free_weight_threshold`, `weight_step`, `price_per_step`, `free_shipping_threshold`, `estimated_delivery`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Standard Shipping', 'Standard delivery within 3-5 business days', 5.99, 1, 5.00, 1.00, 0.50, 50.00, '3-5 business days', 1, 1, '2025-05-18 07:45:23', '2025-05-18 07:45:23'),
(2, 'Express Shipping', 'Faster delivery within 1-2 business days', 12.99, 1, 5.00, 1.00, 1.00, 100.00, '1-2 business days', 1, 2, '2025-05-18 07:45:23', '2025-05-18 07:45:23'),
(3, 'Free Shipping', 'Free standard shipping', 0.00, 0, NULL, NULL, NULL, 50.00, '5-7 business days', 1, 3, '2025-05-18 07:45:23', '2025-05-18 07:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `product_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(12, 'kujinsa', 'gee', 'kujin@gmail.com', '0772581496', 'nj', '2025-08-10 13:49:48', '2025-08-10 13:49:48'),
(13, 'kujinsa', 'gee', 'pirai@gmail.com', '0762589632', 'gyg', '2025-08-10 13:52:39', '2025-08-10 13:52:39'),
(14, 'mathu', 'bis', 'pirai@gmail.com', '0772581496', 'ghghf', '2025-08-10 13:57:07', '2025-08-10 13:57:07'),
(15, 'pirathi', 'juice', 'pirai@gmail.com', '0772581496', 'ghj', '2025-08-10 14:22:43', '2025-08-10 14:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

DROP TABLE IF EXISTS `tax_rates`;
CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `name`, `rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Standard Tax', 0.00, 1, '2025-08-10 15:20:07', '2025-08-10 15:20:07'),
(2, 'kujinsha', 0.02, 1, '2025-08-12 03:29:58', '2025-08-12 03:29:58'),
(3, 'keethan', 0.02, 1, '2025-08-12 03:30:11', '2025-08-12 03:30:11'),
(4, 'jsjs', 0.02, 1, '2025-08-12 04:56:50', '2025-08-12 04:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','customer','staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', '2025-04-22 06:49:56', '2025-04-22 06:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 1, 68, '2025-05-27 13:03:52'),
(2, 1, 67, '2025-05-27 14:50:44'),
(3, 1, 66, '2025-05-27 14:51:27'),
(4, 1, 62, '2025-05-27 14:52:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
