<?php
/**
 * Insert 20 Forum Posts Script
 * This script directly inserts 20 forum posts into the database
 * Access via: https://dawn1.infinityfreeapp.com/513week7/insert_forum_posts.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

$message = '';
$error = '';

// Verify we're using the correct database (513week7)
$current_db = '';
try {
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_db = $result['db'] ?? '';
    $message .= "Connected to database: <strong>" . htmlspecialchars($current_db) . "</strong><br>";
} catch (PDOException $e) {
    $error .= "Error checking database: " . htmlspecialchars($e->getMessage()) . "<br>";
}

try {
    // Ensure forum_posts table exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS forum_posts (
        post_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        category VARCHAR(50) NOT NULL DEFAULT 'general',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_category (category),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // Get a valid user_id
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

    // Clear existing posts to start fresh
    $pdo->exec("DELETE FROM forum_posts");
    $message .= "Cleared existing posts. ";

    // Define 20 forum posts
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

    // Insert all 20 posts
    $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");
    
    $inserted = 0;
    foreach ($posts as $post) {
        try {
            $stmt->execute([$user_id, $post[0], $post[1], $post[2], $post[3]]);
            $inserted++;
        } catch (PDOException $e) {
            $error .= "Error inserting post: " . htmlspecialchars($post[0]) . " - " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }

    $message .= "Successfully inserted $inserted forum posts!";

    // Verify count and show details
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM forum_posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $final_count = (int)$result['count'];
    $message .= " Total posts in database: <strong>$final_count</strong><br>";
    
    // Show sample posts - check which column name exists
    $sample_posts = [];
    try {
        // Check if post_id column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM forum_posts LIKE 'post_id'");
        $has_post_id = $stmt->rowCount() > 0;
        $id_column = $has_post_id ? 'post_id' : 'id';
        $stmt = $pdo->query("SELECT $id_column as post_id, title, category, created_at FROM forum_posts ORDER BY created_at DESC LIMIT 5");
        $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Try with id
        try {
            $stmt = $pdo->query("SELECT id as post_id, title, category, created_at FROM forum_posts ORDER BY created_at DESC LIMIT 5");
            $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e2) {
            // Try with *
            try {
                $stmt = $pdo->query("SELECT * FROM forum_posts ORDER BY created_at DESC LIMIT 5");
                $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e3) {
                // Ignore error, just don't show sample posts
            }
        }
    }
    
    if (count($sample_posts) > 0) {
        $message .= "<br><strong>Sample posts (latest 5):</strong><br>";
        $message .= "<ul>";
        foreach ($sample_posts as $post) {
            $post_id = $post['post_id'] ?? $post['id'] ?? 'N/A';
            $title = htmlspecialchars($post['title'] ?? 'N/A');
            $category = htmlspecialchars($post['category'] ?? 'N/A');
            $created = $post['created_at'] ?? 'N/A';
            $message .= "<li>ID: $post_id - $title ($category) - $created</li>";
        }
        $message .= "</ul>";
    }

} catch (PDOException $e) {
    $error = "Database error: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Forum Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
        .link {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .link a {
            color: #0055aa;
            text-decoration: none;
            margin-right: 15px;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Insert Forum Posts</h1>
        
        <?php if ($message): ?>
            <div class="success">
                <strong>Success!</strong><br>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error">
                <strong>Error!</strong><br>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="link">
            <a href="<?php echo SITE_URL; ?>/forum.php">View Forum</a>
            <a href="<?php echo SITE_URL; ?>">Home</a>
        </div>
    </div>
</body>
</html>

