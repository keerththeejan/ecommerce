-- Add expiry_date, supplier, and batch_number columns to products table
-- This version avoids AFTER clauses so it won't fail if price3 doesn't exist
-- and uses IF NOT EXISTS for idempotency.
ALTER TABLE products 
    ADD COLUMN IF NOT EXISTS expiry_date DATE NULL,
    ADD COLUMN IF NOT EXISTS supplier VARCHAR(255) NULL,
    ADD COLUMN IF NOT EXISTS batch_number VARCHAR(100) NULL;
