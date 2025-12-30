-- Real Estate Listing Database Schema
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
  `country` varchar(100) DEFAULT 'USA',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `images` text,
  `features` text,
  `year_built` int(11) DEFAULT NULL,
  `parking` int(11) DEFAULT NULL,
  `agent_name` varchar(255) DEFAULT NULL,
  `agent_phone` varchar(50) DEFAULT NULL,
  `agent_email` varchar(255) DEFAULT NULL,
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

-- Insert default admin user
-- DEFAULT PASSWORD: admin123
-- IMPORTANT: Change this password immediately after installation!
-- 
-- To set a new password:
-- 1. Use setup-password.php to generate a password hash, OR
-- 2. Use PHP: echo password_hash('your_password', PASSWORD_DEFAULT);
-- 3. Update the password field in admin_users table with the generated hash
--
-- Default hash for 'admin123' (change this!):
INSERT INTO `admin_users` (`username`, `email`, `password`) VALUES
('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

