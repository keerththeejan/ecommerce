-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 30, 2025 at 11:55 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerceq`
--

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
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_url` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(10) NOT NULL DEFAULT 'show',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_name` (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(12, 'Jaisal', 'jaisal', '', 'uploads/brands/682f4226d1c6d.jpg', 'active', '2025-05-18 05:46:22', '2025-05-24 07:03:00'),
(13, 'Ameenah', 'ameenah', '', 'uploads/brands/6883484cce68e.png', 'active', '2025-05-22 15:27:25', '2025-07-25 09:03:42'),
(14, 'cool', 'cool', '', 'uploads/brands/68832aed89e6b.jpg', 'active', '2025-07-25 06:57:49', '2025-07-25 06:57:49'),
(15, 'drink', 'drink', '', 'uploads/brands/688340898ebd4.png', 'active', '2025-07-25 08:30:01', '2025-07-25 08:30:01'),
(16, 'sweet', 'sweet', '', 'uploads/brands/68834097ee63f.png', 'active', '2025-07-25 08:30:15', '2025-07-25 08:30:15'),
(17, 'food', 'food', '', 'uploads/brands/688340ab7ebb7.png', 'active', '2025-07-25 08:30:35', '2025-07-25 08:30:35');

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
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `image`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(12, 'Alkohol Getränke', '', NULL, 'uploads/categories/683c206958639.webp', NULL, 1, '2025-05-17 14:34:35', '2025-06-01 09:42:01'),
(19, 'Bohnen - Linsen', '', NULL, 'uploads/categories/683c208fd6240.webp', NULL, 1, '2025-05-22 12:59:58', '2025-06-01 09:42:39'),
(20, 'Fleisch and Fisch', '', NULL, 'uploads/categories/687f46786118d.webp', NULL, 1, '2025-05-22 13:16:08', '2025-07-22 08:06:16'),
(21, 'Früchte and Gemüse', '', NULL, 'uploads/categories/687f53bc28002.webp', NULL, 1, '2025-05-22 13:26:09', '2025-07-22 09:02:52'),
(22, 'Getränke', '', NULL, 'uploads/categories/687f53dfbd527.webp', NULL, 1, '2025-05-22 13:30:48', '2025-07-22 09:03:27'),
(24, 'Gewürze', '', NULL, 'uploads/categories/687f53f24111c.webp', NULL, 1, '2025-05-22 13:43:46', '2025-07-22 09:03:46'),
(26, 'Kaffee and Tee', '', NULL, 'uploads/categories/687f540f45483.webp', NULL, 1, '2025-05-22 13:51:13', '2025-07-22 09:04:15'),
(27, 'Kokosnuss Produkten', '', NULL, 'uploads/categories/687f543fead0b.webp', NULL, 1, '2025-05-22 13:54:24', '2025-07-22 09:05:03'),
(28, 'Kosmetik', '', NULL, 'uploads/categories/687f54708d3c8.webp', NULL, 1, '2025-05-22 13:58:48', '2025-07-22 09:05:52'),
(29, 'Kräuter - Gewürze', '', NULL, 'uploads/categories/687f548e719e9.webp', NULL, 1, '2025-05-22 14:01:38', '2025-07-22 09:06:22'),
(30, 'Linsen', '', NULL, 'uploads/categories/687f54cc13c50.webp', NULL, 1, '2025-05-22 14:04:36', '2025-07-22 09:07:24'),
(31, 'Milchprodukte', '', NULL, 'uploads/categories/687f5549b4325.png', NULL, 1, '2025-05-22 14:06:16', '2025-07-22 09:09:29'),
(32, 'Noodle - Instant', '', NULL, 'uploads/categories/687f5561107f1.png', NULL, 1, '2025-05-22 14:08:25', '2025-07-22 09:09:53'),
(33, 'Öle - Butter', '', NULL, 'uploads/categories/687f5584ce47e.png', NULL, 1, '2025-05-22 14:10:21', '2025-07-22 09:10:28'),
(34, 'Reis - Mehl', '', NULL, 'uploads/categories/687f55ac53074.png', NULL, 1, '2025-05-22 14:14:51', '2025-07-22 09:11:08'),
(35, 'Sause - Paste', '', NULL, 'uploads/categories/688078abc8424.webp', NULL, 1, '2025-05-22 14:16:50', '2025-07-23 05:52:43'),
(36, 'Süssigkeiten', '', NULL, 'uploads/categories/688078f132e3a.webp', NULL, 1, '2025-05-22 14:19:19', '2025-07-23 05:53:53'),
(37, 'Tiefkühlprodukte', '', NULL, 'uploads/categories/6880790b3cc50.webp', NULL, 1, '2025-05-22 14:21:56', '2025-07-23 05:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tes', 'ss', 'active', '2025-04-25 04:18:25', '2025-04-25 04:18:25'),
(2, 'mmmm', '', 'active', '2025-07-28 12:16:27', '2025-07-28 12:16:27'),
(3, 'kkkkkkkkkkk', '', 'active', '2025-07-28 12:44:32', '2025-07-28 12:44:32'),
(4, 'coun', '', 'active', '2025-07-28 13:52:31', '2025-07-28 13:52:31');

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
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `add_date` date NOT NULL DEFAULT (curdate()),
  `expiry_date` date DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `country_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  KEY `category_id` (`category_id`),
  KEY `fk_products_brands` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`) VALUES
(12, 'Lion Lager – Erfrischendes Lager aus Sri Lanka mit 6 x 0,5 L 4,8% Vol', '', 1.00, 0.02, 2, '3044627', 12, NULL, 'uploads/products/1748002021_lion.jpg', 'active', '2025-05-22 15:45:12', '2025-05-23 12:07:01', '2025-05-24', NULL, 0, NULL),
(14, 'Griechisches Bier Mythos Lagerbier 24x 0,33 Liter inkl. Pfand', '', 1.00, 1.00, 3, '5583371', 12, NULL, 'uploads/products/1748002394_2.jpg', 'active', '2025-05-23 12:12:48', '2025-05-23 12:13:14', '2025-05-24', NULL, 0, NULL),
(15, '2,5 Original Radler Naturtrüb (24 x 0,5L)', '', 1.00, 1.00, 2, '1919592', 12, NULL, 'uploads/products/1748002668_3.jpg', 'active', '2025-05-23 12:17:48', '2025-05-23 12:17:48', '2025-05-24', NULL, 0, NULL),
(16, '20 x 0,50L Schlenkerla Rauchbier Märzen - inkl Biergartendeckel', '', 1.00, 1.00, 2, '7907892', 12, NULL, 'uploads/products/1748002872_4.jpg', 'active', '2025-05-23 12:21:12', '2025-05-23 12:21:12', '2025-05-24', NULL, 0, NULL),
(17, 'Gaffel Kölsch Bierfass (2 x 5,0L)', '', 1.00, 1.00, 2, '7602179', 12, NULL, 'uploads/products/1748003781_5.jpg', 'active', '2025-05-23 12:32:44', '2025-05-23 12:36:21', '2025-05-24', NULL, 0, NULL),
(19, 'Pantio - Bunte Hülsenfrüchte-Mischung Linsen &amp; Bohnen - 500g', '', 1.00, 1.00, 2, '24455', 19, NULL, 'uploads/products/1748004833_7.jpg', 'active', '2025-05-23 12:40:24', '2025-05-23 12:53:53', '2025-05-24', NULL, 0, NULL),
(20, 'EDEKA BIO LINSEN 400 g Dose 6er Pack (400g x 6)', '', 1.00, 1.00, 2, '24554', 19, NULL, 'uploads/products/1748004903_8.jpg', 'active', '2025-05-23 12:55:03', '2025-05-23 12:55:03', '2025-05-24', NULL, 0, NULL),
(21, 'Hülsenfrucht Dünger für Erbsen Bohnen Linsen Erdnüsse NPK Volldünger', '', 1.00, 1.00, 2, '4887', 19, NULL, 'uploads/products/1748005392_9.jpg', 'active', '2025-05-23 13:03:12', '2025-05-23 13:03:12', '2025-05-24', NULL, 0, NULL),
(22, 'Asian Home Gourmet Würzpaste für Indisches Butter Chicken 50g', '', 1.00, 1.00, 2, '5666', 29, NULL, 'uploads/products/1748005531_10.jpg', 'active', '2025-05-23 13:05:31', '2025-05-23 13:05:31', '2025-05-24', NULL, 0, NULL),
(23, '10 x50g OSUPAN Ayurveda Gewürz Ceylon Tee 5 Kräuter Sri Lanka Versand aus Deutschland', '', 1.00, 1.00, 2, '5236', 29, NULL, 'uploads/products/1748005686_12.jpg', 'active', '2025-05-23 13:08:06', '2025-05-23 13:08:06', '2025-05-24', NULL, 0, NULL),
(24, 'Kräuter der Provence Gewürz Kräuter Mischung - 100g - PEnandiTRA', '', 1.00, 1.00, 2, '24555', 29, NULL, 'uploads/products/1748005862_14.jpg', 'active', '2025-05-23 13:11:03', '2025-05-23 13:11:03', '2025-05-24', NULL, 0, NULL),
(25, 'Campina Nr. - Mark Brandenburg - Milchprodukte - MAN - Sattelzug', '', 1.00, 1.00, 2, '5445', 31, NULL, 'uploads/products/1748006029_21.jpg', 'active', '2025-05-23 13:13:49', '2025-05-23 13:13:49', '2025-05-24', NULL, 0, NULL),
(26, 'Looney Tunes Active! Bild Nr. 32 Milchprodukte PENNY: Die total verrückte Sportarena Startpreis', '', 1.00, 1.00, 2, '5897', 31, NULL, 'uploads/products/1748006179_22.jpg', 'active', '2025-05-23 13:16:19', '2025-05-23 13:16:19', '2025-05-24', NULL, 0, NULL),
(27, 'Landana 1000 Tage Käse Gouda uralt Bröckelkäse mit Salzkristallen 1kg', '', 1.00, 1.00, 2, '5558', 31, NULL, 'uploads/products/1748006311_23.jpg', 'active', '2025-05-23 13:18:31', '2025-05-23 13:18:31', '2025-05-24', NULL, 0, NULL),
(28, '20x 7 Days Double Croissant Kakao-Vanille a 60g', '', 1.00, 1.00, 5, '265', 31, NULL, 'uploads/products/1748006485_24.jpg', 'active', '2025-05-23 13:21:25', '2025-05-23 13:21:25', '2025-05-24', NULL, 0, NULL),
(29, 'Arla Gräddost Natur 1KG Butterkäse', '', 1.00, 1.00, 2, '5988', 31, NULL, 'uploads/products/1748006601_25.jpg', 'active', '2025-05-23 13:23:21', '2025-05-23 13:23:21', '2025-05-24', NULL, 0, NULL),
(30, 'Noodle - Instant', '', 1.00, 1.00, 5, '6526', 32, NULL, 'uploads/products/1748006799_31.jpg', 'active', '2025-05-23 13:26:39', '2025-05-23 13:26:39', '2025-05-24', NULL, 0, NULL),
(31, 'Nissin Bag Noodles Soba Teriyaki Wok Style Instant Nudelgericht 110g', '', 1.00, 1.00, 5, '5456', 32, NULL, 'uploads/products/1748006902_32.jpg', 'active', '2025-05-23 13:28:23', '2025-05-23 13:28:23', '2025-05-24', NULL, 0, NULL),
(32, 'Nissin Bag Noodles Soba Classic Wok Style Instant Nudelgericht 109g', '', 1.00, 1.00, 2, '54878', 32, NULL, 'uploads/products/1748007229_33.jpg', 'active', '2025-05-23 13:32:57', '2025-05-23 13:33:49', '2025-05-24', NULL, 0, NULL),
(33, 'MAGGI Magic Asia Noodle Cup Duck Instant-Nudeln Fertiggericht Becher 8 x 63g', '', 1.00, 1.00, 2, '5458', 32, NULL, 'uploads/products/1748007364_34.jpg', 'active', '2025-05-23 13:36:04', '2025-05-23 13:36:04', '2025-05-24', NULL, 0, NULL),
(34, 'Knorr Instant Nudeln Asia Noodles Nudelgericht Rind Huhn Ente 24er Pack 24 x 65g', '', 1.00, 1.00, 5, '454', 32, NULL, 'uploads/products/1748007542_35.jpg', 'active', '2025-05-23 13:39:02', '2025-05-23 13:39:02', '2025-05-24', NULL, 0, NULL),
(35, 'Naturkornmühle Werz Reis Vollkorn Mehl, glutenfrei 1000g', '', 1.00, 1.00, 5, '6656', 34, NULL, 'uploads/products/1748007693_46.jpg', 'active', '2025-05-23 13:41:33', '2025-05-23 13:41:33', '2025-05-24', NULL, 0, NULL),
(36, 'Müllers Mühle Reis Mehl zum Kochen Backen und Verfeinern 500g', '', 1.00, 1.00, 2, '456', 34, NULL, 'uploads/products/1748007832_47.jpg', 'active', '2025-05-23 13:43:53', '2025-05-23 13:43:53', '2025-05-24', NULL, 0, NULL),
(37, 'Naturkornmühle Werz 3x Reis Vollkorn Mehl, glutenfrei 1000g', '', 1.00, 1.00, 5, '2466', 34, NULL, 'uploads/products/1748007958_48.jpg', 'active', '2025-05-23 13:45:58', '2025-05-23 13:45:58', '2025-05-24', NULL, 0, NULL),
(38, 'Naturkornmühle Werz 6x Reis Vollkorn Mehl, glutenfrei 1000g', '', 1.00, 1.00, 5, '45455', 34, NULL, 'uploads/products/1748008073_49.jpg', 'active', '2025-05-23 13:47:53', '2025-05-23 13:47:53', '2025-05-24', NULL, 0, NULL),
(39, 'Sauce / Paste - Adjika traditionell, pastöse Würzsauce - 220 gr. - Georgien', '', 1.00, 1.00, 2, '244544', 35, NULL, 'uploads/products/1748008251_50.jpg', 'active', '2025-05-23 13:49:25', '2025-05-23 13:50:51', '2025-05-24', NULL, 0, NULL),
(40, 'WELA - Sauce Hollandaise Paste 810 g', '', 1.00, 1.00, 5, '2553', 35, NULL, 'uploads/products/1748021445_51.jpg', 'active', '2025-05-23 17:30:45', '2025-05-23 17:30:45', '2025-05-24', NULL, 0, NULL),
(41, 'Jürgen Langbein Krebs Paste für Suppen und Saucen 50g 10er Pack', '', 1.00, 1.00, 3, '656', 35, NULL, 'uploads/products/1748021559_52.jpg', 'active', '2025-05-23 17:32:39', '2025-05-23 17:32:39', '2025-05-24', NULL, 0, NULL),
(42, 'Jürgen Langbein Gourmet Hummer Paste Suppen und Saucen 500g 3er Pack', '', 1.00, 1.00, 2, '323', 35, NULL, 'uploads/products/1748021691_53.jpg', 'active', '2025-05-23 17:34:51', '2025-05-23 17:34:51', '2025-05-24', NULL, 0, NULL),
(43, 'Ungarn - Paprikapaste süß - 210 gr. Glas', '', 1.00, 1.00, 1, '5866', 35, NULL, 'uploads/products/1748021838_54.jpg', 'active', '2025-05-23 17:37:18', '2025-05-23 17:37:18', '2025-05-24', NULL, 0, NULL),
(44, 'Vaya Sweet Potato Rosemary Snack Vegan, 75 Gramm Beute 5 Varianten', '', 1.00, 1.00, 5, '6556', 36, NULL, 'uploads/products/1748021992_60.jpg', 'active', '2025-05-23 17:39:57', '2025-05-23 17:39:57', '2025-05-24', NULL, 0, NULL),
(45, 'Doritos Tortilla Chips Sweet Chili Pepper - Mais Snack - 12x110 g', '', 1.00, 1.00, 6, '5986', 36, NULL, 'uploads/products/1748022141_61.jpg', 'active', '2025-05-23 17:42:21', '2025-05-23 17:42:21', '2025-05-24', NULL, 0, NULL),
(46, 'Rob&#039;s Sweet BBQ 120g', '', 1.00, 1.00, 2, '2442', 36, NULL, 'uploads/products/1748022238_62.jpg', 'active', '2025-05-23 17:43:58', '2025-05-23 17:43:58', '2025-05-24', NULL, 0, NULL),
(47, 'Marshmallows BBQ Vanilla Flavour 300g zum Grillen, Snacken, Süssigkeit', '', 1.00, 1.00, 6, '6530', 36, NULL, 'uploads/products/1748022354_63.jpg', 'active', '2025-05-23 17:45:54', '2025-05-23 17:45:54', '2025-05-24', NULL, 0, NULL),
(48, 'Tyrrells Handcooked Sweet Chilli Chips glutenfrei vegan 150g', '', 1.00, 1.00, 5, '3565', 36, NULL, 'uploads/products/1748022534_64.jpg', 'active', '2025-05-23 17:48:54', '2025-05-23 17:48:54', '2025-05-24', NULL, 0, NULL),
(49, 'ALOE VERA DRINK (ALIBABA) 6X1.5L', '', 1.00, 1.00, 10, '635', 22, NULL, 'uploads/products/1748022927_alovera_drink_1.5l.png', 'active', '2025-05-23 17:55:27', '2025-05-23 17:59:41', '2025-05-24', NULL, 0, NULL),
(50, 'ALOE VERA JUICE (CHIN CHIN) 24X500ML', '', 1.00, 1.00, 10, '5564', 22, NULL, 'uploads/products/1748023140_chin_aloe_vera.png', 'active', '2025-05-23 17:59:00', '2025-05-23 17:59:00', '2025-05-24', NULL, 0, NULL),
(51, 'ALOE VERA WITH HONEY (CHIN CHIN) 12X1.5L', '', 1.00, 1.00, 10, '6652', 22, NULL, 'uploads/products/1748023252_chin_aloe_vera_1.5l.png', 'active', '2025-05-23 18:00:52', '2025-05-23 18:00:52', '2025-05-24', NULL, 0, NULL),
(52, 'TANG MANGO JAR 6X2500G', '', 1.00, 1.00, 10, '5466', 22, NULL, 'uploads/products/1748023320_tang_mango_2.5kg.png', 'active', '2025-05-23 18:02:00', '2025-05-23 18:02:00', '2025-05-24', NULL, 0, NULL),
(53, 'TANG ORANGE JAR 6X2500G', '', 1.00, 1.00, 10, '2564', 22, NULL, 'uploads/products/1748023546_tang_orange_2.5kg.png', 'active', '2025-05-23 18:05:46', '2025-05-23 18:05:46', '2025-05-24', NULL, 0, NULL),
(54, 'GINGER POWDER (ALIBABA) 10X400G', '', 1.00, 1.00, 10, '5982', 24, NULL, 'uploads/products/1748023687_alibaba_ginger_powder_400g.png', 'active', '2025-05-23 18:08:07', '2025-05-23 18:08:07', '2025-05-24', NULL, 0, NULL),
(55, 'HALDI POWDER (ALIBABA) 6X1KG', '', 1.00, 1.00, 10, '325', 24, NULL, 'uploads/products/1748023759_alibaba_haldi_powder_1kg.png', 'active', '2025-05-23 18:09:19', '2025-05-23 18:09:19', '2025-05-24', NULL, 0, NULL),
(56, 'PAPRIKA POWDER (ALIBABA) 20X100G', '', 1.00, 1.00, 10, '3566', 24, NULL, 'uploads/products/1748023973_alibaba_parika_powder_100g-600x600.png', 'active', '2025-05-23 18:12:53', '2025-05-23 18:12:53', '2025-05-24', NULL, 0, NULL),
(57, 'METHI SEEDS (ALIBABA) 20X100G', '', 1.00, 1.00, 10, '7895', 24, NULL, 'uploads/products/1748024057_aliababa_methi_seed_100g-600x600.png', 'active', '2025-05-23 18:14:17', '2025-05-23 18:14:17', '2025-05-24', NULL, 0, NULL),
(58, 'SHAN CHAAT MASALA 6X100G', '', 1.00, 1.00, 10, '786', 24, NULL, 'uploads/products/1748024193_shan_chaat_masala.png', 'active', '2025-05-23 18:16:33', '2025-05-23 18:16:33', '2025-05-24', NULL, 0, NULL),
(59, 'Slinmy Kräutertee', '', 1.00, 1.00, 10, '8561', 26, NULL, 'uploads/products/1748024618_21.jpg', 'active', '2025-05-23 18:23:39', '2025-05-23 18:23:39', '2025-05-24', NULL, 0, NULL),
(60, 'FITN╘ TEA GREEN TEA 6X40 G', '', 1.00, 1.00, 10, '2556', 26, NULL, 'uploads/products/1750316940_1748025672_8-removebg-preview.png', 'active', '2025-05-23 18:28:11', '2025-06-19 07:09:00', '2025-05-24', NULL, 0, NULL),
(61, 'FITN╘ TEA ORIGINAL 6X40 G', '', 1.00, 1.00, 10, '3425', 26, NULL, 'uploads/products/1750316922_1748025672_8-removebg-preview.png', 'active', '2025-05-23 18:29:56', '2025-06-19 07:08:42', '2025-05-24', NULL, 0, NULL),
(62, 'GOLD KILI INSTANT GINGER DRINK 24X10x18 g', '', 1.00, 1.00, 10, '569', 26, NULL, 'uploads/products/1750840014_1748023140_chin_aloe_vera-removebg-preview.png', 'active', '2025-05-23 18:31:57', '2025-06-25 08:26:54', '2025-05-24', NULL, 0, NULL),
(63, 'GOLD KILI INSTANT GINGER DRINK 24X20X18g', '', 1.00, 1.00, 10, '5826', 26, NULL, 'uploads/products/1750839540_1748024618_21-removebg-preview__1_.png', 'active', '2025-05-23 18:33:36', '2025-06-25 08:19:00', '2025-05-24', NULL, 0, NULL),
(64, 'MAGGI BOUILLON CUBES 25X100X4 G', '', 1.00, 1.00, 10, '25456', 33, NULL, 'uploads/products/1750839089_1748023546_tang_orange_2.5kg-removebg-preview__1_.png', 'active', '2025-05-23 18:36:33', '2025-06-25 08:11:29', '2025-05-24', NULL, 0, NULL),
(65, 'MAGGI BOUILLON TABLETS CHICKEN 24X60X10 G', '', 1.00, 1.00, 10, '5211', 33, NULL, 'uploads/products/1750839040_1748021992_60-removebg-preview.png', 'active', '2025-05-23 18:38:09', '2025-06-25 08:10:40', '2025-05-24', NULL, 0, NULL),
(66, 'MAGGI BOUILLON TABLETS SHRIMP 24X60X10 G', '', 1.00, 1.00, 10, '7512', 33, NULL, 'uploads/products/1750839635_1748025107_3-removebg-preview.png', 'active', '2025-05-23 18:39:40', '2025-06-25 08:20:35', '2025-05-24', NULL, 0, NULL),
(67, 'PRAISE PALM OIL 12X1l', '', 1.00, 1.00, 10, '6588', 33, NULL, 'uploads/products/1750837372_1748023320_tang_mango_2.5kg-removebg-preview.png', 'active', '2025-05-23 18:41:12', '2025-06-25 07:42:52', '2025-05-24', NULL, 0, NULL),
(68, 'PRAISE PALM OIL 24X500 ml', '', 1.00, 2.00, 10, '8522', 33, NULL, 'uploads/products/1750836922_1750491057_1748024891_1-removebg-preview.png', 'active', '2025-05-23 18:42:44', '2025-06-25 07:35:22', '2025-05-24', NULL, 0, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
