-- Projects / Developments Table Schema
-- This table stores comprehensive project information for real estate developments

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  
  -- 1. Basic Project Information (Mandatory)
  `project_name` varchar(255) NOT NULL,
  `project_type` enum('residential','commercial','mixed_use','plotted_development') NOT NULL DEFAULT 'residential',
  `project_status` enum('new_launch','under_construction','ready_to_move','oc_received') NOT NULL DEFAULT 'new_launch',
  `rera_number` varchar(100) NOT NULL,
  `rera_authority_state` varchar(100) DEFAULT NULL,
  `rera_valid_till` date DEFAULT NULL,
  `developer_name` varchar(255) DEFAULT NULL,
  `developer_rera_number` varchar(100) DEFAULT NULL,
  
  -- 2. Location Details
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `locality` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  
  -- 3. Project Overview
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `project_highlights` text DEFAULT NULL, -- JSON array
  
  -- 4. Configuration Details (JSON)
  `unit_configurations` text DEFAULT NULL, -- JSON array with unit types, areas, prices
  
  -- 5. Pricing & Payment
  `price_range_min` decimal(15,2) DEFAULT NULL,
  `price_range_max` decimal(15,2) DEFAULT NULL,
  `all_inclusive_price` tinyint(1) DEFAULT 0,
  `price_breakup` text DEFAULT NULL, -- JSON
  `payment_plans` text DEFAULT NULL, -- JSON array
  
  -- 6. Amenities (JSON array)
  `amenities` text DEFAULT NULL,
  
  -- 7. Project Specifications
  `structure_type` varchar(100) DEFAULT NULL,
  `flooring` varchar(255) DEFAULT NULL,
  `kitchen` varchar(255) DEFAULT NULL,
  `bathroom_fittings` varchar(255) DEFAULT NULL,
  `doors_windows` varchar(255) DEFAULT NULL,
  `electrical` varchar(255) DEFAULT NULL,
  
  -- 8. Legal & Compliance
  `rera_certificate` varchar(255) DEFAULT NULL, -- PDF file path
  `approved_plans` varchar(255) DEFAULT NULL, -- PDF file path
  `commencement_certificate` varchar(255) DEFAULT NULL, -- PDF file path
  `occupation_certificate` varchar(255) DEFAULT NULL, -- PDF file path
  `land_title_clear` tinyint(1) DEFAULT 0,
  
  -- 9. Construction Timeline
  `launch_date` date DEFAULT NULL,
  `possession_date` date DEFAULT NULL,
  `construction_status` int(3) DEFAULT 0, -- Percentage 0-100
  
  -- 10. Media Uploads
  `project_images` text DEFAULT NULL, -- JSON array of image paths
  `renders_3d` text DEFAULT NULL, -- JSON array
  `walkthrough_video` varchar(500) DEFAULT NULL, -- YouTube link
  `brochure_pdf` varchar(255) DEFAULT NULL, -- PDF file path
  
  -- 11. Connectivity & Nearby (JSON array)
  `nearby_locations` text DEFAULT NULL,
  
  -- 12. Contact & Lead Routing
  `sales_contact_name` varchar(255) DEFAULT NULL,
  `sales_mobile` varchar(20) DEFAULT NULL,
  `sales_email` varchar(255) DEFAULT NULL,
  `whatsapp_enabled` tinyint(1) DEFAULT 0,
  `assigned_agent_id` int(11) DEFAULT NULL,
  
  -- 13. SEO & Marketing
  `seo_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `target_keywords` text DEFAULT NULL,
  `schema_markup_enabled` tinyint(1) DEFAULT 0,
  `featured_project` tinyint(1) DEFAULT 0,
  `hot_deal` tinyint(1) DEFAULT 0,
  `limited_units` tinyint(1) DEFAULT 0,
  
  -- 14. Compliance Declarations
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

