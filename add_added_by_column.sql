-- SQL to add the 'added_by' column to the food_items table

-- First, check if the column already exists
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'login_db12' 
AND TABLE_NAME = 'food_items' 
AND COLUMN_NAME = 'added_by';

-- Add the added_by column if it doesn't exist
ALTER TABLE food_items 
ADD COLUMN added_by VARCHAR(100) DEFAULT 'Unknown' 
AFTER created_at;

-- Update existing records to have a default value
UPDATE food_items 
SET added_by = 'Unknown' 
WHERE added_by IS NULL OR added_by = '';

-- Verify the column was added successfully
DESCRIBE food_items;

-- Show sample data with the new column
SELECT id, food_name, added_by, created_at 
FROM food_items 
LIMIT 5; 