CREATE TABLE IF NOT EXISTS `footer_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `type` enum('about','links','contact','social','newsletter') NOT NULL DEFAULT 'links',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default sections
INSERT INTO `footer_sections` (`title`, `content`, `type`, `status`, `sort_order`) VALUES
('About Us', 'Your one-stop shop for quality products. We offer the best deals and fast delivery to your doorstep with a satisfaction guarantee on all purchases.', 'about', 'active', 1),
('Quick Links', '["Home","Shop","About","Contact"]', 'links', 'active', 2),
('Contact Us', '123 Street, City\nEmail: info@example.com\nPhone: +1 234 567 890', 'contact', 'active', 3),
('Follow Us', '["Facebook","Twitter","Instagram","LinkedIn"]', 'social', 'active', 4);
