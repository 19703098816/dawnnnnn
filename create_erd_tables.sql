-- ============================================================================
-- ERD Tables Creation Script
-- Dawn ArtisanCraft Marketplace
-- ============================================================================
-- This script creates the tables referenced in the ERD diagram
-- Run in appropriate databases as indicated
-- ============================================================================

-- ============================================================================
-- DATABASE: if0_37969254_wp802
-- ============================================================================
-- Note: wpah_fc_subscribers table is managed by FluentCRM plugin
-- This is a reference structure only

-- ============================================================================
-- DATABASE: if0_37969254_513week7
-- ============================================================================

-- Forum Posts Table
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL COMMENT 'References users.user_id or wpah_fc_subscribers.id (via session)',
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'general',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Discussion forum posts - linked to users via user_id';

-- Orders Table
CREATE TABLE IF NOT EXISTS `wp_orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `customer_email` VARCHAR(255) NOT NULL COMMENT 'References wpah_fc_subscribers.email (cross-database)',
  `product_ids` TEXT NOT NULL COMMENT 'JSON array of product IDs, e.g., [1, 2, 3]',
  `total_amount` DECIMAL(10,2) NOT NULL,
  `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`order_id`),
  INDEX `idx_customer_email` (`customer_email`),
  INDEX `idx_order_date` (`order_date`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Customer orders - linked to wpah_fc_subscribers via email (cross-database)';

-- Users Table (Optional - for local users)
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255),
  `first_name` VARCHAR(100),
  `last_name` VARCHAR(100),
  `phone` VARCHAR(20),
  `role` VARCHAR(20) DEFAULT 'customer',
  `email_verified` TINYINT(1) DEFAULT 0,
  `status` VARCHAR(20) DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  INDEX `idx_role` (`role`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Local user accounts - alternative to FluentCRM';

-- Products Table (Optional - currently using JSON file)
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT(11) DEFAULT 0,
  `image_url` VARCHAR(500),
  `category` VARCHAR(100),
  `artisan_id` INT(11),
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_artisan_id` (`artisan_id`),
  INDEX `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Product catalog - currently using JSON file, this table available for migration';

-- ============================================================================
-- Sample Data for Testing
-- ============================================================================

-- Insert sample forum posts (20 posts as required)
INSERT INTO `forum_posts` (`user_id`, `title`, `content`, `category`, `created_at`) VALUES
(1, 'Welcome to the Forum!', 'This is a great place to share your thoughts about our products and services. Feel free to ask questions and share your experiences!', 'general', NOW() - INTERVAL 20 DAY),
(1, 'Best Product I\'ve Ever Bought', 'I recently purchased the Elegant Bracelet and I\'m absolutely in love with it! The quality is outstanding and it looks even better in person.', 'products', NOW() - INTERVAL 19 DAY),
(1, 'Shipping Question', 'How long does shipping usually take? I placed an order last week and haven\'t received a tracking number yet.', 'support', NOW() - INTERVAL 18 DAY),
(1, 'Love the New Collection', 'The new artisan collection is amazing! So many unique pieces. Keep up the great work!', 'feedback', NOW() - INTERVAL 17 DAY),
(1, 'Suggestion: More Categories', 'Would love to see more categories like home decor and kitchenware. What do you all think?', 'ideas', NOW() - INTERVAL 16 DAY),
(1, 'Customer Service is Excellent', 'I had an issue with my order and the support team resolved it quickly. Very professional!', 'feedback', NOW() - INTERVAL 15 DAY),
(1, 'Looking for Gift Ideas', 'Need suggestions for a birthday gift. What would you recommend for someone who loves handmade items?', 'general', NOW() - INTERVAL 14 DAY),
(1, 'Product Quality is Top Notch', 'Every item I\'ve purchased has exceeded my expectations. The artisans really put their heart into their work.', 'products', NOW() - INTERVAL 13 DAY),
(1, 'Return Policy Question', 'What is the return policy? I want to make sure before I make a large purchase.', 'support', NOW() - INTERVAL 12 DAY),
(1, 'Feature Request: Wishlist', 'It would be great to have a wishlist feature so I can save items for later. Anyone else agree?', 'ideas', NOW() - INTERVAL 11 DAY),
(1, 'Amazing Packaging', 'The packaging is so beautiful! It\'s clear that attention to detail is important here.', 'feedback', NOW() - INTERVAL 10 DAY),
(1, 'Looking for Artisan Partners', 'I\'m an artisan looking to join the platform. How do I get started?', 'general', NOW() - INTERVAL 9 DAY),
(1, 'Product Review: Ceramic Vase', 'The ceramic vase I bought is perfect for my living room. The design is modern and the quality is excellent.', 'products', NOW() - INTERVAL 8 DAY),
(1, 'Payment Options', 'Do you accept PayPal? I prefer using PayPal for online purchases.', 'support', NOW() - INTERVAL 7 DAY),
(1, 'Suggestion: Product Videos', 'It would be helpful to have product videos showing the items in use. Just a thought!', 'ideas', NOW() - INTERVAL 6 DAY),
(1, 'Thank You for Great Service', 'Just wanted to say thank you for the excellent customer service. You\'ve gained a loyal customer!', 'feedback', NOW() - INTERVAL 5 DAY),
(1, 'New User Here', 'Hi everyone! Just joined and excited to explore all the amazing products. Any recommendations for first-time buyers?', 'general', NOW() - INTERVAL 4 DAY),
(1, 'Product Review: Wool Scarf', 'The wool scarf is so warm and soft. Perfect for winter! Highly recommend.', 'products', NOW() - INTERVAL 3 DAY),
(1, 'Order Tracking', 'How can I track my order? I received a confirmation email but no tracking information.', 'support', NOW() - INTERVAL 2 DAY),
(1, 'Love the Community', 'This forum is great! Everyone is so helpful and friendly. Great community here!', 'general', NOW() - INTERVAL 1 DAY);

-- ============================================================================
-- Relationship Documentation
-- ============================================================================
-- 
-- 1. wpah_fc_subscribers (wp802) → wp_orders (513week7)
--    Relationship: One-to-Many
--    Linking: wpah_fc_subscribers.email = wp_orders.customer_email
--    Type: Cross-database (email-based)
--
-- 2. wpah_fc_subscribers (wp802) → forum_posts (513week7)
--    Relationship: One-to-Many
--    Linking: wpah_fc_subscribers.id → forum_posts.user_id (via session)
--    Type: Cross-database (session-based mapping)
--
-- 3. users (513week7) → forum_posts (513week7)
--    Relationship: One-to-Many
--    Linking: users.user_id = forum_posts.user_id
--    Type: Same database (foreign key possible)
--
-- 4. products (JSON/513week7) → wp_orders (513week7)
--    Relationship: Many-to-Many
--    Linking: Product IDs stored in wp_orders.product_ids as JSON array
--    Type: JSON-based (no foreign key)
--
-- ============================================================================

SELECT 'ERD tables created successfully' AS status;

