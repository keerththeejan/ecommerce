-- Banner Management System Migration
-- Create banners table for modern admin dashboard

-- Create banners table
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(500) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('active','inactive','scheduled') NOT NULL DEFAULT 'active',
  `type` enum('general','hero','mobile','promotional','campaign','seasonal','offer') NOT NULL DEFAULT 'general',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `impressions` int(11) NOT NULL DEFAULT 0,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_type` (`type`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_dates` (`start_date`, `end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create banner settings table
CREATE TABLE IF NOT EXISTS `banner_settings` (
  `id` int(11) NOT NULL,
  `auto_slide` tinyint(1) NOT NULL DEFAULT 1,
  `slide_interval` int(11) NOT NULL DEFAULT 5000,
  `transition_effect` enum('fade','slide','zoom') NOT NULL DEFAULT 'fade',
  `show_arrows` tinyint(1) NOT NULL DEFAULT 1,
  `show_dots` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create banner analytics table
CREATE TABLE IF NOT EXISTS `banner_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `event_type` enum('impression','click') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_banner_id` (`banner_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_banner_analytics_banner` FOREIGN KEY (`banner_id`) REFERENCES `banners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default banner settings
INSERT IGNORE INTO `banner_settings` (`id`, `auto_slide`, `slide_interval`, `transition_effect`, `show_arrows`, `show_dots`) 
VALUES (1, 1, 5000, 'fade', 1, 1);

-- Create sample banners for demonstration
INSERT IGNORE INTO `banners` (`id`, `title`, `description`, `image`, `cta_text`, `cta_link`, `status`, `type`, `sort_order`) 
VALUES 
(1, 'Summer Sale 2026', 'Get up to 50% off on selected items', 'uploads/banners/summer-sale-2026.jpg', 'Shop Now', '/products?sale=summer', 'active', 'hero', 1),
(2, 'New Collection', 'Check out our latest arrivals', 'uploads/banners/new-collection.jpg', 'Explore', '/collections/new', 'active', 'hero', 2),
(3, 'Flash Sale', 'Limited time offer - 24 hours only!', 'uploads/banners/flash-sale.jpg', 'Grab Deal', '/deals/flash', 'active', 'promotional', 1),
(4, 'Mobile Exclusive', 'Special offers for mobile users', 'uploads/banners/mobile-exclusive.jpg', 'Get Offer', '/mobile-offers', 'active', 'mobile', 1);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_banner_composite` ON `banners` (`status`, `type`, `sort_order`);
CREATE INDEX IF NOT EXISTS `idx_analytics_composite` ON `banner_analytics` (`banner_id`, `event_type`, `created_at`);
