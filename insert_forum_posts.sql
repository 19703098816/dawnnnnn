-- ============================================================================
-- Insert 20 Forum Posts into 513week7 Database
-- Database: if0_37969254_513week7
-- Table: forum_posts
-- ============================================================================
-- Execute this SQL in phpMyAdmin or via MySQL command line
-- ============================================================================

USE if0_37969254_513week7;

-- Ensure table exists
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `post_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'general',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_category` (`category`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clear existing posts (optional - comment out if you want to keep existing posts)
DELETE FROM `forum_posts`;

-- Get a valid user_id (use first user from users table, or default to 1)
SET @user_id = 1;
SELECT user_id INTO @user_id FROM users LIMIT 1;
SET @user_id = IFNULL(@user_id, 1);

-- Insert 20 forum posts
INSERT INTO `forum_posts` (`user_id`, `title`, `content`, `category`, `created_at`) VALUES
(@user_id, 'Welcome to the Forum!', 'This is a great place to share your thoughts about our products and services. Feel free to ask questions and share your experiences!', 'general', DATE_SUB(NOW(), INTERVAL 20 DAY)),
(@user_id, 'Best Product I\'ve Ever Bought', 'I recently purchased the Elegant Bracelet and I\'m absolutely in love with it! The quality is outstanding and it looks even better in person.', 'products', DATE_SUB(NOW(), INTERVAL 19 DAY)),
(@user_id, 'Shipping Question', 'How long does shipping usually take? I placed an order last week and haven\'t received a tracking number yet.', 'support', DATE_SUB(NOW(), INTERVAL 18 DAY)),
(@user_id, 'Love the New Collection', 'The new artisan collection is amazing! So many unique pieces. Keep up the great work!', 'feedback', DATE_SUB(NOW(), INTERVAL 17 DAY)),
(@user_id, 'Suggestion: More Categories', 'Would love to see more categories like home decor and kitchenware. What do you all think?', 'ideas', DATE_SUB(NOW(), INTERVAL 16 DAY)),
(@user_id, 'Customer Service is Excellent', 'I had an issue with my order and the support team resolved it quickly. Very professional!', 'feedback', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(@user_id, 'Looking for Gift Ideas', 'Need suggestions for a birthday gift. What would you recommend for someone who loves handmade items?', 'general', DATE_SUB(NOW(), INTERVAL 14 DAY)),
(@user_id, 'Product Quality is Top Notch', 'Every item I\'ve purchased has exceeded my expectations. The artisans really put their heart into their work.', 'products', DATE_SUB(NOW(), INTERVAL 13 DAY)),
(@user_id, 'Return Policy Question', 'What is the return policy? I want to make sure before I make a large purchase.', 'support', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(@user_id, 'Feature Request: Wishlist', 'It would be great to have a wishlist feature so I can save items for later. Anyone else agree?', 'ideas', DATE_SUB(NOW(), INTERVAL 11 DAY)),
(@user_id, 'Amazing Packaging', 'The packaging is so beautiful! It\'s clear that attention to detail is important here.', 'feedback', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(@user_id, 'Looking for Artisan Partners', 'I\'m an artisan looking to join the platform. How do I get started?', 'general', DATE_SUB(NOW(), INTERVAL 9 DAY)),
(@user_id, 'Product Review: Ceramic Vase', 'The ceramic vase I bought is perfect for my living room. The design is modern and the quality is excellent.', 'products', DATE_SUB(NOW(), INTERVAL 8 DAY)),
(@user_id, 'Payment Options', 'Do you accept PayPal? I prefer using PayPal for online purchases.', 'support', DATE_SUB(NOW(), INTERVAL 7 DAY)),
(@user_id, 'Suggestion: Product Videos', 'It would be helpful to have product videos showing the items in use. Just a thought!', 'ideas', DATE_SUB(NOW(), INTERVAL 6 DAY)),
(@user_id, 'Thank You for Great Service', 'Just wanted to say thank you for the excellent customer service. You\'ve gained a loyal customer!', 'feedback', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(@user_id, 'New User Here', 'Hi everyone! Just joined and excited to explore all the amazing products. Any recommendations for first-time buyers?', 'general', DATE_SUB(NOW(), INTERVAL 4 DAY)),
(@user_id, 'Product Review: Wool Scarf', 'The wool scarf is so warm and soft. Perfect for winter! Highly recommend.', 'products', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(@user_id, 'Order Tracking', 'How can I track my order? I received a confirmation email but no tracking information.', 'support', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(@user_id, 'Love the Community', 'This forum is great! Everyone is so helpful and friendly. Great community here!', 'general', DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Verify insertion
SELECT COUNT(*) as total_posts FROM forum_posts;
SELECT post_id, title, category, created_at FROM forum_posts ORDER BY created_at DESC;

