-- Add price2 and price3 columns to products table
ALTER TABLE products 
ADD COLUMN price2 DECIMAL(10, 2) NULL AFTER sale_price,
ADD COLUMN price3 DECIMAL(10, 2) NULL AFTER price2;

-- Update existing products to have default values for the new columns
UPDATE products SET price2 = price, price3 = price;
