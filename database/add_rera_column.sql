-- Add RERA number column to properties table
-- Run this SQL query if your properties table already exists

ALTER TABLE `properties` 
ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`;

-- Update existing properties table if needed
-- This adds RERA number support for Indian real estate properties

