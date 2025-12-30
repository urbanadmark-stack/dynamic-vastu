-- Real Estate Listing Database Schema
-- 
-- IMPORTANT: For existing databases created before the Indian market update:
-- 1. Run database/add_rera_column.sql to add RERA number support
-- 2. Run database/update_country_default.sql to update country default to 'India'
--
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `property_type` enum('house','apartment','villa','commercial','land') NOT NULL DEFAULT 'house',
  `status` enum('for_sale','for_rent','sold','rented') NOT NULL DEFAULT 'for_sale',
  `price` decimal(15,2) NOT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `area` decimal(10,2) DEFAULT NULL,
  `area_unit` varchar(20) DEFAULT 'sqft',
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `images` text,
  `features` text,
  `year_built` int(11) DEFAULT NULL,
  `parking` int(11) DEFAULT NULL,
  `agent_name` varchar(255) DEFAULT NULL,
  `agent_phone` varchar(50) DEFAULT NULL,
  `agent_email` varchar(255) DEFAULT NULL,
  `rera_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_type` (`property_type`),
  KEY `status` (`status`),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (only if it doesn't exist)
-- DEFAULT PASSWORD: admin123
-- IMPORTANT: Change this password immediately after installation!
-- 
-- To set a new password:
-- 1. Use setup-password.php to generate a password hash, OR
-- 2. Use PHP: echo password_hash('your_password', PASSWORD_DEFAULT);
-- 3. Update the password field in admin_users table with the generated hash
--
-- Default hash for 'admin123' (change this!):
INSERT IGNORE INTO `admin_users` (`username`, `email`, `password`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Projects / Developments Table Schema
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `project_type` enum('residential','commercial','mixed_use','plotted_development') NOT NULL DEFAULT 'residential',
  `project_status` enum('new_launch','under_construction','ready_to_move','oc_received') NOT NULL DEFAULT 'new_launch',
  `rera_number` varchar(100) NOT NULL,
  `rera_authority_state` varchar(100) DEFAULT NULL,
  `rera_valid_till` date DEFAULT NULL,
  `developer_name` varchar(255) DEFAULT NULL,
  `developer_rera_number` varchar(100) DEFAULT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `project_highlights` text DEFAULT NULL,
  `unit_configurations` text DEFAULT NULL,
  `price_range_min` decimal(15,2) DEFAULT NULL,
  `price_range_max` decimal(15,2) DEFAULT NULL,
  `all_inclusive_price` tinyint(1) DEFAULT 0,
  `price_breakup` text DEFAULT NULL,
  `payment_plans` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `structure_type` varchar(100) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `kitchen` varchar(255) DEFAULT NULL,
  `bathroom_fittings` varchar(255) DEFAULT NULL,
  `doors_windows` varchar(255) DEFAULT NULL,
  `electrical` varchar(255) DEFAULT NULL,
  `rera_certificate` varchar(255) DEFAULT NULL,
  `approved_plans` varchar(255) DEFAULT NULL,
  `commencement_certificate` varchar(255) DEFAULT NULL,
  `occupation_certificate` varchar(255) DEFAULT NULL,
  `land_title_clear` tinyint(1) DEFAULT 0,
  `launch_date` date DEFAULT NULL,
  `possession_date` date DEFAULT NULL,
  `construction_status` int(3) DEFAULT 0,
  `project_images` text DEFAULT NULL,
  `renders_3d` text DEFAULT NULL,
  `walkthrough_video` varchar(500) DEFAULT NULL,
  `brochure_pdf` varchar(255) DEFAULT NULL,
  `nearby_locations` text DEFAULT NULL,
  `sales_contact_name` varchar(255) DEFAULT NULL,
  `sales_mobile` varchar(20) DEFAULT NULL,
  `sales_email` varchar(255) DEFAULT NULL,
  `whatsapp_enabled` tinyint(1) DEFAULT 0,
  `assigned_agent_id` int(11) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `target_keywords` text DEFAULT NULL,
  `schema_markup_enabled` tinyint(1) DEFAULT 0,
  `featured_project` tinyint(1) DEFAULT 0,
  `hot_deal` tinyint(1) DEFAULT 0,
  `limited_units` tinyint(1) DEFAULT 0,
  `rera_info_verified` tinyint(1) DEFAULT 0,
  `prices_subject_to_change` tinyint(1) DEFAULT 1,
  `images_representational` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_type` (`project_type`),
  KEY `project_status` (`project_status`),
  KEY `city` (`city`),
  KEY `state` (`state`),
  KEY `featured_project` (`featured_project`),
  KEY `rera_number` (`rera_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

