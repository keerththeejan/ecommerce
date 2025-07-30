-- Create countries table
CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(3) NOT NULL,
    flag_image VARCHAR(255),
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add country_id to products table
ALTER TABLE products ADD COLUMN country_id INT;
ALTER TABLE products ADD CONSTRAINT fk_product_country FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL;

-- Insert sample countries
INSERT INTO countries (name, code, status) VALUES
('United States', 'USA', 'active'),
('China', 'CHN', 'active'),
('Japan', 'JPN', 'active'),
('Germany', 'DEU', 'active'),
('United Kingdom', 'GBR', 'active'),
('France', 'FRA', 'active'),
('Italy', 'ITA', 'active'),
('South Korea', 'KOR', 'active'),
('India', 'IND', 'active'),
('Canada', 'CAN', 'active');

-- Update some products with country_id
UPDATE products SET country_id = 1 WHERE id = 1; -- Smartphone X from USA
UPDATE products SET country_id = 3 WHERE id = 2; -- Laptop Pro from Japan
UPDATE products SET country_id = 5 WHERE id = 3; -- T-shirt Basic from UK
UPDATE products SET country_id = 4 WHERE id = 4; -- Coffee Maker from Germany
UPDATE products SET country_id = 1 WHERE id = 5; -- Novel: The Journey from USA
