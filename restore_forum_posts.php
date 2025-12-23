<?php
/**
 * Restore 20 Forum Posts
 * This script restores the original 20 forum posts to the forum_posts table
 */

require_once __DIR__ . '/config/database.php';

try {
    // Ensure forum_posts table exists with correct structure
    $pdo->exec("CREATE TABLE IF NOT EXISTS forum_posts (
        post_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        category VARCHAR(50) NOT NULL DEFAULT 'general',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_category (category),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Check if posts already exist
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM forum_posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $existing_count = (int)$result['count'];

    if ($existing_count >= 20) {
        echo "Forum posts already exist ($existing_count posts). Skipping insertion.\n";
        exit;
    }

    // Get a valid user_id (use first user from users table, or default to 1)
    $user_id = 1;
    try {
        $stmt = $pdo->query("SELECT user_id FROM users LIMIT 1");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && isset($user['user_id'])) {
            $user_id = (int)$user['user_id'];
        }
    } catch (PDOException $e) {
        // Use default user_id = 1
    }

    // Clear existing posts if any (optional - comment out if you want to keep existing posts)
    // $pdo->exec("DELETE FROM forum_posts");

    // Insert 20 forum posts
    $posts = [
        ['Welcome to the Forum!', 'This is a great place to share your thoughts about our products and services. Feel free to ask questions and share your experiences!', 'general', 20],
        ['Best Product I\'ve Ever Bought', 'I recently purchased the Elegant Bracelet and I\'m absolutely in love with it! The quality is outstanding and it looks even better in person.', 'products', 19],
        ['Shipping Question', 'How long does shipping usually take? I placed an order last week and haven\'t received a tracking number yet.', 'support', 18],
        ['Love the New Collection', 'The new artisan collection is amazing! So many unique pieces. Keep up the great work!', 'feedback', 17],
        ['Suggestion: More Categories', 'Would love to see more categories like home decor and kitchenware. What do you all think?', 'ideas', 16],
        ['Customer Service is Excellent', 'I had an issue with my order and the support team resolved it quickly. Very professional!', 'feedback', 15],
        ['Looking for Gift Ideas', 'Need suggestions for a birthday gift. What would you recommend for someone who loves handmade items?', 'general', 14],
        ['Product Quality is Top Notch', 'Every item I\'ve purchased has exceeded my expectations. The artisans really put their heart into their work.', 'products', 13],
        ['Return Policy Question', 'What is the return policy? I want to make sure before I make a large purchase.', 'support', 12],
        ['Feature Request: Wishlist', 'It would be great to have a wishlist feature so I can save items for later. Anyone else agree?', 'ideas', 11],
        ['Amazing Packaging', 'The packaging is so beautiful! It\'s clear that attention to detail is important here.', 'feedback', 10],
        ['Looking for Artisan Partners', 'I\'m an artisan looking to join the platform. How do I get started?', 'general', 9],
        ['Product Review: Ceramic Vase', 'The ceramic vase I bought is perfect for my living room. The design is modern and the quality is excellent.', 'products', 8],
        ['Payment Options', 'Do you accept PayPal? I prefer using PayPal for online purchases.', 'support', 7],
        ['Suggestion: Product Videos', 'It would be helpful to have product videos showing the items in use. Just a thought!', 'ideas', 6],
        ['Thank You for Great Service', 'Just wanted to say thank you for the excellent customer service. You\'ve gained a loyal customer!', 'feedback', 5],
        ['New User Here', 'Hi everyone! Just joined and excited to explore all the amazing products. Any recommendations for first-time buyers?', 'general', 4],
        ['Product Review: Wool Scarf', 'The wool scarf is so warm and soft. Perfect for winter! Highly recommend.', 'products', 3],
        ['Order Tracking', 'How can I track my order? I received a confirmation email but no tracking information.', 'support', 2],
        ['Love the Community', 'This forum is great! Everyone is so helpful and friendly. Great community here!', 'general', 1]
    ];

    $inserted = 0;
    $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");

    foreach ($posts as $post) {
        try {
            $stmt->execute([$user_id, $post[0], $post[1], $post[2], $post[3]]);
            $inserted++;
        } catch (PDOException $e) {
            echo "Error inserting post: " . $post[0] . " - " . $e->getMessage() . "\n";
        }
    }

    echo "Successfully inserted $inserted forum posts!\n";
    echo "You can now view them at: https://dawn1.infinityfreeapp.com/513week7/forum.php\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}
?>


