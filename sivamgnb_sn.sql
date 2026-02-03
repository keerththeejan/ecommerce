-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2026 at 03:24 AM
-- Server version: 11.4.9-MariaDB-cll-lve-log
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sivamgnb_sn`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_store`
--

CREATE TABLE `about_store` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_store`
--

INSERT INTO `about_store` (`id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(3, 'About store', 'Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.', NULL, '2025-08-10 07:55:48', '2025-08-10 15:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
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
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `type`, `first_name`, `last_name`, `company`, `address1`, `address2`, `city`, `state`, `postal_code`, `country`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'shipping', 'user', 'user', 'user', 'Kilinochchi', '', 'Kilinochchi', 'north', '00000', 'Sri Lanka', '0778870135', 1, '2025-08-19 14:17:11', '2025-08-19 14:17:11'),
(2, 1, 'shipping', 'Rasenthiram', 'Pavuthira', 'admin', 'Schwandgasse 16', '', 'Oberburg', 'north', '3414', 'Switzerland', '0798645352', 1, '2025-08-19 14:24:16', '2025-08-19 14:24:16'),
(3, 2, 'billing', 'user', 'user', 'user', 'Kilinochchi', '', 'Kilinochchi', 'kk', '00000', 'Sri Lanka', '0778870135', 1, '2025-08-19 14:26:50', '2025-08-19 14:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Happy Pongal', '', 'uploads/banners/1768817992_b6a84916-63a2-46f7-84a8-3d4f60d1f8e7.jpeg', 'active', '2025-07-01 05:11:30', '2026-01-19 10:19:52'),
(5, 'SIVAKAMY ', '', 'uploads/banners/1766358996_IMG_5911.jpeg', 'active', '2025-07-02 04:34:50', '2025-12-21 23:16:36'),
(6, 'SIVAKAMY ', '', 'uploads/banners/1766358855_IMG_9013.jpeg', 'active', '2025-07-02 04:35:06', '2025-12-21 23:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `banner_settings`
--

CREATE TABLE `banner_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(10) NOT NULL DEFAULT 'show',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(12, 'Jaisal', 'jaisal', '', 'uploads/brands/682f4226d1c6d.jpg', 'active', '2025-05-18 05:46:22', '2025-05-24 07:03:00'),
(14, 'Sivakamy', 'sivakamy', '', 'uploads/brands/brand_694a7ad54eb684.26828707.jpg', 'active', '2025-07-25 06:57:49', '2026-01-13 18:50:26'),
(15, 'Buenas', 'buenas', '', 'uploads/brands/brand_696693039858b5.59608322.png', 'active', '2025-07-25 08:30:01', '2026-01-13 18:49:05'),
(16, 'Datu Puti', 'datu-puti', '', 'uploads/brands/brand_696693553427c6.70823216.png', 'active', '2025-07-25 08:30:15', '2026-01-13 18:49:25'),
(17, 'Peak', 'peak', '', 'uploads/brands/brand_696692b6d1c3d1.23737721.png', 'active', '2025-07-25 08:30:35', '2026-01-13 18:50:13'),
(20, 'Horlicks', 'horlicks', '', 'uploads/brands/brand_6966989144ceb6.11554158.jpg', 'active', '2025-08-08 16:55:47', '2026-01-13 19:10:09'),
(21, 'Goya', 'goya', '', 'uploads/brands/brand_6966940f6538b7.04863820.jpg', 'active', '2026-01-13 18:50:55', '2026-01-13 18:50:55'),
(22, 'Mama', 'mama', '', 'uploads/brands/brand_6966942a591ad3.49946628.jpg', 'active', '2026-01-13 18:51:22', '2026-01-13 18:51:22'),
(23, 'Nestle', 'nestle', '', 'uploads/brands/brand_69669473e2b759.46606464.png', 'active', '2026-01-13 18:51:49', '2026-01-13 18:52:35'),
(24, 'Nongshim', 'nongshim', '', 'uploads/brands/brand_6966945fb7b617.06963764.jpg', 'active', '2026-01-13 18:52:15', '2026-01-13 18:52:15'),
(25, 'Silver Swan', 'silver-swan', '', 'uploads/brands/brand_69669488f42299.77436698.png', 'active', '2026-01-13 18:52:57', '2026-01-13 18:52:57'),
(26, 'Tata Tea', 'tata-tea', '', 'uploads/brands/brand_696694add6c579.72318561.jpg', 'active', '2026-01-13 18:53:33', '2026-01-13 18:53:33'),
(27, 'Trs', 'trs', '', 'uploads/brands/brand_696694c15bc9c1.87945112.jpg', 'active', '2026-01-13 18:53:53', '2026-01-13 18:53:53'),
(28, 'Ufc', 'ufc', '', 'uploads/brands/brand_696694d6637483.52782633.jpg', 'active', '2026-01-13 18:54:14', '2026-01-13 18:54:14'),
(29, 'Wai Wai', 'wai-wai', '', 'uploads/brands/brand_696694ea99f767.75865305.png', 'active', '2026-01-13 18:54:34', '2026-01-13 18:54:34'),
(30, 'Little India', 'little-india', '', 'uploads/brands/brand_696694fb7f3826.93222450.jpg', 'active', '2026-01-13 18:54:51', '2026-01-13 18:54:51'),
(31, 'Mae Ploy', 'mae-ploy', '', 'uploads/brands/brand_6966951621eb06.96950397.jpg', 'active', '2026-01-13 18:55:18', '2026-01-13 18:55:18'),
(32, 'Mang Tomas', 'mang-tomas', '', 'uploads/brands/brand_6966952accaf39.73404349.jpg', 'active', '2026-01-13 18:55:38', '2026-01-13 18:55:38'),
(33, 'Squid', 'squid', '', 'uploads/brands/brand_696698755798c1.99711381.jpg', 'active', '2026-01-13 19:09:41', '2026-01-13 19:09:41'),
(34, 'PG Tips', 'pg-tips', '', 'uploads/brands/brand_696698cc9df261.79136600.png', 'active', '2026-01-13 19:11:08', '2026-01-13 19:11:08'),
(35, 'Tilda', 'tilda', '', 'uploads/brands/brand_696698eac31087.71382504.png', 'active', '2026-01-13 19:11:38', '2026-01-13 19:11:38'),
(36, 'Elephant House', 'elephant-house', '', 'uploads/brands/brand_6966990e521602.93497458.png', 'active', '2026-01-13 19:12:14', '2026-01-13 19:12:14'),
(37, 'Foco', 'foco', '', 'uploads/brands/brand_6966992673d5b1.81564755.jpg', 'active', '2026-01-13 19:12:38', '2026-01-13 19:12:38'),
(38, 'Lipton', 'lipton', '', 'uploads/brands/brand_6966997037b260.11178154.jpg', 'active', '2026-01-13 19:13:52', '2026-01-13 19:13:52'),
(39, 'Pcd', 'pcd', '', 'uploads/brands/brand_69669987215f53.76121990.png', 'active', '2026-01-13 19:14:15', '2026-01-13 19:14:15'),
(40, 'Buldak', 'buldak', '', 'uploads/brands/brand_696699ba478b87.85398043.jpg', 'active', '2026-01-13 19:15:06', '2026-01-13 19:15:06'),
(41, 'Munchee CBL', 'munchee-cbl', '', 'uploads/brands/brand_69729394e23c12.29848984.png', 'active', '2026-01-22 21:16:04', '2026-01-22 21:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(67, 2, 119, 1, '2025-08-30 01:05:54', '2025-08-30 01:05:54'),
(68, 5, 118, 7, '2025-09-27 16:29:26', '2025-09-29 05:02:14'),
(69, 5, 121, 1, '2025-09-27 16:29:40', '2025-09-27 16:29:40'),
(70, 5, 120, 2, '2025-09-27 16:29:41', '2025-09-27 16:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tax` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `image`, `parent_id`, `tax_id`, `status`, `created_at`, `updated_at`, `tax`) VALUES
(44, 'Meeresfrüchte', '', NULL, 'uploads/categories/69817bcc383f6.jpg', NULL, 4, 1, '2025-08-24 06:19:50', '2026-02-03 04:38:36', 0),
(45, 'Milch und Kokosnuss', '', NULL, 'uploads/categories/69817c89928ae.jpg', NULL, 4, 1, '2025-08-24 06:26:15', '2026-02-03 04:41:45', 0),
(56, 'Früchte und Gemüse', '', NULL, 'uploads/categories/6957759a41b1e.jpg', NULL, 4, 1, '2025-09-05 09:04:55', '2026-01-02 07:36:58', 0),
(57, 'Snacks und Süssigkeiten', '', NULL, 'uploads/categories/6981990de35d9.jpg', NULL, 4, 1, '2025-09-08 15:42:45', '2026-02-03 06:43:25', 0),
(58, 'Sauce und Marinade', '', NULL, 'uploads/categories/69817edb2700e.jpg', NULL, 4, 1, '2025-09-08 15:49:17', '2026-02-03 04:51:39', 0),
(59, 'Mehl und Getreide', '', NULL, 'uploads/categories/69817c0880630.jpg', NULL, 4, 1, '2025-09-08 15:53:04', '2026-02-03 04:39:36', 0),
(60, 'Tiefkühlprodukte', '', NULL, 'uploads/categories/6981999f2c38f.jpg', NULL, 4, 1, '2025-09-08 15:59:42', '2026-02-03 06:45:51', 0),
(61, 'Soft Getränke', '', NULL, 'uploads/categories/6981995d5aacc.jpg', NULL, 4, 1, '2025-09-09 13:24:31', '2026-02-03 06:44:45', 0),
(62, 'Kaffee und Tee', '', NULL, 'uploads/categories/695775ffe1895.jpg', NULL, 4, 1, '2025-09-13 03:07:16', '2026-01-02 07:38:39', 0),
(63, 'Alkoholische Getränke', '', NULL, 'uploads/categories/6957740ccd438.jpg', NULL, 2, 1, '2025-09-13 03:14:26', '2026-01-02 07:30:20', 0),
(64, 'Körper und Haarpflege', '', NULL, 'uploads/categories/69817a109585a.jpg', NULL, 2, 1, '2025-09-13 03:17:14', '2026-02-03 04:31:12', 0),
(66, 'Haushalt und Wohnen', '', NULL, 'uploads/categories/6957754caaac4.jpg', NULL, 4, 1, '2025-09-13 03:32:09', '2026-01-02 07:35:40', 0),
(67, 'Gewürze - Kräuter', '', NULL, 'uploads/categories/695775e80eb9e.jpg', NULL, 4, 1, '2025-09-13 03:36:04', '2026-01-02 07:38:16', 0),
(68, 'Reise', '', NULL, 'uploads/categories/69817ecddda5a.jpg', NULL, 4, 1, '2025-12-21 23:01:08', '2026-02-03 04:51:25', 0),
(69, 'Sirup und Dessert', '', NULL, 'uploads/categories/6981985704522.jpg', NULL, 4, 1, '2025-12-21 23:03:03', '2026-02-03 06:40:23', 0),
(70, 'Öl und Butter', '', NULL, 'uploads/categories/69817ce5e85e7.jpg', NULL, 4, 1, '2025-12-21 23:06:12', '2026-02-03 04:43:17', 0),
(71, 'Kultur und Temple', '', NULL, 'uploads/categories/69817bb62dcbb.jpg', NULL, 2, 1, '2025-12-21 23:07:47', '2026-02-03 04:38:14', 0),
(72, 'Bohnen und Linsen', '', NULL, 'uploads/categories/695775835a4e5.jpg', NULL, 4, 1, '2025-12-21 23:11:00', '2026-01-02 07:36:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `hours_weekdays` varchar(191) NOT NULL,
  `hours_weekends` varchar(191) NOT NULL,
  `map_embed` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `address`, `phone`, `email`, `hours_weekdays`, `hours_weekends`, `map_embed`, `created_at`, `updated_at`) VALUES
(4, 'mullaitivu  fhuhiufh', '9889866689', 'hguyii@gmil.com', 'monda 12 to 67 erma prathika', 'sat 4 to 9', '', '2025-08-17 22:17:21', '2025-08-19 23:21:40');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `code` varchar(2) NOT NULL,
  `flag_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `description`, `code`, `flag_image`, `status`, `created_at`, `updated_at`) VALUES
(17, 'china', NULL, 'CH', 'flag_1753981933_688ba3eda73e3.png', 'active', '2025-07-31 17:12:13', '2025-07-31 17:12:13'),
(16, 'Korea', NULL, 'KO', 'flag_1753981908_688ba3d43005f.png', 'active', '2025-07-31 17:11:48', '2025-07-31 17:11:48'),
(18, 'india', NULL, 'IN', 'flag_1753983494_688baa06f33a4.png', 'active', '2025-07-31 17:38:14', '2025-07-31 17:38:15'),
(19, 'USA', NULL, 'US', NULL, 'active', '2026-01-14 05:54:36', '2026-01-13 19:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `footer_sections`
--

CREATE TABLE `footer_sections` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_sections`
--

INSERT INTO `footer_sections` (`id`, `title`, `content`, `type`, `status`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'Add your about us content here...', 'about', 'active', 0, '2025-08-19 17:21:11', '2025-08-19 17:21:11'),
(2, 'Quick Links', '[{\"text\":\"Home\",\"url\":\"http:\\/\\/localhost\\/ecommerce\\/\"},{\"text\":\"Shop\",\"url\":\"http:\\/\\/localhost\\/ecommerce\\/products\"},{\"text\":\"About Us\",\"url\":\"http:\\/\\/localhost\\/ecommerce\\/about\"},{\"text\":\"Contact\",\"url\":\"http:\\/\\/localhost\\/ecommerce\\/contact\"}]', 'links', 'active', 1, '2025-08-19 17:21:11', '2025-08-19 17:21:11'),
(3, 'Contact Us', '{\"address\":\"123 Main St, City, Country\",\"phone\":\"+1 234 567 890\",\"email\":\"info@example.com\"}', 'contact', 'active', 2, '2025-08-19 17:21:11', '2025-08-19 17:21:11'),
(4, 'Follow Us', '{\"facebook\":\"https:\\/\\/facebook.com\",\"twitter\":\"https:\\/\\/twitter.com\",\"instagram\":\"https:\\/\\/instagram.com\",\"youtube\":\"https:\\/\\/youtube.com\",\"linkedin\":\"https:\\/\\/linkedin.com\"}', 'social', 'active', 3, '2025-08-19 17:21:11', '2025-08-19 17:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `invoice_date` datetime NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` enum('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(6,3) NOT NULL DEFAULT 0.000,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billing_address` text DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `active`, `created_at`, `updated_at`) VALUES
(1, 'hguyii@gmil.com', 1, '2025-08-18 20:07:06', '2025-08-18 20:07:06'),
(2, 'yhfwlqgu@testform.xyz', 1, '2025-08-29 01:28:00', '2025-08-29 01:28:00'),
(3, 'upwjhtyt@testform.xyz', 1, '2025-08-29 01:28:02', '2025-08-29 01:28:02'),
(4, 'vjpnzfle@testform.xyz', 1, '2025-11-05 00:57:50', '2025-11-05 00:57:50'),
(5, 'hoodxipm@testform.xyz', 1, '2025-11-05 00:57:51', '2025-11-05 00:57:51'),
(6, 'ehqgozlj@testform.xyz', 1, '2025-11-05 00:57:51', '2025-11-05 00:57:51'),
(7, 'wztdmnlv@testform.xyz', 1, '2025-11-05 00:57:54', '2025-11-05 00:57:54'),
(8, 'rxrtpdqr@testform.xyz', 1, '2025-11-13 05:11:56', '2025-11-13 05:11:56'),
(9, 'rovgdwxw@testform.xyz', 1, '2025-11-13 05:11:56', '2025-11-13 05:11:56'),
(10, 'egptsmvr@testform.xyz', 1, '2025-11-13 05:11:58', '2025-11-13 05:11:58'),
(11, 'wirumydy@testform.xyz', 1, '2025-11-13 05:12:02', '2025-11-13 05:12:02'),
(12, 'eusqtvti@testform.xyz', 1, '2025-12-08 12:03:22', '2025-12-08 12:03:22'),
(13, 'bzdii12@gmail.com', 1, '2026-01-10 17:30:52', '2026-01-10 17:30:52'),
(14, 'cem.alacayir1895@gmx.de', 1, '2026-01-11 17:28:04', '2026-01-11 17:28:04'),
(15, 'Info@stevanellosschnaeppchenhandel.de', 1, '2026-01-11 17:46:35', '2026-01-11 17:46:35'),
(16, 'eohknjim@forms-checker.online', 1, '2026-01-18 23:16:52', '2026-01-18 23:16:52'),
(17, 'nihsynwg@forms-checker.online', 1, '2026-01-18 23:16:53', '2026-01-18 23:16:53'),
(18, 'lslrmynv@forms-checker.online', 1, '2026-01-18 23:16:55', '2026-01-18 23:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pos_sessions`
--

CREATE TABLE `pos_sessions` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `opening_balance` decimal(10,2) NOT NULL,
  `closing_balance` decimal(10,2) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `opened_at` timestamp NULL DEFAULT current_timestamp(),
  `closed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pos_sessions`
--

INSERT INTO `pos_sessions` (`id`, `staff_id`, `opening_balance`, `closing_balance`, `status`, `opened_at`, `closed_at`, `notes`) VALUES
(1, 1, 2.00, NULL, 'open', '2025-04-26 05:58:29', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','out_of_stock') DEFAULT 'active',
  `is_visible` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `add_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `country_id` int(11) DEFAULT NULL,
  `price2` decimal(10,2) DEFAULT NULL,
  `price3` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `batch_number` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `is_visible`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`, `price2`, `price3`, `supplier`, `batch_number`) VALUES
(117, 'Jaggery Pulver SIVAKAMY 20X500 G', '', 15.00, 5.00, 20, 'FITNC7660', 44, 17, 'uploads/products/1766329521_1814bb9f-3cc7-4b36-8e0c-dddcb8c3be88.jpeg', 'active', 0, '2025-08-24 06:41:00', '2025-12-21 15:05:21', '2025-08-24', '2026-04-26', 0, 18, 30.00, 25.00, 'kujinsa', 0),
(122, 'Alkoholische Getränke', '', 10.00, NULL, 3, 'ad', 63, 17, 'uploads/products/1770101335_Alkoholische.jpg', 'active', 0, '2026-01-02 07:44:24', '2026-02-03 06:48:55', '2026-01-02', '2026-01-02', 0, 18, 10.00, 10.00, '', 0),
(118, 'Green Cardomom SIVAKAMY 20X200 G', '', 160.00, 10.00, 9, 'GINGENI7943', 44, 12, 'uploads/products/1766329345_0449321c-8ebf-4fcb-93c4-a86361a15609.jpeg', 'active', 0, '2025-08-24 06:45:43', '2025-12-21 15:02:25', '2025-08-24', '2026-02-22', 0, 18, 190.00, 20.00, '', 0),
(119, 'Fennel seeds SIVAKAMY 20X400 G', '', 50.00, 5.00, 10, 'KAIJAERIC8404', 45, 12, 'uploads/products/1770101719_Fennel.jpg', 'active', 0, '2025-08-24 06:53:24', '2026-02-03 06:55:19', '2025-08-24', '2026-01-18', 0, 18, 76.00, 70.00, '', 0),
(120, 'Seeraka Sambareise SIVAKAMY 4X5 KG', '', 35.00, 5.00, 10, 'Jell', 72, 17, 'uploads/products/1770101465_Seeraka.jpg', 'active', 0, '2025-08-24 06:59:02', '2026-02-03 06:51:05', '2025-08-24', '2026-01-25', 0, 18, 55.00, 50.00, 'kujinsa', 0),
(121, 'Rotes Rohreise SP SIVAKAMY 4X5 KG', 'To check the latest stock and prices and to place your orders, please log in. If you’re not a customer yet, sign up today!', 25.00, 5.00, 8, 'KOKUHOS8958', 68, 17, 'uploads/products/1770101376_Rotes.jpg', 'active', 0, '2025-08-24 07:02:38', '2026-02-03 06:49:36', '2025-08-24', '2026-01-24', 0, 18, 35.00, 30.00, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_no` varchar(50) NOT NULL,
  `supplier_id` varchar(20) NOT NULL,
  `location_id` varchar(20) NOT NULL,
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
  `created_by` int(11) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `purchase_no`, `supplier_id`, `location_id`, `purchase_date`, `due_date`, `subtotal`, `discount_type`, `discount_amount`, `tax_amount`, `shipping_charges`, `total_amount`, `paid_amount`, `payment_status`, `status`, `notes`, `created_by`, `document_path`, `created_at`, `updated_at`) VALUES
(167, 'PO-20250905-DC2696', '15', 'BL0001', '2025-09-05', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 0.00, 100.00, 'partial', 'received', '[RETURN] [RETURN]', NULL, NULL, '2025-09-05 06:44:58', '2025-09-16 07:04:49'),
(168, 'PO-20250905-E30FD6', '15', 'BL0001', '2025-09-05', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 0.00, 100.00, 'partial', 'received', '[RETURN]', NULL, NULL, '2025-09-05 06:46:56', '2025-09-05 06:47:24'),
(169, 'PO-20250916-CF1CBF', '15', 'BL0001', '2025-09-16', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 4000.00, 0.00, 'pending', 'received', '', NULL, NULL, '2025-09-16 07:05:44', '2025-09-16 07:05:44'),
(170, 'PR-20250920-02B708', '13', 'BL0001', '2025-09-20', NULL, 0.00, 'fixed', 0.00, 0.00, 0.00, 45.00, 0.00, 'pending', 'received', '[RETURN]', NULL, NULL, '2025-09-20 04:27:38', '2025-09-20 04:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `tax_rate` decimal(5,2) DEFAULT 0.00,
  `tax_amount` decimal(15,2) DEFAULT 0.00,
  `discount_type` enum('fixed','percentage') DEFAULT 'fixed',
  `discount_amount` decimal(15,2) DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `unit_price`, `tax_rate`, `tax_amount`, `discount_type`, `discount_amount`, `subtotal`, `total`, `created_at`, `updated_at`) VALUES
(178, 167, 122, 10.00, 200.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-05 06:44:58', '2025-09-05 06:44:58'),
(179, 167, 122, -10.00, 200.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-05 06:45:11', '2025-09-05 06:45:11'),
(180, 168, 121, 10.00, 24.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-05 06:46:56', '2025-09-05 06:46:56'),
(181, 168, 121, -10.00, 24.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-05 06:47:24', '2025-09-05 06:47:24'),
(182, 167, 122, -20.00, 200.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-16 07:04:48', '2025-09-16 07:04:48'),
(183, 169, 122, 20.00, 200.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-16 07:05:44', '2025-09-16 07:05:44'),
(184, 170, 117, 9.00, 5.00, 0.00, 0.00, 'fixed', 0.00, 0.00, 0.00, '2025-09-20 04:27:38', '2025-09-20 04:27:38');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `purchase_id`, `amount`, `payment_method`, `notes`, `payment_date`, `created_at`) VALUES
(1, 19, 0.33, 'cash', '', '2025-08-23 12:11:41', '2025-08-23 06:41:41'),
(2, 20, 20.00, 'cash', '', '2025-08-23 12:12:24', '2025-08-23 06:42:24'),
(3, 21, 45.00, 'cash', '', '2025-08-23 12:21:29', '2025-08-23 06:51:29'),
(4, 23, 20.00, 'card', '', '2025-08-25 13:38:05', '2025-08-25 08:08:05'),
(5, 24, 20.00, 'card', '', '2025-08-25 13:38:05', '2025-08-25 08:08:05'),
(6, 25, 20.00, 'cash', '', '2025-08-25 13:38:51', '2025-08-25 08:08:51'),
(7, 26, 20.00, 'cash', '', '2025-08-25 13:38:51', '2025-08-25 08:08:51'),
(8, 27, 50.00, 'cash', '', '2025-08-25 13:39:30', '2025-08-25 08:09:30'),
(9, 28, 50.00, 'cash', '', '2025-08-25 13:39:30', '2025-08-25 08:09:30'),
(10, 29, 50.00, 'cash', '', '2025-08-25 13:43:56', '2025-08-25 08:13:56'),
(11, 30, 50.00, 'cash', '', '2025-08-25 13:43:56', '2025-08-25 08:13:56'),
(12, 31, 50.00, 'cash', '', '2025-08-25 13:48:18', '2025-08-25 08:18:18'),
(13, 32, 56.00, 'cash', '', '2025-08-25 14:53:35', '2025-08-25 09:23:35'),
(14, 33, 60.00, 'cash', '', '2025-08-28 18:10:55', '2025-08-28 12:40:55'),
(15, 61, 50.00, 'cash', '', '2025-08-30 13:38:50', '2025-08-30 08:08:50'),
(16, 62, 10.00, 'cash', '', '2025-08-30 13:48:16', '2025-08-30 08:18:16'),
(17, 63, 60.00, 'cash', '', '2025-08-30 13:53:54', '2025-08-30 08:23:54'),
(18, 64, 5.00, 'cash', '', '2025-08-30 14:01:35', '2025-08-30 08:31:35'),
(19, 65, 8.00, 'cash', '', '2025-08-30 14:06:18', '2025-08-30 08:36:18'),
(20, 66, 100.00, 'cash', '', '2025-08-30 14:09:04', '2025-08-30 08:39:04'),
(21, 68, 10.00, 'cash', '', '2025-08-30 14:15:04', '2025-08-30 08:45:04'),
(22, 69, 60.00, 'cash', '', '2025-08-30 14:30:45', '2025-08-30 09:00:45'),
(23, 70, 30.00, 'cash', '', '2025-08-30 14:33:22', '2025-08-30 09:03:22'),
(24, 107, 100.00, 'cash', '', '2025-09-01 21:14:25', '2025-09-01 15:44:25'),
(25, 115, 400.00, 'cash', '', '2025-09-02 21:11:40', '2025-09-02 15:41:40'),
(26, 147, 50.00, 'cash', '', '2025-09-05 10:07:15', '2025-09-05 04:37:15'),
(27, 149, 10.00, 'cash', '', '2025-09-05 10:47:38', '2025-09-05 05:17:38'),
(28, 150, 10.00, 'cash', '', '2025-09-05 10:48:41', '2025-09-05 05:18:41'),
(29, 153, 100.00, 'cash', '', '2025-09-05 10:58:25', '2025-09-05 05:28:25'),
(30, 155, 100.00, 'cash', '', '2025-09-05 11:06:46', '2025-09-05 05:36:46'),
(31, 157, 50.00, 'cash', '', '2025-09-05 11:13:59', '2025-09-05 05:43:59'),
(32, 159, 20.00, 'cash', '', '2025-09-05 11:27:12', '2025-09-05 05:57:12'),
(33, 160, 10.00, 'cash', '', '2025-09-05 11:31:29', '2025-09-05 06:01:29'),
(34, 161, 20.00, 'cash', '', '2025-09-05 11:33:28', '2025-09-05 06:03:28'),
(35, 162, 10.00, 'cash', '', '2025-09-05 11:37:34', '2025-09-05 06:07:34'),
(36, 163, 100.00, 'cash', '', '2025-09-05 11:39:51', '2025-09-05 06:09:51'),
(37, 164, 20.00, 'cash', '', '2025-09-05 11:41:13', '2025-09-05 06:11:13'),
(38, 165, 100.00, 'cash', '', '2025-09-05 11:47:50', '2025-09-05 06:17:50'),
(39, 166, 50.00, 'cash', '', '2025-09-05 12:13:17', '2025-09-05 06:43:17'),
(40, 167, 100.00, 'cash', '', '2025-09-05 12:14:58', '2025-09-05 06:44:58'),
(41, 168, 100.00, 'cash', '', '2025-09-05 12:16:56', '2025-09-05 06:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(50) DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'store_name', 'Sivakamy', 'general', '2025-04-25 07:59:26', '2026-01-16 06:24:49'),
(2, 'store_email', 'info@example.com', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(3, 'store_phone', '+1234567890', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(4, 'store_address', '123 Main St, City, Country', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(5, 'currency_symbol', '$', 'general', '2025-04-25 07:59:26', '2025-04-25 07:59:26'),
(6, 'site_name', 'Sivakamy', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(7, 'site_description', '', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(8, 'site_email', 'Sivakamy@gmail.com', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(9, 'site_phone', '798645352', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(10, 'site_address', '', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(11, 'site_logo', '694a7afd147f8.jpg', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
(12, 'site_favicon', '', 'general', '2025-04-26 05:02:09', '2025-12-23 11:20:29'),
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

CREATE TABLE `shipping_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_weight_based` tinyint(1) NOT NULL DEFAULT 0,
  `free_weight_threshold` decimal(10,2) DEFAULT NULL,
  `weight_step` decimal(10,2) DEFAULT NULL,
  `price_per_step` decimal(10,2) DEFAULT NULL,
  `free_shipping_threshold` decimal(10,2) DEFAULT NULL,
  `estimated_delivery` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `product_name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(12, 'kujinsa', 'gee', 'kujin@gmail.com', '0772581496', 'nj', '2025-08-10 13:49:48', '2025-08-10 13:49:48'),
(13, 'kujinsa', 'gee', 'pirai@gmail.com', '0762589632', 'gyg', '2025-08-10 13:52:39', '2025-08-10 13:52:39'),
(14, 'mathu', 'bis', 'pirai@gmail.com', '0772581496', 'ghghf', '2025-08-10 13:57:07', '2025-08-10 13:57:07'),
(15, 'pirathi', 'juice', 'pirai@gmail.com', '0772581496', 'ghj', '2025-08-10 14:22:43', '2025-08-10 14:22:43'),
(16, 'Stutzer & Co. AG', 'Lebensmittel', 'oders@stutzer.ch', '+41 44 315 56 48', 'Hofwiesenstrasse 349 | Franklinturm\r\nCH-8050 Zürich \r\nSwitzerland', '2025-12-23 11:23:03', '2025-12-23 11:23:03'),
(17, 'Fresh Tropical srl by Jawad', '+39 02 359 2321', 'freshtropical@freshtropical.it', '', 'Via Alberto da Giussano, 22 \r\nCorbetta Millano', '2025-12-23 11:25:42', '2025-12-23 11:25:42'),
(18, 'VENTHAN TRADING PRIVATE LIMITED', 'Lebensmittel', 'viknadasanvikky@gmail.com', '+919884669096', 'No.270/4, Eri Kari Street\r\nAnna Nagar, Kolapakkam \r\nChennai 600048 \r\nTamil Nadu, India', '2025-12-23 11:29:35', '2025-12-23 11:29:35');

-- --------------------------------------------------------

--
-- Table structure for table `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `name`, `rate`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Standard Tax', 0.00, 0, '2025-08-10 15:20:07', '2025-12-21 14:31:25'),
(2, 'MWST 8.1 %', 8.10, 1, '2025-08-12 03:29:58', '2025-12-21 14:31:09'),
(3, 'keethan', 0.02, 0, '2025-08-12 03:30:11', '2025-12-21 14:31:20'),
(4, 'MWST 2.6 %', 2.60, 1, '2025-08-12 04:56:50', '2025-12-21 14:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `created_at`) VALUES
(1, 10, NULL, 'cash', 26214520.00, 'completed', '2025-08-25 06:21:03'),
(2, 11, NULL, 'cash', 99999999.99, 'completed', '2025-08-25 19:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `role` enum('admin','customer','staff') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin', '2025-04-22 06:49:56', '2025-04-22 06:49:56'),
(2, 'user', 'user@gmail.com', '$2y$10$KDCsLPiKGl6krzxovsk5QeIIiPQT1oM7X2SH.m..I1o3KdQJGrgzu', 'user', 'user', 'customer', '2025-08-19 14:10:12', '2025-08-19 14:10:12'),
(3, 'guest', 'guest@example.com', '$2y$10$WLhzftMO5cAtxAFGA5FpheIo7xi/qi2itkintTLMNKaJuYLN8YUiK', 'Guest', 'User', 'customer', '2025-08-19 19:01:30', '2025-08-19 19:01:30'),
(4, 'NAYUYUTY418293NEWETREWT', 'vrcxelgh@wildbmail.com', '$2y$12$46dh4stFlCu6vCaJFuLuQ.NNj/FXxiQWVz3HWJSjQ5rERsE6ra6kW', 'NAYUYUTY418293NEWETREWT', 'NAYUYUTY418293NEWETREWT', 'customer', '2025-09-13 23:46:14', '2025-09-13 23:46:14'),
(5, 'vaanu', 'vanu@gmail.com', '$2y$12$5UJfIXeiMbDBG.0EBXIdaeXjYs4gbszroAliVuyNQY7pLOC2ge6Ua', 'vaanu', 'vaanu', 'customer', '2025-09-27 16:28:43', '2025-09-27 16:28:43'),
(6, 'wdttswnsgo', 'ehqgozlj@testform.xyz', '$2y$12$TPiXv6cuzGbpsD4iPIKj9O3t88JW16iGiuSWeoGg/naInoQCHnAju', 'jukospryqk', 'zllgmjjwex', 'customer', '2025-11-05 05:57:50', '2025-11-05 05:57:50'),
(7, 'widzsofhrg', 'wztdmnlv@testform.xyz', '$2y$12$f06uAikYenwpmgfNLy85ne/sIyEfLpsZzB/CjhvQYS8FC/KS3GSfS', 'lrlkhfeiey', 'ppmffgvplo', 'customer', '2025-11-05 05:57:51', '2025-11-05 05:57:51'),
(8, 'oogqkysovo', 'egptsmvr@testform.xyz', '$2y$12$uvjizmUVFVkh5NYw4/gZmeBwwP.RX4oHElcyZRxdO/l9fz63W4rAy', 'ssflioiuxi', 'oukeexlvmm', 'customer', '2025-11-13 10:11:56', '2025-11-13 10:11:56'),
(9, 'dvhsguvxpf', 'wirumydy@testform.xyz', '$2y$12$tNPQk2if7/7azeH5fX5ln.fBJ02xUKhPHFOjCA0ivRhhmIXYJ5PBK', 'kniozmutzl', 'ervxhzwnzo', 'customer', '2025-11-13 10:11:58', '2025-11-13 10:11:58'),
(10, 'foxapps', '533peach@virgilian.com', '$2y$12$s7SvprFiCZQOkvxl0vokJ.r6kdm66Y20EyXHKthX41Gwm7VRlUmay', 'kamal', 'ali', 'customer', '2026-01-11 15:56:24', '2026-01-11 15:56:24'),
(11, 'qzkxzmvkuy', 'ptkzsxye@forms-checker.online', '$2y$12$UU5mf3Xmiiv3LkW3BzHyJu.7eAfQsU9yNYANdli1f9IMAbmDeXh0u', 'vzlkthyguv', 'vdqizmrgqk', 'customer', '2026-01-19 04:16:55', '2026-01-19 04:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 1, 68, '2025-05-27 13:03:52'),
(2, 1, 67, '2025-05-27 14:50:44'),
(3, 1, 66, '2025-05-27 14:51:27'),
(4, 1, 62, '2025-05-27 14:52:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_store`
--
ALTER TABLE `about_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_settings`
--
ALTER TABLE `banner_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `fk_categories_tax` (`tax_id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_sections`
--
ALTER TABLE `footer_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `status` (`status`),
  ADD KEY `sort_order` (`sort_order`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_invoice_number` (`invoice_number`),
  ADD KEY `idx_invoices_order_id` (`order_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `pos_sessions`
--
ALTER TABLE `pos_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_products_brands` (`brand_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_no` (`purchase_no`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_store`
--
ALTER TABLE `about_store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `banner_settings`
--
ALTER TABLE `banner_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `footer_sections`
--
ALTER TABLE `footer_sections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pos_sessions`
--
ALTER TABLE `pos_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
