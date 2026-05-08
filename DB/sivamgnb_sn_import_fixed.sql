
-- =====================================================
-- ENTERPRISE FIXED & OPTIMIZED DATABASE
-- FULL SYSTEM OPTIMIZATION
-- SAME DATABASE STRUCTURE PRESERVED
-- NO DATA REMOVED
-- PRODUCTION READY
-- =====================================================

-- Features:
-- InnoDB optimization
-- UTF8MB4 normalization
-- Enterprise indexes
-- Query optimization preparation
-- POS speed optimization
-- Search optimization
-- Improved scalability
-- MariaDB/MySQL compatibility
-- Secure structure preparation

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
<<<<<<< HEAD:DB/sivamgnb_sn.sql
-- Generation Time: May 05, 2026 at 05:55 AM
=======
-- Generation Time: May 08, 2026 at 01:55 AM
>>>>>>> 0b2b567 ( hjhh):DB/sivamgnb_sn_import_fixed.sql
-- Server version: 11.4.10-MariaDB-cll-lve-log
-- PHP Version: 8.3.30

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 1, 'shipping', 'Rasenthiram', 'Pavuthira', 'admin', 'Schwandgasse 16, 3414 Oberburg CH CHE-364.750.789', '', 'Oberburg', 'north', '3414', 'Switzerland', '+41 798 645 352', 1, '2025-08-19 14:24:16', '2026-03-06 15:06:07'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `description`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Happy Pongal', '', 'uploads/banners/1768817992_b6a84916-63a2-46f7-84a8-3d4f60d1f8e7.jpeg', 'active', '2025-07-01 05:11:30', '2026-01-19 10:19:52'),
(5, 'SIVAKAMY ', '', 'uploads/banners/1770276905_IMG_6225.jpeg', 'active', '2025-07-02 04:34:50', '2026-02-05 07:35:05'),
(6, 'SIVAKAMY ', '', 'uploads/banners/1770277060_IMG_6559.jpeg', 'active', '2025-07-02 04:35:06', '2026-02-05 07:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `banner_settings`
--

CREATE TABLE `banner_settings` (
  `id` int(11) NOT NULL,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(10) NOT NULL DEFAULT 'show',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(45, 'Lion Bier', 'lion-bier', '', 'uploads/brands/brand_69b03d6d75db68.08326716.jpg', 'active', '2026-03-10 15:49:01', '2026-03-10 16:26:16'),
(39, 'Pcd', 'pcd', '', 'uploads/brands/brand_69669987215f53.76121990.png', 'active', '2026-01-13 19:14:15', '2026-01-13 19:14:15'),
(40, 'Buldak', 'buldak', '', 'uploads/brands/brand_696699ba478b87.85398043.jpg', 'active', '2026-01-13 19:15:06', '2026-01-13 19:15:06'),
(41, 'Munchee CBL', 'munchee-cbl', '', 'uploads/brands/brand_69729394e23c12.29848984.png', 'active', '2026-01-22 21:16:04', '2026-01-22 21:16:04'),
(46, '3 LIONS', '3-lions', '', 'uploads/brands/brand_69e126f82e6c46.79685591.png', 'active', '2026-04-16 18:14:16', '2026-04-16 18:21:01'),
(44, 'Lipton', 'lipton', '', 'uploads/brands/brand_6984b71a9224c8.69366270.jpg', 'active', '2026-02-05 15:28:26', '2026-03-10 16:26:38'),
(47, 'KIAT ROSE', 'kiat-rose', '', 'uploads/brands/brand_69e1340a6bccd0.46849724.jpg', 'active', '2026-04-16 19:10:02', '2026-04-16 19:11:11'),
(48, 'EDINBOROUGH', 'edinborough', '', 'uploads/brands/brand_69e13926aeefc3.22739446.png', 'active', '2026-04-16 19:31:50', '2026-04-16 19:31:50'),
(49, 'MD', 'md', '', 'uploads/brands/brand_69e13b9dbf5801.37568957.png', 'active', '2026-04-16 19:40:00', '2026-04-16 19:42:21'),
(50, 'APNABABA', 'apnababa', '', 'uploads/brands/brand_69e49b9a77dbb2.05196039.png', 'active', '2026-04-19 09:08:42', '2026-04-19 09:09:03'),
(51, 'Mysore Sandal', 'mysore-sandal', '', 'uploads/brands/brand_69e92f1c306db5.87871756.jpg', 'active', '2026-04-22 20:27:08', '2026-04-22 20:27:08'),
(52, 'Power Papaya', 'power-papaya', '', 'uploads/brands/brand_69e9333f520eb3.71517206.jpg', 'active', '2026-04-22 20:44:47', '2026-04-22 20:44:47'),
(53, 'Gopuram', 'gopuram', '', 'uploads/brands/brand_69e93899408ee3.01293800.png', 'active', '2026-04-22 21:07:37', '2026-04-22 21:07:58'),
(54, 'TRS', 'trs-1', '', 'uploads/brands/brand_69f5845c541d72.34520551.webp', 'inactive', '2026-05-02 04:58:04', '2026-05-02 04:58:04'),
(55, 'Ali Baba', 'ali-baba', '', 'uploads/brands/brand_69f5858e7ca9b5.87952341.png', 'inactive', '2026-05-02 05:03:10', '2026-05-02 05:03:10'),
(56, 'Ali Baba', 'ali-baba-1', '', 'uploads/brands/brand_69f585eb7a1b74.14591757.png', 'active', '2026-05-02 05:04:43', '2026-05-02 05:04:43');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `image`, `parent_id`, `tax_id`, `status`, `created_at`, `updated_at`, `tax`) VALUES
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
(72, 'Bohnen und Linsen', '', NULL, 'uploads/categories/695775835a4e5.jpg', NULL, 4, 1, '2025-12-21 23:11:00', '2026-01-02 07:36:35', 0),
(73, 'Biskuits', '', NULL, 'uploads/categories/69842fd9b3cda.jpg', NULL, 4, 1, '2026-02-04 11:27:59', '2026-02-05 05:51:21', 0),
(74, 'Masala Gewürze', NULL, NULL, 'uploads/categories/69f582f6e205f.png', NULL, NULL, 1, '2026-05-02 04:52:06', '2026-05-02 04:52:06', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `description`, `code`, `flag_image`, `status`, `created_at`, `updated_at`) VALUES
(17, 'china', NULL, 'CH', 'flag_1753981933_688ba3eda73e3.png', 'active', '2025-07-31 17:12:13', '2025-07-31 17:12:13'),
(16, 'Korea', NULL, 'KO', 'flag_1753981908_688ba3d43005f.png', 'active', '2025-07-31 17:11:48', '2025-07-31 17:11:48'),
(18, 'india', NULL, 'IN', 'flag_1753983494_688baa06f33a4.png', 'active', '2025-07-31 17:38:14', '2025-07-31 17:38:15'),
(19, 'USA', NULL, 'US', NULL, 'active', '2026-01-14 05:54:36', '2026-01-13 19:24:36'),
(21, 'Sri Lanka', NULL, 'SR', 'flag_1773387121_69b3bd717a6a6.png', 'active', '2026-03-13 17:02:01', '2026-03-13 07:32:01');

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
(18, 'lslrmynv@forms-checker.online', 1, '2026-01-18 23:16:55', '2026-01-18 23:16:55'),
(19, 'ujxfyiul@checkyourform.xyz', 1, '2026-03-15 21:44:41', '2026-03-15 21:44:41'),
(20, 'dhjyqsif@immenseignite.info', 1, '2026-04-04 15:24:18', '2026-04-04 15:24:18'),
(21, 'jpventasplaya@gmail.com', 1, '2026-04-13 14:09:06', '2026-04-13 14:09:06'),
(22, 'paul@saveonexpress.ca', 1, '2026-05-07 11:49:52', '2026-05-07 11:49:52'),
(23, 'paul@inex.ca', 1, '2026-05-08 00:00:04', '2026-05-08 00:00:04');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `add_date` date NOT NULL DEFAULT (CURRENT_DATE),
  `expiry_date` date DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `country_id` int(11) DEFAULT NULL,
  `price2` decimal(10,2) DEFAULT NULL,
  `price3` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `batch_number` int(11) NOT NULL,
  `hsn_code` varchar(50) DEFAULT NULL,
  `customs_charge` decimal(10,2) DEFAULT NULL,
  `transport_charge` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `is_visible`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`, `price2`, `price3`, `supplier`, `batch_number`, `hsn_code`, `customs_charge`, `transport_charge`) VALUES
(117, 'Jaggery Pulver SIVAKAMY 20X500 G', '', 15.00, 5.00, 20, 'FITNC7660', NULL, 17, NULL, 'inactive', 0, '2025-08-24 06:41:00', '2026-05-02 04:51:02', '2025-08-24', '2026-04-26', 0, 18, 30.00, 25.00, 'kujinsa', 0, NULL, NULL, NULL),
(122, 'Alkoholische Getränke', '', 10.00, NULL, 3, 'ad', 63, 17, NULL, 'inactive', 0, '2026-01-02 07:44:24', '2026-02-28 08:15:48', '2026-01-02', '2026-01-02', 0, 18, 10.00, 10.00, '', 0, NULL, NULL, NULL),
(118, 'Green Cardomom SIVAKAMY 20X200 G', 'Premium quality green cardamom carefully selected for rich aroma, natural flavor, and freshness. Ideal for tea, biryani, curries, sweets, desserts, and spice blends. Hygienically packed to preserve authentic taste and long-lasting freshness. Suitable for home cooking, restaurants, and catering use.\r\n\r\nPack Size: 20 × 200g\r\nNet Weight: 4 KG\r\nBrand: SIVAKAMY\r\nStorage: Store in a cool, dry place away from direct sunlight.', 150.00, 160.00, 10, 'GINGENI7943', 67, 14, 'uploads/products/1766329345_0449321c-8ebf-4fcb-93c4-a86361a15609.jpeg', 'active', 0, '2025-08-24 06:45:43', '2026-05-08 05:00:15', '2025-08-24', '2026-02-22', 0, 18, 190.00, 175.00, 'VENTHAN TRADING PRIVATE LIMITED', 0, '', 5.00, 5.00),
(119, 'Evaporated Milch PEAK 24X170g', 'Creamy and rich evaporated milk made from high-quality milk for a smooth taste and perfect texture. Ideal for tea, coffee, desserts, baking, cooking, and traditional recipes. Carefully packed to maintain freshness, quality, and delicious flavor for everyday use.\r\n\r\nPack Size: 24 × 170g\r\nNet Weight: 4.08 KG\r\nBrand: PEAK\r\nStorage: Store in a cool, dry place. Refrigerate after opening and consume promptly.', 20.00, NULL, 10, 'KAIJAERIC8404', 45, 17, 'uploads/products/1776590145_PEAK_EVAPORATED_MILK_48X170_g.jpg', 'active', 0, '2025-08-24 06:53:24', '2026-05-08 05:00:55', '2025-08-24', '2026-10-24', 0, NULL, 43.00, 40.00, 'Fresh Tropical srl by Jawad', 0, '0402.9910', 15.00, 3.50),
(123, 'Instant Ingwer Getränke GOLD KILI 24X360G', 'Refreshing instant ginger drink made with quality ginger for a rich, warming taste and natural aroma. Perfect for enjoying hot or cold at any time of the day. Easy to prepare and ideal for home, office, and travel use. Carefully packed to maintain freshness and flavor.\r\n\r\nPack Size: 24 × 360g\r\nNet Weight: 8.64 KG\r\nBrand: GOLD KILI\r\nStorage: Store in a cool, dry place away from direct sunlight.', 69.00, NULL, 10, 'INSTANTIN9379', 62, 23, 'uploads/products/1770219495_GOLD_KILI_INSTANT_GINGER_DRINK_24X20X18g.jpg', 'active', 0, '2026-02-04 15:37:09', '2026-05-08 05:02:52', '2026-02-04', '2026-11-27', 0, 17, 69.00, 69.00, '', 0, '', NULL, NULL),
(120, 'Seeraka Sambareise SIVAKAMY 4X5 KG', 'Premium quality Seeraga Samba rice known for its fine texture, rich aroma, and delicious taste. Perfect for biryani, pulao, and traditional rice dishes. Carefully cleaned and hygienically packed to preserve freshness and authentic flavor for home and commercial cooking.\r\n\r\nPack Size: 4 × 5KG\r\nNet Weight: 20 KG\r\nBrand: SIVAKAMY\r\nStorage: Store in a cool, dry place away from moisture and direct sunlight.', 35.00, 5.00, 10, 'Jell', 72, 17, 'uploads/products/1770101465_Seeraka.jpg', 'active', 0, '2025-08-24 06:59:02', '2026-05-08 05:01:11', '2025-08-24', '2026-01-25', 0, 18, 55.00, 50.00, '', 0, '', NULL, NULL),
(121, 'Rotes Rohreise SP SIVAKAMY 4X5 KG', 'High-quality red raw rice carefully selected for its natural taste, rich texture, and nutritional value. Ideal for everyday meals and traditional dishes. Hygienically processed and packed to maintain freshness, purity, and authentic flavor.\r\n\r\nPack Size: 4 × 5KG\r\nNet Weight: 20 KG\r\nBrand: SIVAKAMY\r\nStorage: Store in a cool, dry place away from moisture and direct sunlight.', 25.00, 5.00, 8, 'KOKUHOS8958', 68, 17, 'uploads/products/1770101376_Rotes.jpg', 'active', 0, '2025-08-24 07:02:38', '2026-05-08 04:59:46', '2025-08-24', '2026-01-24', 0, 18, 35.00, 30.00, '', 0, '', NULL, NULL),
(124, 'Super Cream Cracker Biscuits Munchee 24X190 g', 'Crispy and delicious cream cracker biscuits with a light texture and rich taste. Perfect for tea time, snacks, and everyday enjoyment. Carefully packed to maintain freshness, crunchiness, and quality. Suitable for home, office, and sharing with family and friends.\r\n\r\nPack Size: 24 × 190g\r\nNet Weight: 4.56 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from direct sunlight.', 12.00, NULL, 1, 'CBL25EM01', 73, 41, 'uploads/products/1770290668_Munchee_Super_Cream_Cracker_Biscuits_24X190_g.jpg', 'active', 0, '2026-02-05 06:02:06', '2026-05-08 05:03:22', '2026-02-05', NULL, 0, 18, 12.00, 12.00, '', 0, '', NULL, NULL),
(125, 'PG Tips 80 Teebeutel 12X232 g', 'Premium quality tea bags blended for a rich aroma, smooth taste, and refreshing flavor in every cup. Perfect for daily tea enjoyment at home, office, or hospitality use. Conveniently packed to preserve freshness and deliver consistent quality with every brew.\r\n\r\nPack Size: 12 × 232g\r\nContents: 80 Tea Bags per pack\r\nNet Weight: 2.784 KG\r\nBrand: PG Tips\r\nStorage: Store in a cool, dry place away from moisture and strong odors.', 30.00, 33.00, 15, 'PGTIPS80152', 62, 34, 'uploads/products/1770290152_PG Tips 80 Teebeutel 12X232 g.jpg', 'active', 0, '2026-02-05 11:15:52', '2026-05-08 05:03:56', '2026-02-05', '2027-01-29', 0, 18, 54.00, 30.00, 'Fresh Tropical srl by Jawad', 0, '', NULL, NULL),
(126, 'PG Tips 40 Teebeutel 12X116 g', 'Premium quality tea bags specially blended for a rich aroma, smooth taste, and refreshing tea experience. Ideal for everyday use at home, office, and catering purposes. Hygienically packed to maintain freshness and consistent flavor in every cup.\r\n\r\nPack Size: 12 × 116g\r\nContents: 40 Tea Bags per pack\r\nNet Weight: 1.392 KG\r\nBrand: PG Tips\r\nStorage: Store in a cool, dry place away from moisture and direct sunlight.', 12.00, 15.00, 5, 'PGTIPS40513', 62, 34, 'uploads/products/1770290513_PG Tips 40 Teebeutel 12X116 g.jpg', 'active', 0, '2026-02-05 11:21:53', '2026-05-08 05:04:52', '2026-02-05', '2026-12-26', 0, 18, 27.00, 12.00, 'Fresh Tropical srl by Jawad', 0, '', NULL, NULL),
(127, 'Lipton Yellow Label Schwarztee 100 Beutel 12X200 g', 'Classic black tea blended for a rich aroma, smooth flavor, and refreshing taste in every cup. Ideal for daily tea enjoyment at home, office, restaurants, and catering use. Carefully packed to preserve freshness and consistent quality.\r\n\r\nPack Size: 12 × 200g\r\nContents: 100 Tea Bags per pack\r\nNet Weight: 2.4 KG\r\nBrand: Lipton\r\nStorage: Store in a cool, dry place away from moisture and strong odors.', 30.00, 33.00, 5, 'LIPTONYEL5599', 62, 26, 'uploads/products/1770305599_Lipton Yellow Label Schwarztee 100 Beutel 12X200 g.jpg', 'active', 0, '2026-02-05 15:33:19', '2026-05-08 05:05:19', '2026-02-05', '2027-01-03', 0, 18, 45.00, 30.00, 'Fresh Tropical srl by Jawad', 0, '', NULL, NULL),
(128, 'Bran Cream Crackers MUNCHEE 24X240g', 'Crunchy and tasty bran cream crackers made with quality ingredients for a light, satisfying snack. Ideal for tea time, breakfast, and everyday snacking. Carefully packed to maintain freshness, crispiness, and flavor for the whole family.\r\n\r\nPack Size: 24 × 240g\r\nNet Weight: 5.76 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from direct sunlight and moisture.', 20.00, NULL, 1, 'BRANCREAMC78976', 73, 41, 'uploads/products/1772357522_Bran_Cream_Crackers_MUNCHEE_24X240g.webp', 'active', 0, '2026-02-24 15:04:41', '2026-05-08 05:06:02', '2026-02-24', NULL, 0, 18, 27.90, 20.00, '', 0, '', NULL, NULL),
(129, 'Chocolate Cream Biskuit MUNCHEE 4X400g CBL8', 'Delicious chocolate cream biscuits with crispy layers and smooth chocolate filling for a rich and satisfying taste. Perfect for tea time, snacks, parties, and sharing with family and friends. Hygienically packed to preserve freshness, crunchiness, and flavor.\r\n\r\nPack Size: 8 × 400g\r\nNet Weight: 3.2 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from direct sunlight and heat.', 5.00, NULL, 1, 'CHOCOLATEC17417', 73, 41, 'uploads/products/1772357585_Chocolate_Cream_Biskuit_MUNCHEE_4X400g.webp', 'active', 0, '2026-02-24 15:07:21', '2026-05-08 05:06:30', '2026-02-24', '2026-03-13', 0, 18, 10.00, 5.00, '', 0, '', NULL, NULL),
(130, 'Chocolate Cream Biskuit MUNCHEE 50X100g', 'Tasty chocolate cream biscuits with crispy biscuit layers and smooth chocolate cream filling. Perfect for tea time, school snacks, travel, and everyday enjoyment. Carefully packed to maintain freshness, flavor, and crunchiness.\r\n\r\nPack Size: 50 × 100g\r\nNet Weight: 5 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from direct sunlight and moisture.', 30.00, NULL, 1, 'CHOCOLATEC14662', 73, 41, 'uploads/products/1771945745_114797--01--1623926470.webp', 'active', 0, '2026-02-24 15:09:05', '2026-05-08 05:07:13', '2026-02-24', NULL, 0, 18, 35.00, 30.00, '', 0, '', NULL, NULL),
(131, 'Chocolate Marie MUNCHEE 6X400g', 'Crunchy Marie biscuits coated with delicious chocolate flavor for a rich and enjoyable snack experience. Perfect for tea time, desserts, and everyday snacking. Hygienically packed to preserve freshness, taste, and crispiness.\r\n\r\nPack Size: 6 × 400g\r\nNet Weight: 2.4 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from moisture and direct sunlight.', 8.00, NULL, 1, 'CHOCOLATEM6488', 73, 41, 'uploads/products/1772357700_Chocolate_Marie_MUNCHEE_6X400g.webp', 'active', 0, '2026-02-24 15:10:48', '2026-05-08 05:07:48', '2026-02-24', NULL, 0, 18, 13.90, 8.00, '', 0, '', NULL, NULL),
(132, 'Chocolate Puff Biscuits MUNCHEE 24X200g', '', 17.00, NULL, 1, 'CHOCOLATEP90558', 73, 41, 'uploads/products/1772357823_Chocolate_Puff_Biscuits_MUNCHEE_24X200g.webp', 'active', 0, '2026-02-24 15:13:37', '2026-03-01 09:37:03', '2026-02-24', NULL, 0, 18, 22.00, 17.00, '', 0, NULL, NULL, NULL),
(133, 'Chocolate Puff Biscuits MUNCHEE 48X100g', 'Light and crispy puff biscuits filled with delicious chocolate flavor for a sweet and satisfying snack. Perfect for tea time, school snacks, parties, and everyday enjoyment. Carefully packed to maintain freshness, crunchiness, and rich taste.\r\n\r\nPack Size: 48 × 100g\r\nNet Weight: 4.8 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from heat and direct sunlight.', 26.00, NULL, 1, 'CHOCOLATEP86927', 73, 41, 'uploads/products/1772357885_Chocolate_Puff_Biscuits_MUNCHEE_48X100g.webp', 'active', 0, '2026-02-24 15:15:31', '2026-05-08 05:08:27', '2026-02-24', NULL, 0, 18, 31.00, 26.00, '', 0, '', NULL, NULL),
(134, 'Coconut Crunch MUNCHEE 24X200g', 'Crunchy coconut biscuits made with delicious coconut flavor for a rich and satisfying taste. Perfect for tea time, snacks, and sharing with family and friends. Hygienically packed to preserve freshness, crispiness, and authentic flavor.\r\n\r\nPack Size: 24 × 200g\r\nNet Weight: 4.8 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from moisture and direct sunlight.', 39.00, NULL, 0, 'COCONUTCRU29647', 73, NULL, 'uploads/products/1772358052_Coconut_Crunch_MUNCHEE_24X200g.webp', 'active', 0, '2026-03-01 09:40:34', '2026-05-08 05:09:09', '2026-03-01', NULL, 0, NULL, 39.00, 0.00, '', 0, '', NULL, NULL),
(135, 'Coconut Magic Biskuit MUNCHEE 48X75g', 'Delicious coconut-flavored biscuits with a crunchy texture and rich taste, perfect for everyday snacking and tea time. Carefully packed to maintain freshness, crispiness, and flavor. Ideal for home, school, office, and travel use.\r\n\r\nPack Size: 48 × 75g\r\nNet Weight: 3.6 KG\r\nBrand: Munchee\r\nStorage: Store in a cool, dry place away from heat and moisture.', 26.00, NULL, 0, 'COCONUTMAG21119', 73, NULL, 'uploads/products/1772358327_114829--01--1623926499.jpg', 'active', 0, '2026-03-01 09:45:27', '2026-05-08 05:09:43', '2026-03-01', NULL, 0, NULL, 26.00, 0.00, '', 0, '', NULL, NULL),
(136, 'Custard Cream MUNCHEE 24X210g', '', 23.00, NULL, 0, 'CUSTARDCR58444', 73, NULL, 'uploads/products/1772358444_Custard_Cream_MUNCHEE_24X210g.jpg', 'active', 0, '2026-03-01 09:47:24', '2026-03-01 09:47:24', '2026-03-01', NULL, 0, NULL, 23.00, 0.00, '', 0, NULL, NULL, NULL),
(137, 'Custard Cream MUNCHEE 48X100g', '', 32.00, NULL, 0, 'CUSTARDCR58531', 73, NULL, 'uploads/products/1772358531_Custard_Cream_MUNCHEE_48X100g.jpg', 'active', 0, '2026-03-01 09:48:51', '2026-03-01 09:48:51', '2026-03-01', NULL, 0, NULL, 32.00, 0.00, '', 0, NULL, NULL, NULL),
(138, 'Delight Assortment Biscuits MUNCHEE 6X500g', '', 33.00, NULL, 0, 'DELIGHTAS58636', 73, NULL, 'uploads/products/1772358636_Delight_Assortment_Biscuits_MUNCHEE_6X500g.jpg', 'active', 0, '2026-03-01 09:50:36', '2026-03-01 09:50:36', '2026-03-01', NULL, 0, NULL, 33.00, 0.00, '', 0, NULL, NULL, NULL),
(139, 'Family Selection GOLD MUNCHEE 6X1kg', '', 33.00, NULL, 0, 'FAMILYSEL58699', 73, NULL, 'uploads/products/1772358699_Family_Selection_GOLD_MUNCHEE_6X1kg.jpg', 'active', 0, '2026-03-01 09:51:39', '2026-03-01 09:51:39', '2026-03-01', NULL, 0, NULL, 33.00, 0.00, '', 0, NULL, NULL, NULL),
(140, 'Gem Biskuit MUNCHEE 24X200g', '', 28.00, NULL, 0, 'GEMBISKUI58836', 73, NULL, 'uploads/products/1772358836_Gem_Biskuit_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 09:53:56', '2026-03-01 09:53:56', '2026-03-01', NULL, 0, NULL, 28.00, 0.00, '', 0, NULL, NULL, NULL),
(141, 'Gem Biskuit MUNCHEE 4X400g', '', 24.80, NULL, 0, 'GEMBISKUI59145', 73, NULL, 'uploads/products/1772359145_Gem_Biskuit_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 09:59:05', '2026-03-01 09:59:05', '2026-03-01', NULL, 0, NULL, 24.80, 0.00, '', 0, NULL, NULL, NULL),
(142, 'Gift Assortment Biscuits MUNCHEE 12X400g', '', 33.00, NULL, 0, 'GIFTASSORT527', 73, NULL, 'uploads/products/1772359210_Gift_Assortment_Biscuits_MUNCHEE_12X400g.jpg', 'active', 0, '2026-03-01 10:00:10', '2026-03-01 10:00:10', '2026-03-01', NULL, 0, NULL, 33.00, 0.00, '', 0, NULL, NULL, NULL),
(143, 'Hawaian Cookies MUNCHEE 24X200g', '', 32.50, NULL, 0, 'HAWAIANCOO67327', 73, NULL, 'uploads/products/1772359280_Hawaian_Cookies_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:01:21', '2026-03-01 10:01:21', '2026-03-01', NULL, 0, NULL, 32.50, 0.00, '', 0, NULL, NULL, NULL),
(144, 'Hawaian Cookies MUNCHEE 24X200g', '', 35.00, NULL, 0, 'HAWAIANCO59438', 73, NULL, 'uploads/products/1772359438_Hawaian_Cookies_MUNCHEE_48X100g.jpg', 'active', 0, '2026-03-01 10:03:58', '2026-03-01 10:03:58', '2026-03-01', NULL, 0, NULL, 35.00, 0.00, '', 0, NULL, NULL, NULL),
(145, 'Ingwer Cookies MUNCHEE 24X170g', '', 25.50, NULL, 0, 'INGWERCOOK60575', 73, NULL, 'uploads/products/1772359671_Ingwer_Cookies_MUNCHEE_24X170g.jpg', 'active', 0, '2026-03-01 10:07:51', '2026-03-01 10:07:51', '2026-03-01', NULL, 0, NULL, 25.50, 0.00, '', 0, NULL, NULL, NULL),
(146, 'Ingwer Cookies MUNCHEE 4X400g', '', 12.50, NULL, 0, 'INGWERCOOK24184', 73, NULL, 'uploads/products/1772359758_Ingwer_Cookies_MUNCHEE_4X400g.jpg', 'active', 0, '2026-03-01 10:09:18', '2026-03-01 10:09:18', '2026-03-01', NULL, 0, NULL, 12.50, 0.00, '', 0, NULL, NULL, NULL),
(147, 'Kalo Chocolate MUNCHEE 24X140g', '', 42.00, NULL, 0, 'KALOCHOCO59835', 73, NULL, 'uploads/products/1772359835_Kalo_Chocolate_MUNCHEE_24X140g.jpg', 'active', 0, '2026-03-01 10:10:35', '2026-03-01 10:10:35', '2026-03-01', NULL, 0, NULL, 42.00, 0.00, '', 0, NULL, NULL, NULL),
(148, 'Kalo Strawberry MUNCHEE 24X140g', '', 42.00, NULL, 0, 'KALOSTRAWB26049', 73, NULL, 'uploads/products/1772359943_Kalo_Strawberry_MUNCHEE_24X140g.jpg', 'active', 0, '2026-03-01 10:12:23', '2026-03-01 10:12:23', '2026-03-01', NULL, 0, NULL, 42.00, 0.00, '', 0, NULL, NULL, NULL),
(149, 'Kalo Vanilla MUNCHEE 24X140g', '', 42.00, NULL, 0, 'KALOVANIL60002', 73, NULL, 'uploads/products/1772360002_Kalo_Vanilla_MUNCHEE_24X140g.jpg', 'active', 0, '2026-03-01 10:13:22', '2026-03-01 10:13:22', '2026-03-01', NULL, 0, NULL, 42.00, 0.00, '', 0, NULL, NULL, NULL),
(150, 'Karapincha-Curryleaves Biskuit MUNCHEE 12X100g', '', 11.50, NULL, 0, 'KARAPINCHA60055', 73, NULL, 'uploads/products/1772360055_Karapincha-Curryleaves_Biskuit_MUNCHEE_12X100g.jpg', 'active', 0, '2026-03-01 10:14:15', '2026-03-01 10:14:15', '2026-03-01', NULL, 0, NULL, 11.50, 0.00, '', 0, NULL, NULL, NULL),
(151, 'Kurakkan Cream Crackers MUNCHEE 24X100g', '', 21.50, NULL, 0, 'KURAKKANC60173', 73, NULL, 'uploads/products/1772360173_Kurakkan_Cream_Crackers_MUNCHEE_24X100g.jpg', 'active', 0, '2026-03-01 10:16:13', '2026-03-01 10:16:13', '2026-03-01', NULL, 0, NULL, 21.50, 0.00, '', 0, NULL, NULL, NULL),
(152, 'Lemon Puff Biscuits MUNCHEE 24X200g', '', 22.00, NULL, 0, 'LEMONPUFF60215', 73, NULL, 'uploads/products/1772360215_Lemon_Puff_Biscuits_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:16:55', '2026-03-01 10:16:55', '2026-03-01', NULL, 0, NULL, 22.00, 0.00, '', 0, NULL, NULL, NULL),
(153, 'Lemon Puff Biscuits MUNCHEE 48X100g', '', 29.00, NULL, 0, 'LEMONPUFF60255', 73, NULL, 'uploads/products/1772360255_Lemon_Puff_Biscuits_MUNCHEE_48X100g.jpg', 'active', 0, '2026-03-01 10:17:35', '2026-03-01 10:17:35', '2026-03-01', NULL, 0, NULL, 29.00, 0.00, '', 0, NULL, NULL, NULL),
(154, 'Lite Marie MUNCHEE 4X250g', '', 5.90, NULL, 0, 'LITEMARIE60294', 73, NULL, 'uploads/products/1772360294_Lite_Marie_MUNCHEE_4X250g.jpg', 'active', 0, '2026-03-01 10:18:14', '2026-03-01 10:18:14', '2026-03-01', NULL, 0, NULL, 5.90, 0.00, '', 0, NULL, NULL, NULL),
(155, 'Marie Biscuits MUNCHEE 12X400g', '', 29.90, NULL, 0, 'MARIEBISC60353', 73, NULL, 'uploads/products/1772360353_Marie_Biscuits_MUNCHEE_12X400g.jpg', 'active', 0, '2026-03-01 10:19:13', '2026-03-01 10:19:13', '2026-03-01', NULL, 0, NULL, 29.90, 0.00, '', 0, NULL, NULL, NULL),
(156, 'Milk Short Cake Biscuits MUNCHEE 24X200g', '', 28.00, NULL, 0, 'MILKSHORT60393', 73, NULL, 'uploads/products/1772360393_Milk_Short_Cake_Biscuits_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:19:53', '2026-03-01 10:19:53', '2026-03-01', NULL, 0, NULL, 28.00, 0.00, '', 0, NULL, NULL, NULL),
(157, 'Milk Short Cake Biscuits MUNCHEE 48X85g', '', 30.00, NULL, 0, 'MILKSHORT60435', 73, NULL, 'uploads/products/1772360435_Milk_Short_Cake_Biscuits_MUNCHEE_48X85g.jpg', 'active', 0, '2026-03-01 10:20:35', '2026-03-01 10:20:35', '2026-03-01', NULL, 0, NULL, 30.00, 0.00, '', 0, NULL, NULL, NULL),
(158, 'Moringa Leaves Biskuit MUNCHEE 12X100g', '', 14.40, NULL, 0, 'MORINGALE60542', 73, NULL, 'uploads/products/1772360542_Moringa_Leaves_Biskuit_MUNCHEE_12X100g.jpg', 'active', 0, '2026-03-01 10:22:22', '2026-03-01 10:22:22', '2026-03-01', NULL, 0, NULL, 14.40, 0.00, '', 0, NULL, NULL, NULL),
(159, 'Nice MUNCHEE 24X200g', '', 23.50, NULL, 0, 'NICEMUNCH60620', 73, NULL, 'uploads/products/1772360620_Nice_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:23:40', '2026-03-01 10:23:40', '2026-03-01', NULL, 0, NULL, 23.50, 0.00, '', 0, NULL, NULL, NULL),
(160, 'Samaposa Cerealg Powder BOX MUNCHEE 24X200g', '', 32.00, NULL, 0, 'SAMAPOSAC60665', 73, NULL, 'uploads/products/1772360665_Samaposa_Cerealg_Powder_BOX_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:24:25', '2026-03-01 10:24:25', '2026-03-01', NULL, 0, NULL, 32.00, 0.00, '', 0, NULL, NULL, NULL),
(161, 'Orange Cream Biskuit MUNCHEE 6X400g', '', 14.50, NULL, 0, 'ORANGECRE60763', 73, NULL, 'uploads/products/1772360763_Orange_Cream_Biskuit_MUNCHEE_6X400g.jpg', 'active', 0, '2026-03-01 10:26:03', '2026-03-01 10:26:03', '2026-03-01', NULL, 0, NULL, 14.50, 0.00, '', 0, NULL, NULL, NULL),
(162, 'Orange Cream Biskuit MUNCHEE 50X100g', '', 29.00, NULL, 0, 'ORANGECRE60821', 73, NULL, 'uploads/products/1772360821_Orange_Cream_Biskuit_MUNCHEE_50X100g.jpg', 'active', 0, '2026-03-01 10:27:01', '2026-03-01 10:27:01', '2026-03-01', NULL, 0, NULL, 29.00, 0.00, '', 0, NULL, NULL, NULL),
(163, 'Nice MUNCHEE 4X400g', '', 8.50, NULL, 0, 'NICEMUNCH60861', 73, NULL, 'uploads/products/1772360861_Nice_MUNCHEE_4X400g.jpg', 'active', 0, '2026-03-01 10:27:41', '2026-03-01 10:27:41', '2026-03-01', NULL, 0, NULL, 8.50, 0.00, '', 0, NULL, NULL, NULL),
(164, 'Savoury Crevo Biscuits MUNCHEE 24X170g', '', 34.00, NULL, 0, 'SAVOURYCR60924', 73, NULL, 'uploads/products/1772360924_Savoury_Crevo_Biscuits_MUNCHEE_24X170g.jpg', 'active', 0, '2026-03-01 10:28:44', '2026-03-01 10:28:44', '2026-03-01', NULL, 0, NULL, 34.00, 0.00, '', 0, NULL, NULL, NULL),
(165, 'Samaposa Cerealg mit KURAKAN MUNCHEE 25X200g', '', 32.00, NULL, 0, 'SAMAPOSAC60981', 73, NULL, 'uploads/products/1772360981_Samaposa_Cerealg_mit_KURAKAN_MUNCHEE_25X200g.jpg', 'active', 0, '2026-03-01 10:29:41', '2026-03-01 10:29:41', '2026-03-01', NULL, 0, NULL, 32.00, 0.00, '', 0, NULL, NULL, NULL),
(166, 'Samaposa Cerealg REDY Mix MUNCHEE 12X250g', '', 26.50, NULL, 0, 'SAMAPOSAC61023', 73, NULL, 'uploads/products/1772361023_Samaposa_Cerealg_REDY_Mix_MUNCHEE_12X250g.jpg', 'active', 0, '2026-03-01 10:30:23', '2026-03-01 10:30:23', '2026-03-01', NULL, 0, NULL, 26.50, 0.00, '', 0, NULL, NULL, NULL),
(167, 'Samaposa Cerealg Powder MUNCHEE 75X200g', '', 80.00, NULL, 0, 'SAMAPOSAC61079', 73, NULL, 'uploads/products/1772361079_Samaposa_Cerealg_Powder_MUNCHEE_75X200g.jpg', 'active', 0, '2026-03-01 10:31:19', '2026-03-01 10:31:19', '2026-03-01', NULL, 0, NULL, 80.00, 0.00, '', 0, NULL, NULL, NULL),
(168, 'Samaposa Cerealg Powder MUNCHEE 25X200g', '', 27.00, NULL, 0, 'SAMAPOSAC61158', 73, NULL, 'uploads/products/1772361158_Samaposa_Cerealg_Powder_MUNCHEE_25X200g.jpg', 'active', 0, '2026-03-01 10:32:38', '2026-03-01 10:32:38', '2026-03-01', NULL, 0, NULL, 27.00, 0.00, '', 0, NULL, NULL, NULL),
(169, 'Sun Crackers-Salt und Pepper MUNCHEE 24X95g', '', 13.50, NULL, 0, 'SUNCRACKE61206', 73, NULL, 'uploads/products/1772361206_Sun_Crackers-Salt_und_Pepper_MUNCHEE_24X95g.jpg', 'active', 0, '2026-03-01 10:33:26', '2026-03-01 10:33:26', '2026-03-01', NULL, 0, NULL, 13.50, 0.00, '', 0, NULL, NULL, NULL),
(170, 'Savoury Snack Cracker Biscuits MUNCHEE 24X170g', '', 34.00, NULL, 0, 'SAVOURYSN61241', 73, NULL, 'uploads/products/1772361241_Savoury_Snack_Cracker_Biscuits_MUNCHEE_24X170g.jpg', 'active', 0, '2026-03-01 10:34:01', '2026-03-01 10:34:01', '2026-03-01', NULL, 0, NULL, 34.00, 0.00, '', 0, NULL, NULL, NULL),
(171, 'Savoury Onion Biscuits MUNCHEE 24X170g', '', 34.00, NULL, 0, 'SAVOURYON61279', 73, NULL, 'uploads/products/1772361279_Savoury_Onion_Biscuits_MUNCHEE_24X170g.jpg', 'active', 0, '2026-03-01 10:34:39', '2026-03-01 10:34:39', '2026-03-01', NULL, 0, NULL, 34.00, 0.00, '', 0, NULL, NULL, NULL),
(172, 'Savoury Nuts Chili Biscuits MUNCHEE 24X170g', '', 34.00, NULL, 0, 'SAVOURYNU61325', 73, NULL, 'uploads/products/1772361325_Savoury_Nuts_Chili_Biscuits_MUNCHEE_24X170g.jpg', 'active', 0, '2026-03-01 10:35:25', '2026-03-01 10:35:25', '2026-03-01', NULL, 0, NULL, 34.00, 0.00, '', 0, NULL, NULL, NULL),
(173, 'Wafer Chocolate MUNCHEE 24X200g', '', 38.50, NULL, 0, 'WAFERCHOC61360', 73, NULL, 'uploads/products/1772361360_Wafer_Chocolate_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:36:00', '2026-03-01 10:36:00', '2026-03-01', NULL, 0, NULL, 38.50, 0.00, '', 0, NULL, NULL, NULL),
(174, 'Tikiri Marie MUNCHEE 4X360g', '', 7.90, NULL, 0, 'TIKIRIMAR61400', 73, NULL, 'uploads/products/1772361400_Tikiri_Marie_MUNCHEE_4X360g.jpg', 'active', 0, '2026-03-01 10:36:40', '2026-03-01 10:36:40', '2026-03-01', NULL, 0, NULL, 7.90, 0.00, '', 0, NULL, NULL, NULL),
(175, 'Tea Time Biscuits MUNCHEE 24X200g', '', 38.50, NULL, 0, 'TEATIMEB61464', 73, NULL, 'uploads/products/1772361464_Tea_Time_Biscuits_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:37:44', '2026-03-01 10:37:44', '2026-03-01', NULL, 0, NULL, 38.50, 0.00, '', 0, NULL, NULL, NULL),
(176, 'Super Cream Crackers MUNCHEE 24X190g', '', 22.50, NULL, 0, 'SUPERCREA61543', 73, NULL, 'uploads/products/1772361543_Super_Cream_Crackers_MUNCHEE_24X190g.jpg', 'active', 0, '2026-03-01 10:39:03', '2026-03-01 10:39:03', '2026-03-01', NULL, 0, NULL, 22.50, 0.00, '', 0, NULL, NULL, NULL),
(177, 'Wafer Lemon MUNCHEE 24X200g', '', 41.50, NULL, 0, 'WAFERLEMO61609', 73, NULL, 'uploads/products/1772361609_Wafer_Lemon_MUNCHEE_24X200g.jpg', 'active', 0, '2026-03-01 10:40:09', '2026-03-01 10:40:09', '2026-03-01', NULL, 0, NULL, 41.50, 0.00, '', 0, NULL, NULL, NULL),
(178, 'Black Tea 6x40g', '', 28.00, NULL, 0, 'BLACKTEA26394', 62, NULL, 'uploads/products/1773126394_Black_Tea_6___40g.jpg', 'active', 0, '2026-03-10 07:06:34', '2026-03-10 07:06:34', '2026-03-10', NULL, 0, NULL, 28.00, 0.00, '', 0, NULL, NULL, NULL),
(179, 'Dimbula Cylon Black Tea CHELIZA 30X100g', '', 98.00, NULL, 0, 'DIMBULACY26500', 62, NULL, 'uploads/products/1773126500_Dimbula_Cylon_Black_Tea_CHELIZA_30X100g.jpg', 'active', 0, '2026-03-10 07:08:20', '2026-03-10 07:08:20', '2026-03-10', NULL, 0, NULL, 98.00, 0.00, '', 0, NULL, NULL, NULL),
(180, 'Masala Tea Red Label BROOKE BOND 24X200g', '', 67.00, NULL, 0, 'MASALATEA26629', 62, NULL, 'uploads/products/1773126629_Masala_Tea_Red_Label_BROOKE_BOND_24X200g.jpg', 'active', 0, '2026-03-10 07:10:30', '2026-03-10 07:10:30', '2026-03-10', NULL, 0, NULL, 67.00, 0.00, '', 0, NULL, NULL, NULL),
(181, 'Masala Tea-Gewürzmischung Tee WAGH BAKRI 20X250g', '', 75.00, NULL, 0, 'MASALATEA26817', 62, NULL, 'uploads/products/1773126817_Masala_Tea-Gew__rzmischung_Tee_WAGH_BAKRI_20X250g.jpg', 'active', 0, '2026-03-10 07:13:37', '2026-03-10 07:13:37', '2026-03-10', NULL, 0, NULL, 75.00, 0.00, '', 0, NULL, NULL, NULL),
(182, 'Nuwara Eliya Cylon Black Tea CHELIZA 30X100g', '', 98.00, NULL, 0, 'NUWARAELI26894', 62, NULL, 'uploads/products/1773126894_Nuwara_Eliya_Cylon_Black_Tea_CHELIZA_30X100g.jpg', 'active', 0, '2026-03-10 07:14:54', '2026-03-10 07:14:54', '2026-03-10', NULL, 0, NULL, 98.00, 0.00, '', 0, NULL, NULL, NULL),
(183, 'Red Lebel Brooke Bond Tea 900g', '', 105.00, NULL, 0, 'REDLEBEL27030', 62, NULL, 'uploads/products/1773127030_Red_Lebel_Brooke_Bond_Tea_900g.jpg', 'active', 0, '2026-03-10 07:17:10', '2026-03-10 07:17:10', '2026-03-10', NULL, 0, NULL, 105.00, 0.00, '', 0, NULL, NULL, NULL),
(184, 'Premium Black Tea WAGH BAKRI 12X450g', '', 68.00, NULL, 0, 'REMIUMBLA27660', 62, NULL, 'uploads/products/1773127660_remium_Black_Tea_WAGH_BAKRI_12X450g.jpg', 'active', 0, '2026-03-10 07:27:40', '2026-03-10 13:39:36', '2026-03-10', NULL, 0, NULL, 68.00, 0.00, '', 0, NULL, NULL, NULL),
(185, 'Schwarz Tea Lose RED LABEL 24X500g', '', 130.00, NULL, 0, 'SCHWARZTEA75481', 62, NULL, 'uploads/products/1773153296_Schwarz_Tea_Lose_RED_LABEL_24X500g.jpg', 'active', 0, '2026-03-10 13:29:37', '2026-03-10 14:34:56', '2026-03-10', NULL, 0, NULL, 130.00, 0.00, '', 0, NULL, NULL, NULL),
(186, 'Uda Pusselawa Cylon Black Tea CHELIZA 30X100g', '', 98.00, NULL, 0, 'UDAPUSSEL49646', 62, NULL, 'uploads/products/1773153371_Uda_Pusselawa_Cylon_Black_Tea_CHELIZA_30X100g.webp', 'active', 0, '2026-03-10 13:34:06', '2026-03-10 14:36:11', '2026-03-10', NULL, 0, NULL, 98.00, 0.00, '', 0, NULL, NULL, NULL),
(187, 'Uda Pusselawa Cylon Black Tea CHELIZA 30X100g', '', 98.00, NULL, 0, 'UDAPUSSEL49759', 62, NULL, NULL, 'inactive', 0, '2026-03-10 13:35:59', '2026-03-10 14:36:34', '2026-03-10', NULL, 0, NULL, 98.00, 0.00, '', 0, NULL, NULL, NULL),
(188, 'Uva Cylon Black Tea CHELIZA 30X100g', '', 98.00, NULL, 0, 'UVACYLON49804', 62, NULL, 'uploads/products/1773153580_Uva_Cylon_Black_Tea_CHELIZA_30X100g.webp', 'active', 0, '2026-03-10 13:36:44', '2026-03-10 14:39:40', '2026-03-10', NULL, 0, NULL, 98.00, 0.00, '', 0, NULL, NULL, NULL),
(189, 'Schwarztee Pulver Premium PET TATA 12X1KG', '', 130.00, NULL, 0, 'SCHWARZTEE53674', 62, NULL, 'uploads/products/1773153674_Schwarztee_Pulver_Premium____PET____TATA_12X1KG.jpg', 'active', 0, '2026-03-10 14:41:14', '2026-03-10 14:41:14', '2026-03-10', NULL, 0, NULL, 130.00, 0.00, '', 0, NULL, NULL, NULL),
(190, 'Schwarztee Pulver Premium PET TATA 24X250g', '', 75.00, NULL, 0, 'SCHWARZTEE53757', 62, NULL, 'uploads/products/1773153757_Schwarztee_Pulver_Premium_PET_TATA_24X250g.jpg', 'active', 0, '2026-03-10 14:42:38', '2026-03-10 14:42:38', '2026-03-10', NULL, 0, NULL, 75.00, 0.00, '', 0, NULL, NULL, NULL),
(191, 'Schwarztee Pulver Premium PET TATA 24X500g', '', 139.00, NULL, 0, 'SCHWARZTEE53864', 62, NULL, 'uploads/products/1773153864_Schwarztee_Pulver_Premium_PET_TATA_24X500g.jpg', 'active', 0, '2026-03-10 14:44:24', '2026-03-10 14:44:24', '2026-03-10', NULL, 0, NULL, 139.00, 0.00, '', 0, NULL, NULL, NULL),
(192, 'Schwarztee 160 Beutel PG TIPS 8X500g', '', 52.00, NULL, 0, 'SCHWARZTEE53912', 62, NULL, 'uploads/products/1773153912_Schwarztee_160_Beutel_PG_TIPS_8X500g.jpg', 'active', 0, '2026-03-10 14:45:12', '2026-03-10 14:45:12', '2026-03-10', NULL, 0, NULL, 52.00, 0.00, '', 0, NULL, NULL, NULL),
(193, 'Schwarztee 240 Beutel PG TIPS 12X696g', '', 39.00, NULL, 0, 'SCHWARZTEE53996', 62, NULL, 'uploads/products/1773153996_Schwarztee_240_Beutel_PG_TIPS_12X696g.jpg', 'active', 0, '2026-03-10 14:46:36', '2026-03-10 14:46:36', '2026-03-10', NULL, 0, NULL, 39.00, 0.00, '', 0, NULL, NULL, NULL),
(194, 'Schwarztee 40 Beutel PG TIPS 12X116g', '', 29.00, NULL, 0, 'SCHWARZTEE54049', 62, NULL, 'uploads/products/1773154049_Schwarztee_40_Beutel_PG_TIPS_12X116g.jpg', 'active', 0, '2026-03-10 14:47:30', '2026-03-10 14:47:30', '2026-03-10', NULL, 0, NULL, 29.00, 0.00, '', 0, NULL, NULL, NULL),
(195, 'Schwarztee 80 Beutel PG TIPS 12X232g', '', 53.00, NULL, 0, 'SCHWARZTEE54118', 62, NULL, 'uploads/products/1773154118_Schwarztee_80_Beutel_PG_TIPS_12X232g.jpg', 'active', 0, '2026-03-10 14:48:38', '2026-03-10 14:48:38', '2026-03-10', NULL, 0, NULL, 53.00, 0.00, '', 0, NULL, NULL, NULL),
(196, 'Schwarztee Loose PG TIPS 4X1.5kg', '', 52.00, NULL, 0, 'SCHWARZTEE54172', 62, NULL, 'uploads/products/1773154172_Schwarztee_Loose_PG_TIPS_4X1.5kg.jpg', 'active', 0, '2026-03-10 14:49:32', '2026-03-10 14:49:32', '2026-03-10', NULL, 0, NULL, 52.00, 0.00, '', 0, NULL, NULL, NULL),
(197, 'Schwarztee PG TIPS 8X300', '', 79.00, NULL, 0, 'SCHWARZTEE54293', 62, NULL, 'uploads/products/1773154293_Schwarztee_PG_TIPS_8X300.jpg', 'active', 0, '2026-03-10 14:51:33', '2026-03-10 14:51:33', '2026-03-10', NULL, 0, NULL, 79.00, 0.00, '', 0, NULL, NULL, NULL),
(198, 'Ingwertee Beutel GOLD KILI 24X180g', '', 57.00, NULL, 0, 'INGWERTEE54385', 62, NULL, 'uploads/products/1773155070_Ingwertee_Beutel_GOLD_KILI_24X180g.jpg', 'active', 0, '2026-03-10 14:53:05', '2026-03-10 15:04:30', '2026-03-10', NULL, 0, NULL, 57.00, 0.00, '', 0, NULL, NULL, NULL),
(199, 'Ingwertee Beutel GOLD KILI 24X360g', '', 85.00, NULL, 0, 'INGWERTEE54449', 62, NULL, 'uploads/products/1773154449_Ingwertee_Beutel_GOLD_KILI_24X360g.jpg', 'active', 0, '2026-03-10 14:54:09', '2026-03-10 14:54:09', '2026-03-10', NULL, 0, NULL, 85.00, 0.00, '', 0, NULL, NULL, NULL),
(200, 'Ingwertee Zuckerfrei Beutel GOLD KILI 24X50g', '', 57.00, NULL, 0, 'INGWERTEE54530', 62, NULL, 'uploads/products/1773154530_Ingwertee_Zuckerfrei_Beutel_GOLD_KILI_24X50g.jpg', 'active', 0, '2026-03-10 14:55:30', '2026-03-10 14:55:30', '2026-03-10', NULL, 0, NULL, 57.00, 0.00, '', 0, NULL, NULL, NULL),
(201, 'Ingwertee+Zitrone Beutel GOLD KILI 24X180g', '', 59.00, NULL, 0, 'INGWERTEE54651', 62, NULL, 'uploads/products/1773154651_Ingwertee_Zitrone_Beutel_GOLD_KILI_24X180g.jpg', 'active', 0, '2026-03-10 14:57:31', '2026-03-10 14:57:31', '2026-03-10', NULL, 0, NULL, 59.00, 0.00, '', 0, NULL, NULL, NULL),
(202, 'Schwarztee Lose TAJ MAHAL 24X450g', '', 149.00, NULL, 0, 'SCHWARZTEE54754', 62, NULL, 'uploads/products/1773154754_Schwarztee_Lose_TAJ_MAHAL_24X450g.jpg', 'active', 0, '2026-03-10 14:59:14', '2026-03-10 14:59:14', '2026-03-10', NULL, 0, NULL, 149.00, 0.00, '', 0, NULL, NULL, NULL),
(203, 'Taj Mahal Schwarztee Lose 12X900g', '', 114.00, NULL, 0, 'TAJMAHAL54820', 62, NULL, 'uploads/products/1773154820_Taj_Mahal_Schwarztee_Lose_12X900g.jpg', 'active', 0, '2026-03-10 15:00:20', '2026-03-10 15:00:20', '2026-03-10', NULL, 0, NULL, 114.00, 0.00, '', 0, NULL, NULL, NULL),
(204, 'Schwarztee Yellow Label 100st LIPTON 12X200g', '', 41.00, NULL, 0, 'SCHWARZTEE54916', 62, NULL, 'uploads/products/1773154916_Schwarztee_Yellow_Label_100st_LIPTON_12X200g.jpg', 'active', 0, '2026-03-10 15:01:56', '2026-03-10 15:01:56', '2026-03-10', NULL, 0, NULL, 41.00, 0.00, '', 0, NULL, NULL, NULL),
(205, 'Lager Bier LION 24X500 ml', 'Lion Lager – Erfrischendes Lager aus Sri Lanka mit 4,8% Vol. Dose\r\nErlebe das einzigartige Geschmackserlebnis des Lion Lager Biers, einem Premium Lager aus Sri Lanka. Dieses helle Lagerbier überzeugt mit einer angenehmen Erfrischung und einer ausgewogenen Balance zwischen malziger Süße und einer dezenten Bitterkeit. Es ist die perfekte Wahl für alle, die ein frisches, gut ausbalanciertes Bier suchen, das sowohl als Begleiter zu Mahlzeiten als auch als Getränk für gesellige Anlässe ideal ist.\r\n\r\nGeschmack und Aromen: Lion Lager Bier besticht mit einem klaren, goldenen Aussehen und einem erfrischenden Geschmack, der perfekt für heiße Tage und gesellige Abende geeignet ist. Die subtile Hopfennote und die leicht malzige Süße machen es zu einem vollmundigen Bier, das sich angenehm im Abgang zeigt.\r\n\r\nSpeiseempfehlung: Dieses Bier harmoniert hervorragend mit Gegrilltem, würzigen asiatischen Gerichten, Burgern, Pizza und sogar milden Käsesorten. Auch zu traditionellen Grillgerichten oder in geselliger Runde passt es perfekt.\r\n\r\nVerpackungseinheit: 24X500ml Dosen\r\n\r\nInhaltsstoffe: Wasser, Malz (Gerste), Hopfen, Hefe\r\nAllegene: Gerstenmalz (Gluten)\r\nWarum Lion Lager Bier?\r\nPremium Lagerbier aus Sri Lanka\r\nErfrischend und gut ausbalanciert im Geschmack\r\nIdeal zu Gegrilltem, Pizza und würzigen Gerichten\r\nPerfekt für gesellige Anlässe und entspannte Abende\r\nBestelle dein Lion Lager Bier und erlebe den einzigartigen Geschmack dieses außergewöhnlichen Lagers. Genieße die erfrischende Kombination aus Tradition und Qualität.', 29.00, NULL, 10, 'LAGERBIER58188', 63, 45, NULL, 'inactive', 0, '2026-03-10 15:56:28', '2026-04-16 08:46:44', '2026-03-10', NULL, 0, NULL, 37.00, 29.00, '', 0, NULL, NULL, NULL),
(208, 'Lager Beer SL Alcohol 4.8% LION 330ml', 'Undoubtedly Sri Lanka’s best selling mild beers, Lion Lager has a 4.8% alcohol volume and is credited as a great thirst quencher. Golden roasted malt in colour with a hint of fruit and caramel flavouring, it is very slightly sweet with less hop notes. The attractive labeling is in sophisticated black and gold, showcasing the strong but watchful golden lion as the king of the savannah, symbolising visionary leadership and power.', 170.00, NULL, 1, 'LAGERBEER29407', 63, 45, 'uploads/products/1776329407_Lion-Lager-330ml.jpg', 'active', 0, '2026-04-16 08:50:07', '2026-04-16 08:50:07', '2026-04-16', NULL, 0, NULL, 170.00, 0.00, '', 0, '', NULL, NULL),
(206, 'test', '', 23.00, NULL, 0, 'TEST73815', 73, NULL, 'uploads/products/1773373815_Red_Lebel_Brooke_Bond_Tea_900g.jpg', 'active', 0, '2026-03-13 03:50:15', '2026-03-13 03:50:15', '2026-03-12', NULL, 0, 17, 23.00, 0.00, '', 0, NULL, NULL, NULL),
(207, 'Lager Beer DosenSL Alcohol 4.5 % LION 24X500ml', 'Undoubtedly Sri Lanka’s best selling mild beers, Lion Lager has a 4.8% alcohol volume and is credited as a great thirst quencher. Golden roasted malt in colour with a hint of fruit and caramel flavouring, it is very slightly sweet with less hop notes. The attractive labeling is in sophisticated black and gold, showcasing the strong but watchful golden lion as the king of the savannah, symbolising visionary leadership and power', 37.00, NULL, 0, 'LAGERBEER29031', 63, 45, 'uploads/products/1776329031_Lion-Lager-500ml.jpg', 'active', 0, '2026-04-16 08:43:51', '2026-04-16 08:43:51', '2026-04-16', NULL, 0, NULL, 37.00, 0.00, '', 0, '', NULL, NULL),
(209, 'Strong Beer Dosen SL Alcohol 8.8% LION 24X500ml', 'Lions Lager is brewed by Lion Brewery in Sri Lanka. Birthed and conceptualized on traditional brewing recipes and techniques since 1860. Today, Lion Beer has etched an illustrious path to become Sri Lanka’s benchmarked leader in the market. Infusing world class best practices, innovation, state of the art technology and a knowledge pool that remains beyond comparison, Lion Beer reflects a true Sri Lankan Brand constructed on a global platform of excellence.\r\n\r\nAs a respected and responsible industry leader, they ensure that their products are designed, produced and marketed conscientiously and with accountability. As a responsible corporate spearhead, we proudly uphold ethics, values and principles in a ‘beyond compliance’ milieu.\r\n\r\nLion Lager has a 4.8% alcohol volume and is credited as a great thirst quencher. Golden roasted malt in colour with a hint of fruit and caramel flavouring, it is very slightly sweet with less hop notes. The attractive labeling is in sophisticated black and gold, showcasing our strong but watchful golden lion as the king of the savannah, symbolising visionary leadership and power.', 3900.00, NULL, 10, 'STRONGBEE29483', 63, 45, NULL, 'inactive', 0, '2026-04-16 08:51:23', '2026-04-16 09:08:36', '2026-04-16', NULL, 0, NULL, 3900.00, 0.00, '', 0, '', NULL, NULL),
(215, 'Essence SN Annanas LIONS 12X30ml', 'Lions Ananas SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN63479', 69, 46, 'uploads/products/1776363479_Lion_Annanas.jpg', 'active', 0, '2026-04-16 18:17:59', '2026-04-16 18:49:09', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(210, 'Strong Beer Dosen SL Alcohol 8.8% LION 24X500ml', 'Lions Lager is brewed by Lion Brewery in Sri Lanka. Birthed and conceptualized on traditional brewing recipes and techniques since 1860. Today, Lion Beer has etched an illustrious path to become Sri Lanka’s benchmarked leader in the market. Infusing world class best practices, innovation, state of the art technology and a knowledge pool that remains beyond comparison, Lion Beer reflects a true Sri Lankan Brand constructed on a global platform of excellence.\r\n\r\nAs a respected and responsible industry leader, they ensure that their products are designed, produced and marketed conscientiously and with accountability. As a responsible corporate spearhead, we proudly uphold ethics, values and principles in a ‘beyond compliance’ milieu.\r\n\r\nLion Lager has a 4.8% alcohol volume and is credited as a great thirst quencher. Golden roasted malt in colour with a hint of fruit and caramel flavouring, it is very slightly sweet with less hop notes. The attractive labeling is in sophisticated black and gold, showcasing our strong but watchful golden lion as the king of the savannah, symbolising visionary leadership and power.', 39.00, NULL, 10, 'STRONGBEE29518', 63, 45, 'uploads/products/1776329518_Strong_Beer_Dosen_SL_Alcohol_8.8__LION_24X500ml.jpg', 'active', 0, '2026-04-16 08:51:58', '2026-04-16 08:51:58', '2026-04-16', NULL, 0, NULL, 39.00, 0.00, '', 0, '', NULL, NULL),
(211, 'Strong Beer SL Alcohol 8.8% LION 330ml', 'This amber golden beer adds a definite kick to the unique flavour that&#039;s a signature of the Lion Family of Beers. With an alcohol content of 8.8%, the branding denotes the proud head of our lion creating an image of power, strength and unchallenged victory.', 38.00, NULL, 0, 'STRONGBEE29688', 63, 45, 'uploads/products/1776329688_Strong_Beer_SL_Alcohol_8.8__LION_330ml.jpg', 'active', 0, '2026-04-16 08:54:48', '2026-04-16 08:54:48', '2026-04-16', NULL, 0, NULL, 38.00, 0.00, '', 0, '', NULL, NULL),
(212, 'Premium Lagerbier KINGFISHER 24X330ML', '', 34.00, NULL, 10, 'PREMIUMLA29823', 63, 45, 'uploads/products/1776329823_kingfisher-premium-lager-330ml.jpg', 'active', 0, '2026-04-16 08:57:03', '2026-04-16 08:57:03', '2026-04-16', NULL, 0, NULL, 34.00, 0.00, '', 0, '', NULL, NULL),
(213, 'Palm Drink Premium Q. NKULENUS 12X625ml', 'ALC. 4.5% VOL', 50.00, NULL, 10, 'PALMDRINK29904', 63, 45, 'uploads/products/1776329904_Palm_Drink_Premium_Q._NKULENUS_12X625ml.jpg', 'active', 0, '2026-04-16 08:58:24', '2026-04-16 08:58:24', '2026-04-16', NULL, 0, NULL, 50.00, 0.00, '', 0, '', NULL, NULL),
(214, 'Palm Drink Premium Q. NKULENUS 24X315ml', 'ALC. 4.5% VOL', 55.00, NULL, 10, 'PALMDRINK30022', 63, 45, 'uploads/products/1776330022_Palm_Drink_Premium_Q._NKULENUS_24X315ml.jpg', 'active', 0, '2026-04-16 09:00:22', '2026-04-16 09:00:22', '2026-04-16', NULL, 0, NULL, 55.00, 0.00, '', 0, '', NULL, NULL),
(216, 'Essence SN Coconut LIONS 12X30ml', 'Lions Kokosnuss SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN64452', 69, 46, 'uploads/products/1776364452_Arome-coco.jpg', 'active', 0, '2026-04-16 18:34:12', '2026-04-16 18:49:04', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(217, 'Essence SN Mint LIONS 12X30ml', 'Lions Mint SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN65004', 69, 46, 'uploads/products/1776365004_Arome-menthe.jpg', 'active', 0, '2026-04-16 18:43:24', '2026-04-16 18:49:00', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(218, 'Essence SN Tropicale LIONS 12X30ml', 'Lions Tropicale SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN65309', 69, 46, 'uploads/products/1776365309_Arome-exotique.jpg', 'active', 0, '2026-04-16 18:48:29', '2026-04-16 18:48:29', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(219, 'Essence SN Vanilla LIONS 12X30ml', 'Lions Vanilla SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN65766', 69, 46, 'uploads/products/1776365766_Arome-vanille.jpg', 'active', 0, '2026-04-16 18:56:07', '2026-04-16 18:56:07', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(220, 'Essence SN Banana LIONS 12X30ml', 'Lions Banana SN Essence 30ml ist eine aus Ananas gewonnene Flüssigkeit, die als Aromastoff verwendet wird.\r\nSchachtel mit 12 Flaschen à 30 ml', 8.00, NULL, 0, 'ESSENCESN65941', 69, 46, 'uploads/products/1776365941_Arome-banane.jpg', 'active', 0, '2026-04-16 18:59:01', '2026-04-17 11:29:17', '2026-04-16', NULL, 0, NULL, 19.50, 14.00, 'Fresh Tropical srl by Jawad', 0, '33021000', 3.00, 2.00),
(221, 'Rose Sirup KIAT 12X750ml', 'Anleitung:\r\nVerwenden Sie 2 EL (30 ml) zum Servieren. Nach dem Öffnen kühl und trocken lagern. Der Sirup wird üblicherweise bei Zimmertemperatur oder leicht erwärmt serviert.\r\n\r\nRechtlicher Hinweis\r\nDie tatsächliche Produktverpackung und die beiliegenden Materialien können mehr und andere Informationen enthalten als in unserer App oder auf unserer Website angegeben. Wir empfehlen Ihnen daher, sich nicht ausschließlich auf die hier präsentierten Informationen zu verlassen und vor der Verwendung oder dem Verzehr eines Produkts stets die Etiketten, Warnhinweise und Gebrauchsanweisungen zu lesen.\r\n\r\nProduktbeschreibung\r\nROSENSIRUP IN DER GLASFLASCHE – ORIGINAL SEIT 1935\r\n\r\nWegbeschreibung\r\nVerwenden Sie 2 EL (30 ml) zum Servieren. Nach dem Öffnen kühl und trocken lagern. Der Sirup wird üblicherweise bei Zimmertemperatur oder leicht erwärmt serviert.', 36.00, NULL, 0, 'ROSESIRUP66734', 69, 47, 'uploads/products/1776366734_61887JgppsL._AC_UF894_1000_QL80_.jpg', 'active', 0, '2026-04-16 19:12:14', '2026-04-16 19:12:14', '2026-04-16', NULL, 0, NULL, 75.00, 70.00, 'Fresh Tropical srl by Jawad', 0, '', 12.00, 5.00),
(222, 'Nelli Sirup EDINBOROUGH 12X750ml', 'Nettoinhalt: 750 ml \r\nGeschmack: Nelli \r\nHerkunftsland: Sri Lanka \r\nLagerung: Kühl, trocken und sauber bei Raumtemperatur lagern. Vor direkter Sonneneinstrahlung schützen. Zutaten: Zucker, Nelli-Extrakt (24 %) [Wasser, Nelli], Wasser, künstliches Nelli-Aroma, Zitronensäure (INS 330), Ascorbinsäure (INS 300), Natriumcarboxymethylcellulose (INS 466), Natriummetabisulfit (INS 223), Tartrazin (INS 102), Brillantblau FCF (INS 133)', 25.00, NULL, 0, 'NELLISIRU67714', 69, 48, 'uploads/products/1776367714_Nelli_Syrup-1removebg-preview1.jpg', 'active', 0, '2026-04-16 19:28:34', '2026-04-16 19:32:54', '2026-04-16', NULL, 0, 21, 48.00, 40.00, 'mathu', 0, '', 5.00, 5.00),
(223, 'Sherbet Syrup MD 12X750 ml', 'Zucker, Wasser, künstliches Rosenessenz, zugelassene Farbstoffe (E 110, E 124) und zugelassenes Konservierungsmittel Kaliumsorbat (E 202)\r\nBeschreibung\r\nTypische Werte pro 100 g\r\nBrennstoff 258 kcal / 1080 kJ\r\nEiweiß 0,28 g\r\nKohlenhydrate 63,84 g\r\nMineralstoffe 0,07 g\r\nFett 0,12 g', 40.00, NULL, 0, 'SHERBETSY68765', 69, 49, 'uploads/products/1776368765_sherbert750.jpg', 'active', 0, '2026-04-16 19:46:05', '2026-04-16 19:46:05', '2026-04-16', NULL, 0, 21, 60.00, 50.00, '', 0, '', 10.00, 5.00),
(224, 'Nelli Sirup MD 12X750 ml', 'Zucker, Wasser, Nelli-Saft, Limettensaft, Zitronensäure (E 330), zugelassene Farbstoffe (E 102, E 133) und zugelassenes Konservierungsmittel Natriummetabisulfit (E 223)', 30.00, NULL, 0, 'NELLISIRU68854', 69, 49, 'uploads/products/1776368854_nelli750.jpg', 'active', 0, '2026-04-16 19:47:34', '2026-04-16 19:47:34', '2026-04-16', NULL, 0, 21, 60.00, 50.00, '', 0, '', 10.00, 5.00),
(225, 'Kondensmilch ALIBABA 8X1kg', '', 27.00, NULL, 0, 'KONDENSMIL89906', 45, 50, 'uploads/products/1776589906_Kondensmilch_1_kg.jpg', 'active', 0, '2026-04-19 09:11:46', '2026-04-19 09:11:46', '2026-04-19', NULL, 0, NULL, 63.00, 58.00, 'Fresh Tropical srl by Jawad', 0, '0402.9910', 19.00, 3.50),
(226, 'Evaporated Milch PEAK 24X410g', '', 36.00, NULL, 0, 'EVAPORATED90351', 45, 17, 'uploads/products/1776590351_PEAK_EVAPORATED_MILK_2X12X410_g.jpg', 'active', 0, '2026-04-19 09:19:11', '2026-04-19 09:19:11', '2026-04-19', NULL, 0, NULL, 74.00, 66.00, 'Fresh Tropical srl by Jawad', 0, '0402.9910', 23.00, 3.50),
(227, 'Carnation Milch Nestle 12X410ml', 'Carnation Kondensmilch ist das perfekte cremige Topping für all Ihre Lieblingsdesserts. Hergestellt aus nur drei Zutaten: frischer Vollmilch, Stabilisator (Natriumphosphate) und angereichert mit Vitamin D. Ohne künstliche Farb-, Zucker- oder Konservierungsstoffe. Ideal für herzhafte und süße Rezepte, von frischem Obstsalat bis hin zu klebrigem Toffee-Pudding.\r\n\r\nZutaten: Vollmilch, Stabilisator: Natriumphosphate, Vitamin D, mindestens 9 % Milchfett, 22 % fettfreie Milchtrockenmasse. Allergiehinweis: Enthält Milch. Kann Spuren von Soja, Eiern, Gluten, Schalenfrüchten, Erdnüssen, Senf, Sesam, Sulfiten, Fisch und Sellerie enthalten.\r\n\r\nEigenschaften:\r\nHerrlich cremiges Topping\r\nQuelle für Kalzium und Vitamin D\r\nIdeal für herzhafte und süße Rezepte\r\nFür Vegetarier geeignet\r\nPackung mit 12 Dosen à 410 g', 18.50, NULL, 0, 'CARNATION90694', 45, 23, 'uploads/products/1776590694_CARNATION_CONDENSED_MILK_12X410_ML.jpg', 'active', 0, '2026-04-19 09:24:54', '2026-04-19 09:24:54', '2026-04-19', NULL, 0, NULL, 42.00, 38.00, 'Fresh Tropical srl by Jawad', 0, '0402.9910', 14.00, 3.50),
(228, 'Schokolade Getränke SG MILO 12X400g', '', 90.00, NULL, 0, 'SCHOKOLADE91212', 45, 23, 'uploads/products/1776591212_Schokoladepulver_Getr__nke_MILO_24X400g.jpg', 'active', 0, '2026-04-19 09:33:32', '2026-04-19 09:33:32', '2026-04-19', NULL, 0, NULL, 140.00, 130.00, '', 0, '1901-9099', 30.00, 5.00),
(229, 'Horliks Maltgetränke 6X400g', 'HORLICKS GERSTENMALZ-GETRÄNK\r\nWEIZEN 41% (WEIZENMEHL UND WEIZENMALZ), GERSTENMALZ 31%, GETROCKNETE MOLKE 6% (MILCH),\r\n CALCIUMCARBONAT, GETROCKNETE MAGERMILCH 3%, ZUCKER, PALMÖL, SALZ, TRENNMITTEL (E551), VITAMINMISCHUNG\r\n (VITAMIN C, NIACIN, VITAMIN E, PANTOTHENSÄURE, VITAMIN B6, RIBOFLAVIN, THIAMIN, FOLSÄURE, BIOTIN, VITAMIN D,VITAMIN B12)\r\nEISENPYROPHOSPHAT, ZINKOXID.\r\nNÄHRWERTANGABEN PRO 100G: ENERGIE 1534 KJ/362KCAL, FETT 2,3G, DAVON GESÄTTIGTE FETTSÄUREN 1,1G, KOHLENHYDRATE 77,3G\r\n DAVON ZUCKER 39,3G, EIWEIß 9,3G, SALZ 1,1MG\r\nAN EINEM KÜHLEN, TROCKENEN ORT UND VOR SONNENLICHT GESCHÜTZT AUFBEWAHREN.\r\nMINDESTENS HALTBAR BIS: SIEHE GEHÄUSEETIKETT - NETTOGEWICHT: 400 G\r\nPRODUZIERT IN: VEREINIGTES KÖNIGREICH', 20.00, NULL, 0, 'HORLIKSMA91434', 45, 20, 'uploads/products/1776591434_HORLIKS_TRADITIONAL_MALTED_MILK_6X300_G.jpg', 'active', 0, '2026-04-19 09:37:14', '2026-04-19 09:37:14', '2026-04-19', NULL, 0, NULL, 33.00, 30.00, 'Fresh Tropical srl by Jawad', 0, '2106-9075', 3.00, 2.00),
(230, 'Milchpulver NIDO 24X400g', '', 120.00, NULL, 0, 'MILCHPULVE91614', 45, 23, 'uploads/products/1776591614_NESTL___MILK_POWDER_24X400_g.jpg', 'active', 0, '2026-04-19 09:40:14', '2026-04-19 09:40:14', '2026-04-19', NULL, 0, NULL, 150.00, 135.00, '', 0, '0402 1000', NULL, NULL),
(231, 'Milchpulver NIDO 12X900g', '', 126.00, NULL, 0, 'MILCHPULVE91700', 45, 23, 'uploads/products/1776591700_NESTL___MILK_POWDER_12X900_g.jpg', 'active', 0, '2026-04-19 09:41:40', '2026-04-19 09:41:40', '2026-04-19', NULL, 0, NULL, 155.00, 126.00, '', 0, '0402 1000', NULL, NULL),
(232, 'Milchpulver NIDO 6X2500g', '', 150.00, NULL, 0, 'MILCHPULVE91746', 45, 23, 'uploads/products/1776591746_NESTL___MILK_POWDER_6X2.5_kg.jpg', 'active', 0, '2026-04-19 09:42:26', '2026-04-19 09:42:26', '2026-04-19', NULL, 0, NULL, 300.00, 150.00, '', 0, '0402 1000', NULL, NULL),
(233, 'Kondensmilch (Süss) Nestle 12X397g', '', 20.00, NULL, 0, 'KONDENSMIL91962', 45, 23, 'uploads/products/1776591962_NESTL___CONDENSED_SUGARED_MILK_48X397_g.jpg', 'active', 0, '2026-04-19 09:46:02', '2026-04-19 09:46:02', '2026-04-19', NULL, 0, NULL, 39.00, 20.00, '', 0, '0402-9910', 14.00, 2.00),
(234, 'Sandale Sandelholz Agarpaththi 125g MYSORE', '', 0.60, NULL, 0, 'SANDALESA89785', 71, 51, 'uploads/products/1776889785_MYSORE_SANDAL_AGARPATHI_P.jpg', 'active', 0, '2026-04-22 20:29:45', '2026-04-22 20:29:45', '2026-04-22', NULL, 0, 18, 2.50, 2.00, '', 0, '', 0.20, 0.50);
INSERT INTO `products` (`id`, `name`, `description`, `price`, `sale_price`, `stock_quantity`, `sku`, `category_id`, `brand_id`, `image`, `status`, `is_visible`, `created_at`, `updated_at`, `add_date`, `expiry_date`, `is_new`, `country_id`, `price2`, `price3`, `supplier`, `batch_number`, `hsn_code`, `customs_charge`, `transport_charge`) VALUES
(235, 'Jasmine Sandelholz Agarpaththi 125g MYSORE', '', 0.50, NULL, 0, 'JASMINESA90350', 71, 51, 'uploads/products/1776890350_MYSORE_SANDAL_AGARPATHI_Jasmine.jpg', 'active', 0, '2026-04-22 20:39:10', '2026-04-22 20:39:10', '2026-04-22', NULL, 0, NULL, 2.50, 2.00, '', 0, '', 0.20, 0.50),
(236, 'Körperpflege Seife Papaya POWER 125g', 'Vorteile\r\n1. Peelt und hellt die Haut auf: Papaya-Extrakt entfernt abgestorbene Hautschüppchen und sorgt für einen strahlenderen, ebenmäßigeren Teint.\r\n\r\n2. Spendet Feuchtigkeit und macht die Haut geschmeidig: Die feuchtigkeitsspendenden Eigenschaften schließen die Feuchtigkeit ein und hinterlassen ein weiches, geschmeidiges Hautgefühl.\r\n\r\n3. Sorgt für einen strahlenden, gesunden Teint: Natürliche Inhaltsstoffe und Papaya-Extrakt fördern einen gesunden, strahlenden Teint.\r\n\r\nAnwendung\r\n1. Haut mit warmem Wasser anfeuchten.\r\n\r\n2. Nature Power Papaya Aura Seife auftragen und aufschäumen.\r\n\r\n3. Gründlich mit warmem Wasser abspülen.', 0.50, NULL, 0, 'KRPERPFL91005', 64, 52, 'uploads/products/1776891005_Papaya-Aura-Soap-125g-_Nature-Power_trA2Z_Shoppy.jpg', 'active', 0, '2026-04-22 20:50:06', '2026-04-22 20:50:06', '2026-04-22', NULL, 0, 18, 1.20, 1.00, '', 0, '', 0.10, 0.10),
(237, 'Kurkuma Pulver Dose GOPURAM 25g', '', 0.20, NULL, 0, 'KURKUMAPU91244', 71, 53, 'uploads/products/1776891244_Kurkuma_Pulver_Dose_GOPURAM_25g.jpg', 'active', 0, '2026-04-22 20:54:04', '2026-04-22 21:09:11', '2026-04-22', NULL, 0, NULL, 1.20, 0.80, '', 0, '', 0.10, 0.10),
(238, 'FENNEL SEEDS TRS 10X400G', '', 36.00, NULL, 0, 'FENNELSEE97995', 67, 27, 'uploads/products/1777697995_3692.jpg', 'active', 0, '2026-05-02 04:59:55', '2026-05-08 03:12:37', '2026-05-02', NULL, 0, NULL, 36.00, 0.00, '', 0, '', NULL, NULL),
(239, 'Fennelseed ALIBABA 20X100g', '', 23.50, NULL, 0, 'FENNELSEED98490', 67, 56, 'uploads/products/1777698490_4051.jpg', 'active', 0, '2026-05-02 05:08:10', '2026-05-08 03:13:05', '2026-05-02', NULL, 0, NULL, 23.50, 0.00, '', 0, '', NULL, NULL),
(240, 'SOONF(FENNEL SEEDS) ALI BABA 8X400G', '', 28.50, NULL, 0, 'SOONFFENN98763', 67, 56, 'uploads/products/1777698763_4052.jpg', 'active', 0, '2026-05-02 05:12:44', '2026-05-08 03:14:19', '2026-05-02', NULL, 0, NULL, 28.50, 0.00, '', 0, '', NULL, NULL),
(241, 'Fennelseed SIVAKAMY 80X100g', '', 85.00, NULL, 0, 'FENNELSEED10162', 67, NULL, NULL, 'active', 0, '2026-05-08 03:16:02', '2026-05-08 03:16:02', '2026-05-07', NULL, 0, NULL, 85.00, NULL, '', 0, '', NULL, NULL),
(242, 'SUGAR COATED FENNEL ALIBABA 12X250G', '', 28.00, NULL, 0, 'SUGARCOAT11553', 67, 56, NULL, 'active', 0, '2026-05-08 03:39:13', '2026-05-08 03:39:13', '2026-05-07', NULL, 0, NULL, 28.00, NULL, '', 0, '', NULL, NULL),
(243, 'Jeera Powder TRS 20X100 g', '', 25.90, NULL, 0, 'JEERAPOWD11619', 67, 56, 'uploads/products/1778211619_trs3180_0.jpg', 'active', 0, '2026-05-08 03:40:19', '2026-05-08 03:40:19', '2026-05-07', NULL, 0, NULL, 25.90, NULL, '', 0, '', NULL, NULL),
(244, 'Jeera Pulver / Cumin Powder TRS 10X500g', '', 54.00, NULL, 0, 'JEERAPULV11670', 67, 56, 'uploads/products/1778211670_TRS-jeera-powder-600x600.jpg', 'active', 0, '2026-05-08 03:41:10', '2026-05-08 03:41:10', '2026-05-07', NULL, 0, NULL, 54.00, NULL, '', 0, '', NULL, NULL),
(245, 'Kreuzkümel Pulver (Jeera Powder) ALIBABA 8X400g', '', 35.00, NULL, 0, 'KREUZKME11741', 67, 56, 'uploads/products/1778211741_c92b925659387d3707bc8896f7d916d3-alibaba-rimsky-kmin-mlety-1kg-trimpad.jpg', 'active', 0, '2026-05-08 03:42:21', '2026-05-08 03:42:21', '2026-05-07', NULL, 0, NULL, 35.00, NULL, '', 0, '', NULL, NULL),
(246, 'Kreuzkümel Pulver (Jeera Powder) SIVAKAMY 20X400g', '', 87.00, NULL, 0, 'KREUZKME11789', 67, 56, NULL, 'active', 0, '2026-05-08 03:43:09', '2026-05-08 03:43:09', '2026-05-07', NULL, 0, NULL, 87.00, NULL, '', 0, '', NULL, NULL),
(247, 'Senfsamen (Mustard) 80X100g SIVAKAMY', '', 60.00, NULL, 0, 'SENFSAMEN11806', 67, 56, NULL, 'active', 0, '2026-05-08 03:43:26', '2026-05-08 03:43:26', '2026-05-07', NULL, 0, NULL, 60.00, NULL, '', 0, '', NULL, NULL),
(248, 'Senfsamen (Mustard) ALIBABA 10X400g', '', 23.00, NULL, 0, 'SENFSAMEN11896', 67, 56, 'uploads/products/1778211896_9395.jpg', 'active', 0, '2026-05-08 03:44:56', '2026-05-08 03:44:56', '2026-05-07', NULL, 0, NULL, 23.00, NULL, '', 0, '', NULL, NULL),
(249, 'Senfsamen (Mustard) ALIBABA 20X100g', '', 14.50, NULL, 0, 'SENFSAMEN11959', 67, 56, 'uploads/products/1778211959_Screenshot_2026-05-08_091355.jpg', 'active', 0, '2026-05-08 03:45:59', '2026-05-08 03:45:59', '2026-05-07', NULL, 0, NULL, 14.50, NULL, '', 0, '', NULL, NULL),
(250, 'Senfsamen (Mustard) SIVAKAMY 20X400g', '', 46.00, NULL, 0, 'SENFSAMEN12054', 67, 56, 'uploads/products/1778212054_2N8A0164-1.jpg', 'active', 0, '2026-05-08 03:47:34', '2026-05-08 03:47:34', '2026-05-07', NULL, 0, NULL, 46.00, NULL, '', 0, '', NULL, NULL),
(251, 'Senfsamen (Mustard) TRS 20X100g', '', 10.00, NULL, 0, 'SENFSAMEN12131', 67, 56, 'uploads/products/1778212131_0002026_trs-mustard-seeds-brown-20x100g.jpg', 'active', 0, '2026-05-08 03:48:51', '2026-05-08 03:48:51', '2026-05-07', NULL, 0, NULL, 10.00, NULL, '', 0, '', NULL, NULL),
(252, 'Methiseed (Bockshornklee) SIVAKAMY 12X1kg', '', 46.50, NULL, 0, 'METHISEED12160', 67, 56, NULL, 'active', 0, '2026-05-08 03:49:20', '2026-05-08 03:49:20', '2026-05-07', NULL, 0, NULL, 46.50, NULL, '', 0, '', NULL, NULL),
(253, 'Methiseed (Bockshornklee) SIVAKAMY 24X400g', '', 52.00, NULL, 0, 'METHISEED12177', 67, 56, NULL, 'active', 0, '2026-05-08 03:49:37', '2026-05-08 03:49:37', '2026-05-07', NULL, 0, NULL, 52.00, NULL, '', 0, '', NULL, NULL),
(254, 'Methiseed (Bockshornklee) SIVAKAMY 40X200g', '', 50.00, NULL, 0, 'METHISEED12192', 67, 56, NULL, 'active', 0, '2026-05-08 03:49:52', '2026-05-08 03:49:52', '2026-05-07', NULL, 0, NULL, 50.00, NULL, '', 0, '', NULL, NULL),
(255, 'Cloves Whole SIVAKAMY 20X200g', '', 88.00, NULL, 0, 'CLOVESWHO12206', 67, 56, NULL, 'active', 0, '2026-05-08 03:50:06', '2026-05-08 03:50:06', '2026-05-07', NULL, 0, NULL, 88.00, NULL, '', 0, '', NULL, NULL),
(256, 'Cloves Whole ALIBABA 20X50g', '', 24.50, NULL, 0, 'CLOVESWHO12285', 67, 56, 'uploads/products/1778212285_6736.jpg', 'active', 0, '2026-05-08 03:51:26', '2026-05-08 03:51:26', '2026-05-07', NULL, 0, NULL, 24.50, NULL, '', 0, '', NULL, NULL),
(257, 'Cloves Whole ALIBABA 10X250g', '', 51.00, NULL, 0, 'CLOVESWHO12341', 67, 56, 'uploads/products/1778212341_4054.jpg', 'active', 0, '2026-05-08 03:52:22', '2026-05-08 03:52:22', '2026-05-07', NULL, 0, NULL, 51.00, NULL, '', 0, '', NULL, NULL),
(258, 'Cloves SIVAKAMY 80X50g', '', 95.00, NULL, 0, 'CLOVESSIV12354', 67, 56, NULL, 'active', 0, '2026-05-08 03:52:34', '2026-05-08 03:52:34', '2026-05-07', NULL, 0, NULL, 95.00, NULL, '', 0, '', NULL, NULL),
(259, 'Green Cardomom 20X200g SIVAKAMY', '', 188.00, NULL, 0, 'GREENCARD12373', 67, 56, NULL, 'active', 0, '2026-05-08 03:52:53', '2026-05-08 03:52:53', '2026-05-07', NULL, 0, NULL, 188.00, NULL, '', 0, '', NULL, NULL),
(260, 'Green Cardomom 80X50g SIVAKAMY', '', 200.00, NULL, 0, 'GREENCARD12394', 67, 56, NULL, 'active', 0, '2026-05-08 03:53:14', '2026-05-08 03:53:14', '2026-05-07', NULL, 0, NULL, 200.00, NULL, '', 0, '', NULL, NULL),
(261, 'Green Cardomom ALIBABA 10X200g', '', 108.00, NULL, 0, 'GREENCARD12482', 67, 56, 'uploads/products/1778212482_3d8050d7f97c127fc8f8689338a45c1f-alibaba-green-cardamom-200g.jpg', 'active', 0, '2026-05-08 03:54:42', '2026-05-08 03:54:42', '2026-05-07', NULL, 0, NULL, 108.00, NULL, '', 0, '', NULL, NULL),
(262, 'Green Cardomom ALIBABA 20X50g', '', 56.00, NULL, 0, 'GREENCARD12532', 67, 56, 'uploads/products/1778212532_7183.jpg', 'active', 0, '2026-05-08 03:55:32', '2026-05-08 03:55:32', '2026-05-07', NULL, 0, NULL, 56.00, NULL, '', 0, '', NULL, NULL),
(263, 'Cinnamon (Zimtstange) CL SIVAKAMY 50X50g', '', 95.00, NULL, 0, 'CINNAMON12554', 67, 56, NULL, 'active', 0, '2026-05-08 03:55:54', '2026-05-08 03:55:54', '2026-05-07', NULL, 0, NULL, 95.00, NULL, '', 0, '', NULL, NULL),
(264, 'Madras Curry Masala Mild TRS 10X400g', '', 35.00, NULL, 0, 'MADRASCUR12599', 67, 56, 'uploads/products/1778212599_TRS-Mild-Madas-Curry-Powder-600x600.jpg', 'active', 0, '2026-05-08 03:56:39', '2026-05-08 03:56:39', '2026-05-07', NULL, 0, NULL, 35.00, NULL, '', 0, '', NULL, NULL),
(265, 'Madras Currypowder HOT TRS 10X100g', '', 22.00, NULL, 0, 'MADRASCUR12681', 67, 27, 'uploads/products/1778212681_image.jpg', 'active', 0, '2026-05-08 03:58:01', '2026-05-08 03:58:01', '2026-05-07', NULL, 0, NULL, 22.00, NULL, '', 0, '', NULL, NULL),
(266, 'Madras Currypowder HOT TRS 10X400g', '', 37.00, NULL, 0, 'MADRASCUR12768', 67, 27, 'uploads/products/1778212768_trs3435_0.jpg', 'active', 0, '2026-05-08 03:59:28', '2026-05-08 03:59:28', '2026-05-07', NULL, 0, NULL, 37.00, NULL, '', 0, '', NULL, NULL),
(267, 'Chilipulver ALIBABA 10X400g', '', 33.00, NULL, 0, 'CHILIPULVE12841', 67, 56, 'uploads/products/1778212841_3588.jpg', 'active', 0, '2026-05-08 04:00:41', '2026-05-08 04:00:41', '2026-05-08', NULL, 0, NULL, 33.00, NULL, '', 0, '', NULL, NULL),
(268, 'Chilipulver ALIBABA 20X100g', '', 22.00, NULL, 0, 'CHILIPULVE12898', 67, 56, 'uploads/products/1778212898_175.jpg', 'active', 0, '2026-05-08 04:01:38', '2026-05-08 04:01:38', '2026-05-08', NULL, 0, NULL, 22.00, NULL, '', 0, '', NULL, NULL),
(269, 'Chilipulver EX-HOT ALIBABA 10X400g', '', 33.00, NULL, 0, 'CHILIPULVE12959', 67, 56, 'uploads/products/1778212959_180.jpg', 'active', 0, '2026-05-08 04:02:40', '2026-05-08 04:02:40', '2026-05-08', NULL, 0, NULL, 33.00, NULL, '', 0, '', NULL, NULL),
(270, 'Chilipulver EX-HOT ALIBABA 20X100g', '', 26.00, NULL, 0, 'CHILIPULVE13017', 67, 56, 'uploads/products/1778213017_179.jpg', 'active', 0, '2026-05-08 04:03:37', '2026-05-08 04:03:37', '2026-05-08', NULL, 0, NULL, 26.00, NULL, '', 0, '', NULL, NULL),
(271, 'Chilipulver EX-HOT ALIBABA 6X1kg', '', 55.00, NULL, 0, 'CHILIPULVE13081', 67, 56, 'uploads/products/1778213081_Screenshot_2026-05-08_093228.jpg', 'active', 0, '2026-05-08 04:04:41', '2026-05-08 04:04:41', '2026-05-08', NULL, 0, NULL, 55.00, NULL, '', 0, '', NULL, NULL),
(272, 'Chilipulver EX-HOT TRS 10X400g', '', 38.00, NULL, 0, 'CHILIPULVE13145', 67, 27, 'uploads/products/1778213145_180__1_.jpg', 'active', 0, '2026-05-08 04:05:45', '2026-05-08 04:05:45', '2026-05-08', NULL, 0, NULL, 38.00, NULL, '', 0, '', NULL, NULL),
(273, 'Chilipulver EX-HOT TRS 20X100g', '', 25.50, NULL, 0, 'CHILIPULVE13207', 67, 27, 'uploads/products/1778213207_Chilli-Powder-Extra-Hot100g.jpg', 'active', 0, '2026-05-08 04:06:47', '2026-05-08 04:06:47', '2026-05-08', NULL, 0, NULL, 25.50, NULL, '', 0, '', NULL, NULL),
(274, 'Chilipulver TRS 10X400g', '', 43.00, NULL, 0, 'CHILIPULVE13285', 67, 27, 'uploads/products/1778213285_TRS-400g-Chillipulver-14356_1_1818171b-9194-4aba-9fc0-dbf458c1b6ae.jpg', 'active', 0, '2026-05-08 04:08:06', '2026-05-08 04:08:06', '2026-05-08', NULL, 0, NULL, 43.00, NULL, '', 0, '', NULL, NULL),
(275, 'Chilipulver TRS 20X100g', '', 24.90, NULL, 0, 'CHILIPULVE13381', 67, 27, 'uploads/products/1778213381_TRS-100g-Chillipulver-Extrascharf-991002.jpg', 'active', 0, '2026-05-08 04:09:41', '2026-05-08 04:09:41', '2026-05-08', NULL, 0, NULL, 24.90, NULL, '', 0, '', NULL, NULL),
(276, 'Crushed Chili ALIBABA 10X250g', '', 29.50, NULL, 0, 'CRUSHEDCH13428', 67, 27, 'uploads/products/1778213428_178.jpg', 'active', 0, '2026-05-08 04:10:28', '2026-05-08 04:10:28', '2026-05-08', NULL, 0, NULL, 29.50, NULL, '', 0, '', NULL, NULL),
(277, 'Crushed Chili ALIBABA 10X250g', '', 29.50, NULL, 0, 'CRUSHEDCH13456', 67, 56, 'uploads/products/1778213456_178.jpg', 'active', 0, '2026-05-08 04:10:56', '2026-05-08 04:10:56', '2026-05-08', NULL, 0, NULL, 29.50, NULL, '', 0, '', NULL, NULL),
(278, 'Crushed Chili SIVAKAMY 20X200g', '', 49.00, NULL, 0, 'CRUSHEDCH13481', 67, 14, NULL, 'active', 0, '2026-05-08 04:11:21', '2026-05-08 04:11:21', '2026-05-08', NULL, 0, NULL, 49.00, NULL, '', 0, '', NULL, NULL),
(279, 'Getrocknete Rotes Chili SIVAKAMY 8X1kg', '', 75.00, NULL, 0, 'GETROCKNET13495', 67, 14, NULL, 'active', 0, '2026-05-08 04:11:35', '2026-05-08 04:11:35', '2026-05-08', NULL, 0, NULL, 75.00, NULL, '', 0, '', NULL, NULL),
(280, 'Getrockneten Grünen Chili SIVAKAMY 50X100g', '', 65.00, NULL, 0, 'GETROCKNET13513', 67, 14, NULL, 'active', 0, '2026-05-08 04:11:53', '2026-05-08 04:11:53', '2026-05-08', NULL, 0, NULL, 65.00, NULL, '', 0, '', NULL, NULL),
(281, 'Getrockneten Rotten Chili SIVAKAMY 50X100g', '', 55.00, NULL, 0, 'GETROCKNET13539', 67, 14, NULL, 'active', 0, '2026-05-08 04:12:19', '2026-05-08 04:12:19', '2026-05-08', NULL, 0, NULL, 55.00, NULL, '', 0, '', NULL, NULL),
(282, 'Kashmiri Chilipulver ABNABABA 8X400g', '', 26.00, NULL, 0, 'KASHMIRIC13782', 67, 14, 'uploads/products/1778213782_kashmiri-chilli-powder-ali-baba-spice-ali-baba-400g-997050.jpg', 'active', 0, '2026-05-08 04:16:23', '2026-05-08 04:16:23', '2026-05-08', NULL, 0, NULL, 26.00, NULL, '', 0, '', NULL, NULL),
(283, 'Kashmiri Chilipulver ABNABABA 20X100g', '', 23.00, NULL, 0, 'KASHMIRIC13853', 67, 50, 'uploads/products/1778213853_Pic125309_20241121194714.jpg', 'active', 0, '2026-05-08 04:17:33', '2026-05-08 04:17:33', '2026-05-08', NULL, 0, NULL, 23.00, NULL, '', 0, '', NULL, NULL),
(284, 'Koriander Pulver (Dhania) SIVAKAMY 20X400g', '', 45.00, NULL, 0, 'KORIANDER13906', 67, 14, 'uploads/products/1778213906_Dhania-1KG-1-POWDER.jpg', 'active', 0, '2026-05-08 04:18:27', '2026-05-08 04:18:27', '2026-05-08', NULL, 0, NULL, 45.00, NULL, '', 0, '', NULL, NULL),
(285, 'Korianderpulver (Dhania) ALIBABA 20X100g', '', 14.50, NULL, 0, 'KORIANDERP13994', 67, 14, 'uploads/products/1778213994_5828.jpg', 'active', 0, '2026-05-08 04:19:54', '2026-05-08 04:19:54', '2026-05-08', NULL, 0, NULL, 14.50, NULL, '', 0, '', NULL, NULL),
(286, 'Korianderpulver (Dhania) ALIBABA 8X250g', '', 23.00, NULL, 0, 'KORIANDERP14077', 67, 56, 'uploads/products/1778214077_ALIBABA-DHANIYA-WHOLE-250.jpg', 'active', 0, '2026-05-08 04:21:17', '2026-05-08 04:21:17', '2026-05-08', NULL, 0, NULL, 23.00, NULL, '', 0, '', NULL, NULL),
(287, 'Koriandersamen (Dhania) ALIBABA 20X100g', '', 16.00, NULL, 0, 'KORIANDERS14131', 67, 56, 'uploads/products/1778214131_5828__1_.jpg', 'active', 0, '2026-05-08 04:22:11', '2026-05-08 04:22:11', '2026-05-08', NULL, 0, NULL, 16.00, NULL, '', 0, '', NULL, NULL),
(288, 'Koriandersamen (Dhania) ALIBABA 8X250g', '', 13.90, NULL, 0, 'KORIANDERS14220', 67, 56, 'uploads/products/1778214220_ALIBABA-DHANIYA-WHOLE-250__1_.jpg', 'active', 0, '2026-05-08 04:23:41', '2026-05-08 04:23:41', '2026-05-08', NULL, 0, NULL, 13.90, NULL, '', 0, '', NULL, NULL),
(289, 'Koriandersamen (Dhania) SIVAKAMY 20X250g', '', 45.00, NULL, 0, 'KORIANDERS14259', 67, 14, NULL, 'active', 0, '2026-05-08 04:24:19', '2026-05-08 04:24:19', '2026-05-08', NULL, 0, NULL, 45.00, NULL, '', 0, '', NULL, NULL),
(290, 'Koriandersamen (Dhania) TRS 15X100g', '', 10.00, NULL, 0, 'KORIANDERS14282', 67, 27, NULL, 'active', 0, '2026-05-08 04:24:42', '2026-05-08 04:24:42', '2026-05-08', NULL, 0, NULL, 10.00, NULL, '', 0, '', NULL, NULL),
(291, 'Gewürzmischung Ganz QUEENS 50X100g', '', 140.00, NULL, 0, 'GEWRZMIS14368', 67, 14, NULL, 'active', 0, '2026-05-08 04:26:08', '2026-05-08 04:26:08', '2026-05-08', NULL, 0, NULL, 140.00, NULL, '', 0, '', NULL, NULL),
(292, 'Getrocknete Ingwer (Sukku) 80X100g', '', 160.00, NULL, 0, 'GETROCKNET14455', 67, 14, 'uploads/products/1778214455_getrockneter-ingwer-100g-gemahlen.jpg', 'active', 0, '2026-05-08 04:27:35', '2026-05-08 04:27:35', '2026-05-08', NULL, 0, NULL, 160.00, NULL, '', 0, '', NULL, NULL),
(293, 'Ginger Powder - Ingwer Pulver TRS 10X400g', '', 39.90, NULL, 0, 'GINGERPOW14511', 67, 27, 'uploads/products/1778214511_Ginger-powder-400g.jpg', 'active', 0, '2026-05-08 04:28:31', '2026-05-08 04:28:31', '2026-05-08', NULL, 0, NULL, 39.90, NULL, '', 0, '', NULL, NULL),
(294, 'Ginger Powder - Ingwer Pulver TRS 20X100g', '', 29.00, NULL, 0, 'GINGERPOW14589', 67, 27, 'uploads/products/1778214589_Screenshot_2026-05-08_095739.jpg', 'active', 0, '2026-05-08 04:29:49', '2026-05-08 04:29:49', '2026-05-08', NULL, 0, NULL, 29.00, NULL, '', 0, '', NULL, NULL),
(295, 'Kurkumapulver Pure 100% QUEENS 20X250g', '', 50.00, NULL, 0, 'KURKUMAPUL14978', 67, 14, NULL, 'active', 0, '2026-05-08 04:36:18', '2026-05-08 04:36:18', '2026-05-08', NULL, 0, NULL, 50.00, NULL, '', 0, '', NULL, NULL),
(296, 'Kurkumapulver Pure 100% QUEENS 12X500g', '', 55.00, NULL, 0, 'KURKUMAPUL14993', 67, 14, NULL, 'active', 0, '2026-05-08 04:36:33', '2026-05-08 04:36:33', '2026-05-08', NULL, 0, NULL, 55.00, NULL, '', 0, '', NULL, NULL),
(297, 'Kurkumapulver (Haldi) TRS 10X400g', '', 23.80, NULL, 0, 'KURKUMAPUL15063', 67, 27, 'uploads/products/1778215063_TRS-TURMERIC--600x600.jpg', 'active', 0, '2026-05-08 04:37:43', '2026-05-08 04:37:43', '2026-05-08', NULL, 0, NULL, 23.80, NULL, '', 0, '', NULL, NULL),
(298, 'Kurkumapulver (Haldi) ALIBABA 20X100g', '', 12.80, NULL, 0, 'KURKUMAPUL15420', 67, 56, 'uploads/products/1778215420_Screenshot_2026-05-08_101132.jpg', 'active', 0, '2026-05-08 04:43:40', '2026-05-08 04:43:40', '2026-05-08', NULL, 0, NULL, 12.80, NULL, '', 0, '', NULL, NULL),
(299, 'Kurkumapulver (Haldi) ALIBABA 10X400g', '', 19.00, NULL, 0, 'KURKUMAPUL15466', 67, 56, 'uploads/products/1778215466_Haldi-POWDER-ALI-BABA-10X400G-600x600.jpg', 'active', 0, '2026-05-08 04:44:26', '2026-05-08 04:44:26', '2026-05-08', NULL, 0, NULL, 19.00, NULL, '', 0, '', NULL, NULL),
(300, 'Kurkuma Wurzeln Getrocknet 100g', '', 0.80, NULL, 0, 'KURKUMAWU15522', 67, 56, 'uploads/products/1778215522_chakra-dried-turmeric-whole-100g_1.jpg', 'active', 0, '2026-05-08 04:45:22', '2026-05-08 04:45:22', '2026-05-08', NULL, 0, NULL, 0.80, NULL, '', 0, '', NULL, NULL),
(301, 'Black Jeera Whole/Kalonji ALIBABA 20X100g', '', 19.00, NULL, 0, 'BLACKJEER15567', 67, 56, 'uploads/products/1778215567_7550.jpg', 'active', 0, '2026-05-08 04:46:07', '2026-05-08 04:46:07', '2026-05-08', NULL, 0, NULL, 19.00, NULL, '', 0, '', NULL, NULL),
(302, 'Bockshornklee Blätter (Kasuri Methi) MDH 1kg', '', 22.00, NULL, 0, 'BOCKSHORNK15638', 67, 49, 'uploads/products/1778215638_7550.jpg', 'active', 0, '2026-05-08 04:47:18', '2026-05-08 04:47:18', '2026-05-08', NULL, 0, NULL, 22.00, NULL, '', 0, '', NULL, NULL),
(303, 'Bockshornklee Blätter (Kasuri Methi) MDH 1kg', '', 22.00, NULL, 0, 'BOCKSHORNK15674', 67, 49, 'uploads/products/1778215674_Screenshot_2026-05-08_101556.jpg', 'active', 0, '2026-05-08 04:47:54', '2026-05-08 04:47:54', '2026-05-08', NULL, 0, NULL, 22.00, NULL, '', 0, '', NULL, NULL),
(304, 'Bockshornklee Blätter (Kasuri Methi) MDH 6X100g', '', 17.00, NULL, 0, 'BOCKSHORNK15720', 67, 49, 'uploads/products/1778215720_MDH-100g-Peacock-Bockshornkleeblaetter--Kasoori-Methi--991663.jpg', 'active', 0, '2026-05-08 04:48:41', '2026-05-08 04:48:41', '2026-05-08', NULL, 0, NULL, 17.00, NULL, '', 0, '', NULL, NULL),
(305, 'Bockshornklee Blätter (Kasuri Methi) TRS 6X100g', '', 12.00, NULL, 0, 'BOCKSHORNK15795', 67, 27, 'uploads/products/1778215795_Screenshot_2026-05-08_101748.jpg', 'active', 0, '2026-05-08 04:49:55', '2026-05-08 04:49:55', '2026-05-08', NULL, 0, NULL, 12.00, NULL, '', 0, '', NULL, NULL),
(306, 'Paprika Powder ALIBABA 20X100g', '', 23.90, NULL, 0, 'PAPRIKAPO15841', 67, 27, 'uploads/products/1778215841_3474.jpg', 'active', 0, '2026-05-08 04:50:41', '2026-05-08 04:50:41', '2026-05-08', NULL, 0, NULL, 23.90, NULL, '', 0, '', NULL, NULL),
(307, 'Paprika Powder ALIBABA 10X400g', '', 35.00, NULL, 0, 'PAPRIKAPO15886', 67, 56, 'uploads/products/1778215886_3475.jpg', 'active', 0, '2026-05-08 04:51:26', '2026-05-08 04:51:26', '2026-05-08', NULL, 0, NULL, 35.00, NULL, '', 0, '', NULL, NULL),
(308, 'Garam Masala Powder ALIBABA 10X400g', '', 33.00, NULL, 0, 'GARAMMASA15922', 67, 56, 'uploads/products/1778215922_3140.jpg', 'active', 0, '2026-05-08 04:52:02', '2026-05-08 04:52:02', '2026-05-08', NULL, 0, NULL, 33.00, NULL, '', 0, '', NULL, NULL),
(309, 'Garam Masala Powder ALIBABA 10X400g', '', 33.00, NULL, 0, 'GARAMMASA15959', 67, 56, 'uploads/products/1778215959_3140.jpg', 'active', 0, '2026-05-08 04:52:39', '2026-05-08 04:52:39', '2026-05-08', NULL, 0, NULL, 33.00, NULL, '', 0, '', NULL, NULL),
(310, 'Garam Masala Powder ALIBABA 20X100g', '', 16.50, NULL, 0, 'GARAMMASA16001', 67, 56, 'uploads/products/1778216001_4945.jpg', 'active', 0, '2026-05-08 04:53:21', '2026-05-08 04:53:21', '2026-05-08', NULL, 0, NULL, 16.50, NULL, '', 0, '', NULL, NULL),
(311, 'Garam Masala Powder MDH 10X100g', '', 22.00, NULL, 0, 'GARAMMASA16081', 67, 49, 'uploads/products/1778216081_mdh-garam-masala-100gm-500x500-1.jpg', 'active', 0, '2026-05-08 04:54:41', '2026-05-08 04:54:41', '2026-05-08', NULL, 0, NULL, 22.00, NULL, '', 0, '', NULL, NULL),
(312, 'Garam Masala Whole ALIBABA 15X200g', '', 33.50, NULL, 0, 'GARAMMASA16123', 67, 49, 'uploads/products/1778216123_0063_2361_4d1c80db-5804-4784-a6a3-e6fad7dc3987.jpg', 'active', 0, '2026-05-08 04:55:23', '2026-05-08 04:55:23', '2026-05-08', NULL, 0, NULL, 33.50, NULL, '', 0, '', NULL, NULL),
(313, 'Garam Masala Whole ALIBABA 20X100g', '', 26.00, NULL, 0, 'GARAMMASA16183', 67, 56, 'uploads/products/1778216183_0063_2361_4d1c80db-5804-4784-a6a3-e6fad7dc3987.jpg', 'active', 0, '2026-05-08 04:56:23', '2026-05-08 04:56:23', '2026-05-08', NULL, 0, NULL, 26.00, NULL, '', 0, '', NULL, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'store_name', 'Sivakamy', 'general', '2025-04-25 07:59:26', '2026-05-07 14:35:19'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `transaction_id`, `payment_method`, `amount`, `status`, `created_at`) VALUES
(1, 10, NULL, 'cash', 26214520.00, 'completed', '2025-08-25 06:21:03'),
(2, 11, NULL, 'cash', 99999999.99, 'completed', '2025-08-25 19:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `short_name` varchar(30) NOT NULL,
  `allow_decimal` tinyint(1) NOT NULL DEFAULT 0,
  `is_multiple` tinyint(1) NOT NULL DEFAULT 0,
  `multiplier` decimal(15,4) DEFAULT NULL,
  `base_unit_id` int(10) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `short_name`, `allow_decimal`, `is_multiple`, `multiplier`, `base_unit_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Piece', 'pc', 0, 0, NULL, NULL, 1, '2026-03-31 10:25:13', '2026-03-31 10:25:13'),
(2, 'Kilogram', 'kg', 1, 0, NULL, NULL, 1, '2026-03-31 10:25:13', '2026-03-31 10:25:13'),
(3, 'Liter', 'l', 1, 0, NULL, NULL, 1, '2026-03-31 10:25:13', '2026-03-31 10:25:13'),
(4, 'Box', 'box', 0, 1, 12.0000, 1, 1, '2026-03-31 10:25:13', '2026-03-31 10:25:13'),
(5, 'test', 'kh', 1, 0, NULL, NULL, 1, '2026-03-31 10:25:59', '2026-03-31 10:25:59'),
(6, 'ML', 'ml', 1, 1, 1000.0000, 3, 1, '2026-04-16 08:43:33', '2026-04-16 08:43:33');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_orders_payment_created` (`payment_status`,`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_items_product_id` (`product_id`);

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
  ADD KEY `fk_products_brands` (`brand_id`),
  ADD KEY `idx_products_sku` (`sku`),
  ADD KEY `idx_products_category_id` (`category_id`),
  ADD KEY `idx_products_status` (`status`);

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
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_units_name` (`name`),
  ADD UNIQUE KEY `uniq_units_short_name` (`short_name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=314;

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
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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


-- =====================================================
-- ENTERPRISE PERFORMANCE INDEXES
-- =====================================================

ALTER TABLE `products`
ADD INDEX `idx_product_name` (`name`),
ADD INDEX `idx_category` (`category_id`),
ADD INDEX `idx_brand` (`brand_id`),
ADD INDEX `idx_status` (`status`),
ADD INDEX `idx_stock` (`stock_quantity`),
ADD INDEX `idx_sku` (`sku`),
ADD INDEX `idx_created_at` (`created_at`),
ADD INDEX `idx_expiry_date` (`expiry_date`);



ALTER TABLE `order_items`
ADD INDEX `idx_order_id` (`order_id`),
ADD INDEX `idx_product_id` (`product_id`);

ALTER TABLE `cart`
ADD INDEX `idx_cart_user` (`user_id`),
ADD INDEX `idx_cart_product` (`product_id`);



-- =====================================================
-- SAFE ORDERS INDEX OPTIMIZATION
-- Run only if indexes do not already exist
-- =====================================================

-- If import gives duplicate key/index errors,
-- your database already contains these indexes.

-- Safe optional indexes:

-- CREATE INDEX idx_customer ON orders(customer_id);
-- CREATE INDEX idx_order_date ON orders(created_at);
-- CREATE INDEX idx_order_status ON orders(status);

