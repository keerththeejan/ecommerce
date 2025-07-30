-- Add columns for better content management
ALTER TABLE `footer_sections` 
ADD COLUMN `subtitle` VARCHAR(255) NULL AFTER `title`,
ADD COLUMN `icon` VARCHAR(100) NULL AFTER `subtitle`,
ADD COLUMN `custom_class` VARCHAR(100) NULL AFTER `icon`,
ADD COLUMN `is_custom` TINYINT(1) NOT NULL DEFAULT 0 AFTER `custom_class`,
MODIFY COLUMN `content` LONGTEXT DEFAULT NULL;

-- Create a table for footer section fields
CREATE TABLE IF NOT EXISTS `footer_section_fields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `section_id` INT(11) NOT NULL,
  `field_name` VARCHAR(100) NOT NULL,
  `field_value` TEXT NULL,
  `field_type` VARCHAR(50) NOT NULL DEFAULT 'text',
  `field_label` VARCHAR(100) NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `idx_section_id` (`section_id`),
  CONSTRAINT `fk_section_fields` FOREIGN KEY (`section_id`) REFERENCES `footer_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
