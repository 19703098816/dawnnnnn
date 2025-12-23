-- Database: if0_37969254_513week7
-- Create tables for recruitment system

-- Table for job positions
CREATE TABLE IF NOT EXISTS `job_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT 'fas fa-briefcase',
  `description` text NOT NULL,
  `requirements` text NOT NULL COMMENT 'JSON array of requirements',
  `location` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL COMMENT 'Full-time, Part-time, etc.',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default job positions
INSERT INTO `job_positions` (`title`, `icon`, `description`, `requirements`, `location`, `type`, `is_active`) VALUES
('Web Developer', 'fas fa-code', 'Develop and maintain our e-commerce platform and web applications.', '["3+ years PHP/JavaScript experience", "Experience with databases", "Strong problem-solving skills"]', 'Remote / On-site', 'Full-time', 1),
('Marketing Specialist', 'fas fa-bullhorn', 'Drive brand awareness and customer engagement through digital marketing campaigns.', '["2+ years marketing experience", "Social media expertise", "Analytics proficiency"]', 'Hybrid', 'Full-time', 1),
('Customer Support', 'fas fa-headset', 'Provide exceptional customer service and support to our community of artisans and buyers.', '["Excellent communication skills", "Customer service experience", "Problem-solving ability"]', 'Remote', 'Full-time / Part-time', 1),
('Product Manager', 'fas fa-tasks', 'Lead product development and strategy to enhance our marketplace platform.', '["5+ years product management", "E-commerce experience", "Strong leadership skills"]', 'On-site', 'Full-time', 1);

-- Table for job applications
CREATE TABLE IF NOT EXISTS `job_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `experience` text NOT NULL,
  `cover_letter` text NOT NULL,
  `cv_file_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending' COMMENT 'pending, reviewed, accepted, rejected',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `position` (`position`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

