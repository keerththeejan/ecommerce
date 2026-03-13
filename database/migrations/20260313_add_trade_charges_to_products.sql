-- Add HSN code, customs charge, and transport charge columns to products table
-- Uses IF NOT EXISTS for idempotency.

ALTER TABLE products 
    ADD COLUMN IF NOT EXISTS hsn_code VARCHAR(50) NULL,
    ADD COLUMN IF NOT EXISTS customs_charge DECIMAL(10,2) NULL,
    ADD COLUMN IF NOT EXISTS transport_charge DECIMAL(10,2) NULL;
