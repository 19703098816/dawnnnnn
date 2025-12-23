<?php
// Script to create admin user with the correct details
require_once 'config/database.php';

// Idempotent admin creation script using unified $pdo
$adminEmail = '3442835688@qq.com';
$firstName = 'Junyi';
$lastName = 'Hu';
$rawPassword = 'hjy20041206'; // temporary password, please change after first login

try {
    // Check if admin user already exists by email
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt->execute([':email' => $adminEmail]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Admin user already exists.\n";
        $adminUserId = $existing_user['user_id'];
    } else {
        // Create admin user
        $stmt = $pdo->prepare(
            "INSERT INTO users (email, password, first_name, last_name, role, status, email_verified, created_at)
             VALUES (:email, :password, :first_name, :last_name, 'admin', 'active', 1, NOW())"
        );
        
        $password_hash = password_hash($rawPassword, PASSWORD_DEFAULT);
        
        $stmt->execute([
            ':email' => $adminEmail,
            ':password' => $password_hash,
            ':first_name' => $firstName,
            ':last_name' => $lastName
        ]);
        
        $adminUserId = $pdo->lastInsertId();
        echo "Admin user created successfully: {$firstName} {$lastName} ({$adminEmail})\n";
    }

    // Output admin user id for convenience
    if (isset($adminUserId)) {
        echo "Admin user ID: " . $adminUserId . "\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Optional: seed sample artisan/product data when ?seed=1 is provided
if (isset($_GET['seed']) && $_GET['seed'] == '1') {
    echo "\nSeeding sample artisan and product data...\n";
    try {
        // 1) category
        $pdo->prepare("INSERT INTO categories (name, slug, created_at)
            SELECT :name, :slug, NOW() FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = :slug_check)")
            ->execute([':name' => 'Clay Pottery', ':slug' => 'clay-pottery', ':slug_check' => 'clay-pottery']);

        // 2) sample artisan user
        $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, role, status, email_verified, created_at)
            SELECT :email, :pwd, :first_name, :last_name, 'artisan', 'active', 1, NOW() FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = :email_check)")
            ->execute([':email' => 'artisan@example.com', ':pwd' => password_hash('temp1234', PASSWORD_DEFAULT), ':first_name' => 'Ming', ':last_name' => 'Li', ':email_check' => 'artisan@example.com']);

        // 3) artisan_profile
        $pdo->prepare("INSERT INTO artisan_profiles (user_id, business_name, description, is_verified, commission_rate, created_at)
            SELECT u.user_id, :business_name, :description, 1, 15.00, NOW()
            FROM users u
            WHERE u.email = :artisan_email
            AND NOT EXISTS (SELECT 1 FROM artisan_profiles ap WHERE ap.user_id = u.user_id)")
            ->execute([':business_name' => 'Li Ming Pottery', ':description' => 'Small-batch handmade pottery from recycled clays.', ':artisan_email' => 'artisan@example.com']);

        // 4) product
        $pdo->prepare("INSERT INTO products (artisan_id, category_id, name, slug, description, price, stock_quantity, low_stock_threshold, is_active, created_at)
            SELECT ap.artisan_id, (SELECT category_id FROM categories WHERE slug = 'clay-pottery'), :name, :slug, :desc, :price, :stock, :low, 1, NOW()
            FROM artisan_profiles ap
            WHERE ap.user_id = (SELECT user_id FROM users WHERE email = :artisan_email2)
            AND NOT EXISTS (SELECT 1 FROM products p WHERE p.slug = :slug_check)")
            ->execute([':name' => 'Handmade Terracotta Vase', ':slug' => 'handmade-terracotta-vase', ':slug_check' => 'handmade-terracotta-vase', ':desc' => 'Beautiful handmade terracotta vase, approx 20cm height. Sustainable materials.', ':price' => 49.99, ':stock' => 25, ':low' => 5, ':artisan_email2' => 'artisan@example.com']);

        // 5) product image
        $pdo->prepare("INSERT INTO product_images (product_id, image_url, is_primary, created_at)
            SELECT p.product_id, :img, 1, NOW()
            FROM products p
            WHERE p.slug = :slug
            AND NOT EXISTS (SELECT 1 FROM product_images pi WHERE pi.product_id = p.product_id AND pi.is_primary = 1)")
            ->execute([':img' => 'image/terracotta-vase.jpg', ':slug' => 'handmade-terracotta-vase']);

        echo "Seeding completed.\n";
    } catch (PDOException $e) {
        echo "Seeding error: " . $e->getMessage() . "\n";
    }
}