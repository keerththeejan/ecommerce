-- Create footer_content table
CREATE TABLE IF NOT EXISTS `footer_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default about section
INSERT INTO `footer_content` (`section`, `title`, `content`, `status`) VALUES
('about', 'About Our Store', 'Your one-stop shop for quality products. We offer the best deals and fast delivery to your doorstep with a satisfaction guarantee on all purchases.', 1);
