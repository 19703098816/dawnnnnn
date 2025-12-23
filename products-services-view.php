<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => false,
        'samesite' => 'Lax'
    ]);
    session_start();
}

$page_title = "Product Details - Dawn's ArtisanCraft";
$page_description = "View product details";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// First try to get product from JSON file (products.php uses JSON)
$product = null;
$jsonFile = __DIR__ . '/data/products.json';
if (file_exists($jsonFile)) {
    $raw = @file_get_contents($jsonFile);
    if ($raw !== false) {
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            foreach ($decoded as $p) {
                if (isset($p['id']) && (int)$p['id'] === $id) {
                    $product = $p;
                    $product['product_id'] = $product['id'];
                    $product['business_name'] = $product['supplier'] ?? 'Artisan';
                    $product['category_name'] = $product['category'] ?? 'General';
                    $product['description'] = $product['description'] ?? $product['short_description'] ?? '';
                    $product['stock_quantity'] = $product['quantity'] ?? 10;
                    if (isset($product['image_url']) && strpos($product['image_url'], 'http') !== 0) {
                        $product['image_url'] = SITE_URL . '/' . ltrim($product['image_url'], '/');
                    }
                    break;
                }
            }
        }
    }
}

// If not found in JSON, try database
if (!$product) {
    try {
$stmt = $pdo->prepare("
    SELECT p.*, ap.business_name, c.name AS category_name,
                   COALESCE(pi.image_url, '" . SITE_URL . "/image/placeholder.jpg') AS image_url
    FROM products p
            LEFT JOIN artisan_profiles ap ON p.artisan_id = ap.artisan_id
            LEFT JOIN categories c ON p.category_id = c.category_id
            LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
            WHERE p.product_id = :id AND (p.is_active = 1 OR p.is_active IS NULL)
        ");
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch();
    } catch (PDOException $e) {
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, ap.business_name, ap.artisan_name,
                       COALESCE(pi.image_url, '" . SITE_URL . "/image/placeholder.jpg') AS image_url,
                       c.name AS category_name
                FROM ac_products p
                LEFT JOIN ac_artisan_profiles ap ON p.artisan_id = ap.artisan_id
                LEFT JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
                WHERE p.product_id = :id AND (p.status = 'active' OR p.status IS NULL)
");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch();
            if ($product && !isset($product['business_name']) && isset($product['artisan_name'])) {
                $product['business_name'] = $product['artisan_name'];
            }
        } catch (PDOException $e2) {
            // Product not found
        }
    }
}

if (!$product) {
    $page_title = "Product Not Found - Dawn's ArtisanCraft";
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
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        .product-detail {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
        }
        @media (max-width: 768px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
        .product-image-container {
            position: relative;
            background: #f5f5f5;
            border-radius: 12px;
            overflow: hidden;
        }
        .product-main-image {
            width: 100%;
            height: auto;
            min-height: 400px;
            object-fit: cover;
            display: block;
        }
        .product-gallery {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        .product-gallery img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid #e0e0e0;
            transition: border-color 0.3s;
        }
        .product-gallery img:hover {
            border-color: #3b82f6;
        }
        .product-gallery img.active {
            border-color: #3b82f6;
            border-width: 3px;
        }
        .product-info h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #1e3a8a;
        }
        .product-meta {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .product-price {
            font-size: 2rem;
            font-weight: bold;
            color: #ffe066;
            margin-bottom: 1.5rem;
        }
        .product-price .original-price {
            text-decoration: line-through;
            color: #bbb;
            font-size: 1.3rem;
            margin-right: 0.5rem;
        }
        .product-price .discount-badge {
            color: #ffe066;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }
        .product-description {
            line-height: 1.8;
            color: #4b5563;
            margin-bottom: 2rem;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .quantity-controls button {
            width: 40px;
            height: 40px;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .quantity-controls button:hover {
            background: #f3f4f6;
            border-color: #3b82f6;
        }
        .quantity-controls button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .quantity-input {
            width: 80px;
            text-align: center;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 1rem;
        }
        .add-to-cart-btn {
            flex: 1;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
        }
        .add-to-cart-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .add-to-cart-btn.loading {
            position: relative;
            color: transparent;
        }
        .add-to-cart-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .stock-info {
            margin-top: 1rem;
            padding: 0.75rem;
            background: #f0f9ff;
            border-radius: 8px;
            color: #0369a1;
        }
        .stock-info.low-stock {
            background: #fef3c7;
            color: #92400e;
        }
        .stock-info.out-of-stock {
            background: #fee2e2;
            color: #dc2626;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .notification.success {
            background: #10b981;
            color: white;
        }
        .notification.error {
            background: #ef4444;
            color: white;
        }
        .breadcrumb {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .breadcrumb a {
            color: #3b82f6;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php if (!$product): ?>
<div class="container" style="padding:2rem 0;">
    <div class="empty-state" style="text-align:center; padding:3rem;">
        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #fbbf24; margin-bottom: 1rem;"></i>
        <h2>Product Not Found</h2>
        <p style="margin: 1rem 0 2rem; color: #6b7280;">The product you're looking for doesn't exist or has been removed.</p>
        <a href="<?php echo SITE_URL; ?>/products.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>
<?php else: 
    // Get product images
    $gallery = [];
    if (!isset($product['product_id']) && !isset($product['id'])) {
        try {
$images = $pdo->prepare("SELECT image_url, alt_text FROM product_images WHERE product_id = :id ORDER BY is_primary DESC, sort_order ASC");
$images->execute([':id' => $id]);
$gallery = $images->fetchAll();
        } catch (PDOException $e) {
            // No images table
        }
    }
    
    // Fix image URL
    if (isset($product['image_url'])) {
        $imgUrl = $product['image_url'];
        if (strpos($imgUrl, 'http') !== 0) {
            $product['image_url'] = SITE_URL . '/' . ltrim($imgUrl, '/');
        }
    } else {
        $product['image_url'] = SITE_URL . '/image/placeholder.jpg';
    }
    
    // Setup gallery
    if (empty($gallery)) {
        $gallery = [['image_url' => $product['image_url'], 'alt_text' => $product['name']]];
    }
    
    // Fix gallery image URLs
    foreach ($gallery as &$img) {
        if (isset($img['image_url'])) {
            $imgUrl = $img['image_url'];
            if (strpos($imgUrl, 'http') !== 0) {
                $img['image_url'] = SITE_URL . '/' . ltrim($imgUrl, '/');
            }
        }
    }
    unset($img);
    
    // Calculate price - use EXACT same logic as products.php
    $price = isset($product['price']) ? (float)$product['price'] : 0.0;
    $discount = isset($product['discount_percentage']) ? (float)$product['discount_percentage'] : 0.0;
    
    // Validate price (same validation as products.php)
    if ($price > 1000000 || $price < 0) {
        $price = 0.0;
        $discount = 0.0;
    }
    
    // Calculate final price - EXACT same as products.php line 161: round($price * (1 - $discount/100), 2)
    $finalPrice = $discount > 0 ? round($price * (1 - $discount/100), 2) : $price;
    
    $stock_quantity = isset($product['stock_quantity']) ? (int)$product['stock_quantity'] : 10;
    $is_in_stock = $stock_quantity > 0;
    $is_low_stock = $stock_quantity > 0 && $stock_quantity <= 5;
?>

<div class="container" style="padding:2rem 0;">
    <div class="breadcrumb">
        <a href="<?php echo SITE_URL; ?>">Home</a> / 
        <a href="<?php echo SITE_URL; ?>/products.php">Products</a> / 
        <span><?php echo htmlspecialchars($product['name']); ?></span>
    </div>
    
    <div class="product-detail">
        <div>
            <div class="product-image-container">
                <img id="main-product-image" class="product-main-image" 
                     src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     onerror="this.src='<?php echo SITE_URL; ?>/image/placeholder.jpg';">
            </div>
            <?php if (count($gallery) > 1): ?>
            <div class="product-gallery">
                <?php foreach ($gallery as $index => $img): 
                    $imgUrl = $img['image_url'] ?? $product['image_url'] ?? '';
                    if (empty($imgUrl)) continue;
                ?>
                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" 
                         alt="<?php echo htmlspecialchars($img['alt_text'] ?? $product['name'] ?? 'Product image'); ?>" 
                         data-image="<?php echo htmlspecialchars($imgUrl); ?>"
                         class="<?php echo $index === 0 ? 'active' : ''; ?>"
                         onerror="this.style.display='none';">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="product-meta">
                <i class="fas fa-user"></i> By <?php echo htmlspecialchars($product['business_name']); ?> • 
                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category_name']); ?>
            </div>
            
            <div class="product-price">
                <?php if ($price > 0): ?>
                    <?php if ($discount > 0): ?>
                        <span class="original-price">$<?php echo number_format($price, 2); ?></span>
                        <span>$<?php echo number_format($finalPrice, 2); ?></span>
                        <span class="discount-badge">(<?php echo (int)$discount; ?>% off)</span>
                    <?php else: ?>
                        <span>$<?php echo number_format($price, 2); ?></span>
                    <?php endif; ?>
                <?php else: ?>
                    <span>Price not available</span>
                <?php endif; ?>
            </div>
            
            <div class="product-description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            
            <?php if ($is_in_stock): ?>
            <form id="addToCartForm" onsubmit="return false;">
                <div class="quantity-controls">
                    <button type="button" class="qty-decrease" aria-label="Decrease quantity" disabled>
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" 
                           id="quantity-input" 
                           class="quantity-input" 
                           value="1" 
                           min="1" 
                           max="<?php echo $stock_quantity; ?>" 
                           aria-label="Product quantity">
                    <button type="button" class="qty-increase" aria-label="Increase quantity">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button type="button" 
                        class="btn btn-primary add-to-cart-btn" 
                        data-product-id="<?php echo $product['product_id']; ?>"
                        <?php echo !$user_id ? 'disabled title="Please login to add items to cart"' : ''; ?>>
                    <i class="fas fa-shopping-cart"></i> 
                    <?php echo $user_id ? 'Add to Cart' : 'Login to Add to Cart'; ?>
                </button>
            </form>
            <?php else: ?>
            <div style="padding: 1rem; background: #fee2e2; border-radius: 8px; color: #dc2626; text-align: center;">
                <i class="fas fa-times-circle"></i> This product is currently out of stock.
            </div>
            <?php endif; ?>
            
            <div class="stock-info <?php echo !$is_in_stock ? 'out-of-stock' : ($is_low_stock ? 'low-stock' : ''); ?>">
                <i class="fas fa-<?php echo $is_in_stock ? ($is_low_stock ? 'exclamation-triangle' : 'check-circle') : 'times-circle'; ?>"></i>
                <strong>Stock:</strong> 
                <?php if ($is_in_stock): ?>
                    <?php echo $stock_quantity; ?> available
                    <?php if ($is_low_stock): ?>
                        <span style="margin-left: 0.5rem;">(Low stock - order soon!)</span>
                    <?php endif; ?>
                <?php else: ?>
                    Out of stock
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div id="notification-container"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const qtyInput = document.getElementById('quantity-input');
    const qtyDecrease = document.querySelector('.qty-decrease');
    const qtyIncrease = document.querySelector('.qty-increase');
    const mainImage = document.getElementById('main-product-image');
    const galleryImages = document.querySelectorAll('.product-gallery img');
    
    // Gallery image switching
    if (galleryImages.length > 0 && mainImage) {
        galleryImages.forEach(img => {
            img.addEventListener('click', function() {
                mainImage.src = this.dataset.image;
                galleryImages.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // Quantity controls
    function updateQuantityControls() {
        const current = parseInt(qtyInput.value) || 1;
        const max = parseInt(qtyInput.getAttribute('max')) || 999;
        qtyDecrease.disabled = current <= 1;
        qtyIncrease.disabled = current >= max;
    }
    
    qtyInput.addEventListener('input', function() {
        const current = parseInt(this.value) || 1;
        const max = parseInt(this.getAttribute('max')) || 999;
        if (current < 1) this.value = 1;
        if (current > max) this.value = max;
        updateQuantityControls();
    });
    
    qtyDecrease.addEventListener('click', function() {
        const current = parseInt(qtyInput.value) || 1;
        if (current > 1) {
            qtyInput.value = current - 1;
            updateQuantityControls();
        }
    });
    
    qtyIncrease.addEventListener('click', function() {
        const current = parseInt(qtyInput.value) || 1;
        const max = parseInt(qtyInput.getAttribute('max')) || 999;
        if (current < max) {
            qtyInput.value = current + 1;
            updateQuantityControls();
        }
    });
    
    updateQuantityControls();
    
    // Add to cart
    if (addToCartBtn && !addToCartBtn.disabled) {
        addToCartBtn.addEventListener('click', function() {
    const productId = this.getAttribute('data-product-id');
            const quantity = parseInt(qtyInput.value) || 1;
            
            // Disable button and show loading
            this.disabled = true;
            this.classList.add('loading');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            
    fetch('<?php echo SITE_URL; ?>/ajax/add-to-cart.php', {
        method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include',
                body: JSON.stringify({ product_id: productId, quantity: quantity })
            })
            .then(r => {
                if (!r.ok) {
                    throw new Error('Network response was not ok');
                }
                return r.json();
            })
            .then(data => {
                // Re-enable button
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('loading');
                addToCartBtn.innerHTML = originalText;
                
                if (data.success) {
                    showNotification('Product added to cart successfully!', 'success');
                    // Update cart count in header
            const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count || 0;
                        cartCount.style.display = 'inline';
                    }
        } else {
                    let errorMsg = data.message || 'Failed to add product to cart';
                    if (errorMsg.includes('login')) {
                        errorMsg = 'Please login to add items to cart. <a href="<?php echo SITE_URL; ?>/auth/login.php?redirect_to=' + encodeURIComponent(window.location.href) + '" style="color: white; text-decoration: underline;">Login now</a>';
                    }
                    showNotification(errorMsg, 'error');
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('loading');
                addToCartBtn.innerHTML = originalText;
                showNotification('Network error. Please try again.', 'error');
            });
        });
    }
    
    // Notification function
    function showNotification(message, type) {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = message;
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideIn 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
