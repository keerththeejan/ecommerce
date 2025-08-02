-- Create tax_rates table if it doesn't exist
CREATE TABLE IF NOT EXISTS `tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tax1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax4` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default values
INSERT INTO `tax_rates` (`tax1`, `tax2`, `tax3`, `tax4`) 
SELECT 0.00, 0.00, 0.00, 0.00 
WHERE NOT EXISTS (SELECT 1 FROM `tax_rates` LIMIT 1);
