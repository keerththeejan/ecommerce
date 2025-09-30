-- Add last_activity column if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS last_activity DATETIME NULL DEFAULT NULL AFTER updated_at,
ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) NULL DEFAULT NULL AFTER last_activity,
ADD COLUMN IF NOT EXISTS user_agent TEXT NULL DEFAULT NULL AFTER ip_address,
ADD INDEX IF NOT EXISTS idx_last_activity (last_activity);
