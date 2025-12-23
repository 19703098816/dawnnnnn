<?php
/**
 * Force Insert 20 Forum Posts - Direct Database Insertion
 * This script forcefully inserts 20 forum posts into the 513week7 database
 * Access via: https://dawn1.infinityfreeapp.com/513week7/force_insert_forum_posts.php
 */

require_once __DIR__ . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

$messages = [];
$errors = [];

// Step 1: Verify database connection
try {
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_db = $result['db'] ?? 'unknown';
    $messages[] = "✓ Connected to database: <strong>" . htmlspecialchars($current_db) . "</strong>";
    
    if (strpos($current_db, '513week7') === false) {
        $errors[] = "✗ ERROR: Not connected to 513week7 database! Current: " . htmlspecialchars($current_db);
    }
} catch (PDOException $e) {
    $errors[] = "✗ Database connection error: " . htmlspecialchars($e->getMessage());
    die("Database connection failed!");
}

// Step 2: Create table if not exists
try {
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
    $messages[] = "✓ Table 'forum_posts' exists or created successfully";
} catch (PDOException $e) {
    $errors[] = "✗ Error creating table: " . htmlspecialchars($e->getMessage());
}

// Step 3: Get user_id
$user_id = 1;
try {
    $stmt = $pdo->query("SELECT user_id FROM users LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && isset($user['user_id'])) {
        $user_id = (int)$user['user_id'];
        $messages[] = "✓ Using user_id: $user_id";
    } else {
        $messages[] = "⚠ No users found, using default user_id: $user_id";
    }
} catch (PDOException $e) {
    $messages[] = "⚠ Could not get user_id from users table, using default: $user_id";
}

// Step 4: Check current post count
$before_count = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM forum_posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $before_count = (int)$result['count'];
    $messages[] = "✓ Current posts in database: $before_count";
} catch (PDOException $e) {
    $errors[] = "✗ Error counting posts: " . htmlspecialchars($e->getMessage());
}

// Step 5: Clear existing posts
try {
    $pdo->exec("DELETE FROM forum_posts");
    $messages[] = "✓ Cleared existing posts";
} catch (PDOException $e) {
    $errors[] = "✗ Error clearing posts: " . htmlspecialchars($e->getMessage());
}

// Step 6: Insert 20 posts
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
$failed = 0;

$stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");

foreach ($posts as $index => $post) {
    try {
        $stmt->execute([$user_id, $post[0], $post[1], $post[2], $post[3]]);
        $inserted++;
        $messages[] = "✓ Inserted post " . ($index + 1) . ": " . htmlspecialchars($post[0]);
    } catch (PDOException $e) {
        $failed++;
        $errors[] = "✗ Failed to insert post " . ($index + 1) . " (" . htmlspecialchars($post[0]) . "): " . htmlspecialchars($e->getMessage());
    }
}

// Step 7: Verify final count
$after_count = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM forum_posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $after_count = (int)$result['count'];
    $messages[] = "✓ Final posts in database: <strong>$after_count</strong>";
} catch (PDOException $e) {
    $errors[] = "✗ Error verifying count: " . htmlspecialchars($e->getMessage());
}

// Step 8: Show sample posts
$sample_posts = [];
try {
    // First check what columns exist in the table
    $stmt = $pdo->query("SHOW COLUMNS FROM forum_posts LIKE 'post_id'");
    $has_post_id = $stmt->rowCount() > 0;
    
    // Use the correct column name
    $id_column = $has_post_id ? 'post_id' : 'id';
    $stmt = $pdo->query("SELECT $id_column as post_id, title, category, created_at FROM forum_posts ORDER BY created_at DESC LIMIT 5");
    $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = "✗ Error fetching sample posts: " . htmlspecialchars($e->getMessage());
    // Try alternative query with id
    try {
        $stmt = $pdo->query("SELECT id as post_id, title, category, created_at FROM forum_posts ORDER BY created_at DESC LIMIT 5");
        $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e2) {
        // If both fail, try with *
        try {
            $stmt = $pdo->query("SELECT * FROM forum_posts ORDER BY created_at DESC LIMIT 5");
            $sample_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e3) {
            $errors[] = "✗ All query attempts failed: " . htmlspecialchars($e3->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Force Insert Forum Posts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
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
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #e3f2fd;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }
        .messages {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-family: monospace;
            font-size: 13px;
            max-height: 400px;
            overflow-y: auto;
        }
        .messages div {
            margin: 3px 0;
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
            font-weight: bold;
        }
        .link a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Force Insert Forum Posts</h1>
        <p class="subtitle">Direct insertion into 513week7 database</p>
        
        <?php if (count($messages) > 0): ?>
            <div class="messages">
                <?php foreach ($messages as $msg): ?>
                    <div><?php echo $msg; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (count($errors) > 0): ?>
            <div class="error">
                <strong>Errors:</strong><br>
                <?php foreach ($errors as $err): ?>
                    <div><?php echo $err; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($inserted > 0): ?>
            <div class="success">
                <strong>Success!</strong> Inserted <strong><?php echo $inserted; ?></strong> forum posts into the database.
                <?php if ($failed > 0): ?>
                    <br><strong>Failed:</strong> <?php echo $failed; ?> posts
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (count($sample_posts) > 0): ?>
            <div class="info">
                <strong>Sample Posts (Latest 5):</strong>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sample_posts as $post): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($post['post_id']); ?></td>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['category']); ?></td>
                                <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="link">
            <a href="<?php echo SITE_URL; ?>/forum.php">→ View Forum</a>
            <a href="<?php echo SITE_URL; ?>">→ Home</a>
        </div>
    </div>
</body>
</html>

