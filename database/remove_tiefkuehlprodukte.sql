-- Remove category "Tiefkühlprodukte"
-- Run in phpMyAdmin: select database (sn) -> SQL tab -> paste and Execute

UPDATE products SET category_id = NULL WHERE category_id IN (SELECT id FROM categories WHERE name = 'Tiefkühlprodukte');
DELETE FROM categories WHERE name = 'Tiefkühlprodukte';
