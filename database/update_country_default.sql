-- Migration: Update country default value to 'India'
-- This migration updates the default value for the 'country' column in the properties table
-- Run this migration if your database was created before the schema was updated for Indian market

-- Check if column exists and update default value
ALTER TABLE `properties` 
MODIFY COLUMN `country` varchar(100) DEFAULT 'India';

-- Note: This only affects new records. To update existing NULL values:
-- UPDATE `properties` SET `country` = 'India' WHERE `country` IS NULL OR `country` = '';

