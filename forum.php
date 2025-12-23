<?php
$page_title = "Discussion Forum - Dawn's ArtisanCraft";
$page_description = "Share your thoughts and opinions in our community forum";
require_once 'config/database.php';
require_once 'includes/functions.php';

// Require login
redirectIfNotLoggedIn();

// Handle filters and sorting
$category_filter = $_GET['category'] ?? 'all';
$sort_by = $_GET['sort'] ?? 'newest';
$search_query = sanitize_text_field($_GET['search'] ?? '');

// Handle post submission
$success_msg = '';
$error_msg = '';

// Create forum_posts table if it doesn't exist
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
} catch (PDOException $e) {
    // Table might already exist
}

// Create forum_replies table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS forum_replies (
        reply_id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_post_id (post_id),
        INDEX idx_user_id (user_id),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (PDOException $e) {
    // Table might already exist
}

// Auto-insert 20 forum posts if table is empty or has less than 20 posts
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM forum_posts");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $post_count = (int)$result['count'];
    
    if ($post_count < 20) {
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
        
        // If no posts exist, clear table first to ensure clean start
        if ($post_count == 0) {
            $pdo->exec("DELETE FROM forum_posts");
        }
        
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
        
        // Insert posts (only insert the missing ones)
        $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY))");
        
        $inserted = 0;
        foreach ($posts as $post) {
            // Check if this post already exists (by title)
            $check_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM forum_posts WHERE title = ?");
            $check_stmt->execute([$post[0]]);
            $exists = $check_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ((int)$exists['count'] == 0) {
                try {
                    $stmt->execute([$user_id, $post[0], $post[1], $post[2], $post[3]]);
                    $inserted++;
                } catch (PDOException $e) {
                    error_log("Error inserting forum post: " . $post[0] . " - " . $e->getMessage());
                }
            }
        }
        
        if ($inserted > 0) {
            error_log("Auto-inserted $inserted forum posts");
        }
    }
} catch (PDOException $e) {
    error_log("Error checking/inserting forum posts: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $title = sanitize_text_field($_POST['title'] ?? '');
    $content = sanitize_textarea_field($_POST['content'] ?? '');
    $category = sanitize_text_field($_POST['category'] ?? 'general');
    
    if (empty($title) || empty($content)) {
        $error_msg = 'Please fill in both title and content.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, title, content, category, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $title, $content, $category]);
            $success_msg = 'Your post has been submitted successfully!';
            $title = $content = '';
            // Redirect to avoid resubmission
            header("Location: " . SITE_URL . "/forum.php");
            exit;
        } catch (Exception $e) {
            $error_msg = 'An error occurred. Please try again later.';
        }
    }
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $post_id = (int)($_POST['post_id'] ?? 0);
    $reply_content = sanitize_textarea_field($_POST['reply_content'] ?? '');
    
    if (empty($reply_content) || $post_id <= 0) {
        $error_msg = 'Please enter a reply.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO forum_replies (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$post_id, $_SESSION['user_id'], $reply_content]);
            $success_msg = 'Your reply has been posted successfully!';
            // Redirect to avoid resubmission
            header("Location: " . SITE_URL . "/forum.php");
            exit;
        } catch (Exception $e) {
            $error_msg = 'An error occurred while posting your reply. Please try again later.';
        }
    }
}

// Build query with filters
$where_conditions = [];
$params = [];

if ($category_filter !== 'all') {
    $where_conditions[] = "fp.category = ?";
    $params[] = $category_filter;
}

if (!empty($search_query)) {
    $where_conditions[] = "(fp.title LIKE ? OR fp.content LIKE ?)";
    $search_param = "%{$search_query}%";
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Determine sort order
$order_by = "fp.created_at DESC";
switch ($sort_by) {
    case 'oldest':
        $order_by = "fp.created_at ASC";
        break;
    case 'title':
        $order_by = "fp.title ASC";
        break;
    case 'category':
        $order_by = "fp.category ASC, fp.created_at DESC";
        break;
    default:
        $order_by = "fp.created_at DESC";
}

// Get all posts with filters
try {
    // First check which column name exists (id or post_id)
    $id_column = 'post_id';
    try {
        $check_stmt = $pdo->query("SHOW COLUMNS FROM forum_posts LIKE 'post_id'");
        if ($check_stmt->rowCount() == 0) {
            $id_column = 'id';
        }
    } catch (PDOException $e) {
        // Default to id if check fails
        $id_column = 'id';
    }
    
    // Build query with correct column name
    $stmt = $pdo->prepare("
        SELECT fp.*, fp.$id_column as post_id, u.first_name, u.last_name, u.email,
               (SELECT COUNT(*) FROM forum_posts fp2 WHERE fp2.category = fp.category) as category_count,
               (SELECT COUNT(*) FROM forum_replies fr WHERE fr.post_id = fp.$id_column) as reply_count
        FROM forum_posts fp 
        LEFT JOIN users u ON fp.user_id = u.user_id 
        $where_clause
        ORDER BY $order_by
    ");
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Normalize post_id - ensure all posts have post_id key
    foreach ($posts as &$post) {
        if (!isset($post['post_id']) && isset($post['id'])) {
            $post['post_id'] = $post['id'];
        }
    }
    unset($post);
    
    // Get replies for each post
    $replies_by_post = [];
    foreach ($posts as $post) {
        $current_post_id = $post['post_id'] ?? $post['id'] ?? 0;
        if ($current_post_id > 0) {
            try {
                $reply_stmt = $pdo->prepare("
                    SELECT fr.*, u.first_name, u.last_name, u.email
                    FROM forum_replies fr
                    LEFT JOIN users u ON fr.user_id = u.user_id
                    WHERE fr.post_id = ?
                    ORDER BY fr.created_at ASC
                ");
                $reply_stmt->execute([$current_post_id]);
                $replies_by_post[$current_post_id] = $reply_stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $replies_by_post[$current_post_id] = [];
            }
        }
    }
    
    // Get category counts
    $category_counts = [];
    $cat_stmt = $pdo->query("SELECT category, COUNT(*) as count FROM forum_posts GROUP BY category");
    $cat_results = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cat_results as $cat) {
        $category_counts[$cat['category']] = $cat['count'];
    }
} catch (Exception $e) {
    $posts = [];
    $category_counts = [];
    $replies_by_post = [];
}

// Group posts by category for better organization
$posts_by_category = [];
foreach ($posts as $post) {
    $cat = $post['category'] ?? 'general';
    if (!isset($posts_by_category[$cat])) {
        $posts_by_category[$cat] = [];
    }
    $posts_by_category[$cat][] = $post;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .forum-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        .forum-sidebar {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            height: fit-content;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .forum-main {
            min-width: 0;
        }
        .category-filter {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .category-filter li {
            margin-bottom: 0.5rem;
        }
        .category-filter a {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .category-filter a:hover,
        .category-filter a.active {
            background: #f3f4f6;
            color: #6b7280;
        }
        .category-filter .count {
            background: #e5e7eb;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.85rem;
        }
        .forum-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .forum-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .search-box {
            display: flex;
            gap: 0.5rem;
        }
        .search-box input {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
        }
        .sort-select {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .post-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }
        .post-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }
        .post-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .post-author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .author-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .category-badge {
            background: #6b7280;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .post-content {
            color: #4b5563;
            line-height: 1.6;
            margin: 0;
        }
        .post-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }
        .post-action {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }
        .post-action:hover {
            color: #4b5563;
        }
        .reply-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }
        .reply-form {
            margin-top: 1rem;
            display: none;
        }
        .reply-form.active {
            display: block;
        }
        .reply-form textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
            margin-bottom: 0.5rem;
        }
        .reply-item {
            padding: 1rem;
            margin-top: 1rem;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 3px solid #6b7280;
        }
        .reply-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .reply-content {
            color: #4b5563;
            line-height: 1.6;
        }
        .replies-count {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .new-post-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #6b7280;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        @media (max-width: 768px) {
            .forum-container {
                grid-template-columns: 1fr;
            }
            .forum-sidebar {
                order: 2;
            }
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="container" style="padding: 2rem 0;">
    <h1>Discussion Forum</h1>
    <p>Share your thoughts, ask questions, and connect with our community.</p>
    
    <?php if ($success_msg): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo esc_html($success_msg); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($error_msg); ?>
        </div>
    <?php endif; ?>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?php echo count($posts); ?></div>
            <div class="stat-label">Total Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo count($posts_by_category); ?></div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo count(array_unique(array_column($posts, 'user_id'))); ?></div>
            <div class="stat-label">Active Members</div>
        </div>
    </div>
    
    <div class="forum-container">
        <!-- Sidebar -->
        <div class="forum-sidebar">
            <h3 style="margin-top: 0; margin-bottom: 1rem;">Categories</h3>
            <ul class="category-filter">
                <li>
                    <a href="?category=all&sort=<?php echo $sort_by; ?>&search=<?php echo urlencode($search_query); ?>" 
                       class="<?php echo $category_filter === 'all' ? 'active' : ''; ?>">
                        <span>All Categories</span>
                        <span class="count"><?php echo count($posts); ?></span>
                    </a>
                </li>
                <?php
                $categories = ['general' => 'General Discussion', 'products' => 'Products', 'support' => 'Support', 'feedback' => 'Feedback', 'ideas' => 'Ideas & Suggestions'];
                foreach ($categories as $cat_key => $cat_name):
                    $count = $category_counts[$cat_key] ?? 0;
                ?>
                <li>
                    <a href="?category=<?php echo $cat_key; ?>&sort=<?php echo $sort_by; ?>&search=<?php echo urlencode($search_query); ?>" 
                       class="<?php echo $category_filter === $cat_key ? 'active' : ''; ?>">
                        <span><?php echo esc_html($cat_name); ?></span>
                        <span class="count"><?php echo $count; ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="forum-main">
            <!-- New Post Form -->
            <div class="new-post-form">
                <h2 style="margin-top: 0;">Create New Post</h2>
                <form method="POST" class="form">
                    <div class="form-group">
                        <label for="title">Post Title *</label>
                        <input type="text" id="title" name="title" required value="<?php echo esc_attr($title ?? ''); ?>" placeholder="Enter your post title">
                    </div>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" style="width: 100%; padding: 0.75rem;">
                                <option value="general" <?php echo ($category ?? 'general') == 'general' ? 'selected' : ''; ?>>General Discussion</option>
                                <option value="products" <?php echo ($category ?? '') == 'products' ? 'selected' : ''; ?>>Products</option>
                                <option value="support" <?php echo ($category ?? '') == 'support' ? 'selected' : ''; ?>>Support</option>
                                <option value="feedback" <?php echo ($category ?? '') == 'feedback' ? 'selected' : ''; ?>>Feedback</option>
                                <option value="ideas" <?php echo ($category ?? '') == 'ideas' ? 'selected' : ''; ?>>Ideas & Suggestions</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: flex; align-items: flex-end;">
                            <button type="submit" name="submit_post" class="btn btn-primary" style="width: 100%;">Submit Post</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Post Content *</label>
                        <textarea id="content" name="content" rows="6" required placeholder="Share your thoughts..."><?php echo esc_textarea($content ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
            
            <!-- Forum Header with Controls -->
            <div class="forum-header">
                <h2 style="margin: 0;"><?php echo $category_filter === 'all' ? 'All Posts' : ucfirst($category_filter) . ' Posts'; ?></h2>
                <div class="forum-controls">
                    <form method="GET" class="search-box">
                        <input type="hidden" name="category" value="<?php echo esc_attr($category_filter); ?>">
                        <input type="hidden" name="sort" value="<?php echo esc_attr($sort_by); ?>">
                        <input type="text" name="search" placeholder="Search posts..." value="<?php echo esc_attr($search_query); ?>">
                        <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i></button>
                    </form>
                    <select class="sort-select" onchange="window.location.href='?category=<?php echo urlencode($category_filter); ?>&sort='+this.value+'&search=<?php echo urlencode($search_query); ?>'">
                        <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo $sort_by === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="title" <?php echo $sort_by === 'title' ? 'selected' : ''; ?>>Sort by Title</option>
                        <option value="category" <?php echo $sort_by === 'category' ? 'selected' : ''; ?>>Sort by Category</option>
                    </select>
                </div>
            </div>
            
            <!-- Posts List -->
            <?php if (empty($posts)): ?>
                <div class="post-card" style="text-align: center; padding: 3rem;">
                    <i class="fas fa-comments" style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem;"></i>
                    <h3>No posts found</h3>
                    <p>Be the first to share your thoughts!</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): 
                    $author_initials = strtoupper(substr($post['first_name'] ?? 'U', 0, 1) . substr($post['last_name'] ?? '', 0, 1));
                    $author_name = trim(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? 'User'));
                ?>
                    <div class="post-card">
                        <div class="post-header">
                            <div style="flex: 1;">
                                <h3 class="post-title"><?php echo esc_html($post['title']); ?></h3>
                                <div class="post-meta">
                                    <div class="post-author">
                                        <div class="author-avatar"><?php echo esc_html($author_initials); ?></div>
                                        <span><?php echo esc_html($author_name); ?></span>
                                    </div>
                                    <span>•</span>
                                    <span class="category-badge"><?php echo esc_html(ucfirst($post['category'] ?? 'general')); ?></span>
                                    <span>•</span>
                                    <span><i class="far fa-clock"></i> <?php echo date('M d, Y g:i A', strtotime($post['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <p class="post-content"><?php echo nl2br(esc_html($post['content'])); ?></p>
                        <div class="post-actions">
                            <?php 
                            $current_post_id = $post['post_id'] ?? $post['id'] ?? 0;
                            ?>
                            <button type="button" class="post-action toggle-reply" data-post-id="<?php echo $current_post_id; ?>">
                                <i class="far fa-comment"></i> Reply 
                                <?php if (isset($post['reply_count']) && $post['reply_count'] > 0): ?>
                                    <span class="replies-count">(<?php echo $post['reply_count']; ?>)</span>
                                <?php endif; ?>
                            </button>
                        </div>
                        
                        <!-- Replies Section -->
                        <div class="reply-section" id="replies-<?php echo $current_post_id; ?>" style="display: none;">
                            <?php 
                            $post_replies = $replies_by_post[$current_post_id] ?? [];
                            if (!empty($post_replies)): 
                            ?>
                                <h4 style="margin-bottom: 1rem; color: #4b5563; font-size: 1rem;">Replies (<?php echo count($post_replies); ?>)</h4>
                                <?php foreach ($post_replies as $reply): 
                                    $reply_author_initials = strtoupper(substr($reply['first_name'] ?? 'U', 0, 1) . substr($reply['last_name'] ?? '', 0, 1));
                                    $reply_author_name = trim(($reply['first_name'] ?? '') . ' ' . ($reply['last_name'] ?? 'User'));
                                ?>
                                    <div class="reply-item">
                                        <div class="reply-header">
                                            <div class="author-avatar" style="width: 24px; height: 24px; font-size: 0.75rem;"><?php echo esc_html($reply_author_initials); ?></div>
                                            <span><strong><?php echo esc_html($reply_author_name); ?></span></strong>
                                            <span>•</span>
                                            <span><i class="far fa-clock"></i> <?php echo date('M d, Y g:i A', strtotime($reply['created_at'])); ?></span>
                                        </div>
                                        <div class="reply-content"><?php echo nl2br(esc_html($reply['content'])); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            
                            <!-- Reply Form -->
                            <form method="POST" class="reply-form" id="reply-form-<?php echo $current_post_id; ?>">
                                <input type="hidden" name="post_id" value="<?php echo $current_post_id; ?>">
                                <textarea name="reply_content" placeholder="Write your reply..." required></textarea>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="submit" name="submit_reply" class="btn btn-primary" style="padding: 0.5rem 1rem;">Post Reply</button>
                                    <button type="button" class="btn btn-outline cancel-reply" data-post-id="<?php echo $current_post_id; ?>" style="padding: 0.5rem 1rem;">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle reply form
    document.querySelectorAll('.toggle-reply').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const replySection = document.getElementById('replies-' + postId);
            const replyForm = document.getElementById('reply-form-' + postId);
            
            if (replySection.style.display === 'none') {
                replySection.style.display = 'block';
                replyForm.classList.add('active');
                replyForm.querySelector('textarea').focus();
            } else {
                replySection.style.display = 'none';
                replyForm.classList.remove('active');
            }
        });
    });
    
    // Cancel reply
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const replySection = document.getElementById('replies-' + postId);
            const replyForm = document.getElementById('reply-form-' + postId);
            
            replySection.style.display = 'none';
            replyForm.classList.remove('active');
            replyForm.querySelector('textarea').value = '';
        });
    });
});
</script>
</body>
</html>
