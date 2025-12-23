<?php
require_once __DIR__ . '/includes/header.php';

$page_title = 'Products & Services';
$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "
    SELECT p.product_id, p.name, p.price, p.short_description,
           COALESCE(pi.image_url, '" . SITE_URL . "/assets/images/placeholder.jpg') AS image_url,
           ap.business_name, c.name AS category_name
    FROM products p
    JOIN artisan_profiles ap ON p.artisan_id = ap.artisan_id
    JOIN categories c ON p.category_id = c.category_id
    LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
    WHERE p.is_active = 1
";
$params = [];

if ($categoryId) {
    $query .= " AND p.category_id = :category_id";
    $params[':category_id'] = $categoryId;
}
if ($search !== '') {
    $query .= " AND (p.name LIKE :q OR p.short_description LIKE :q OR ap.business_name LIKE :q)";
    $params[':q'] = "%$search%";
}

$query .= " ORDER BY p.created_at DESC LIMIT 60";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="container" style="padding:2rem 0;">
    <h1>Products & Services</h1>

    <form class="product-filters" method="GET" style="margin:1rem 0 2rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search products, services, or artisans...">
        <select name="category">
            <option value="">All Categories</option>
            <?php 
            $cats = $pdo->query("SELECT category_id, name FROM categories WHERE is_active=1 ORDER BY name")->fetchAll();
            foreach ($cats as $c):
            ?>
                <option value="<?php echo $c['category_id']; ?>" <?php echo ($categoryId===$c['category_id'])?'selected':''; ?>>
                    <?php echo htmlspecialchars($c['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>No products or services found</h3>
            <p>Try adjusting filters or search query.</p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $p['image_url']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                        <div class="product-overlay">
                            <a href="<?php echo SITE_URL; ?>/products-services-view.php?id=<?php echo $p['product_id']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-meta">
                            <span class="artisan-name">by <?php echo htmlspecialchars($p['business_name']); ?></span>
                            <span class="category"><?php echo htmlspecialchars($p['category_name']); ?></span>
                        </div>
                        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars(mb_strimwidth($p['short_description'], 0, 100, '...')); ?></p>
                        <div class="product-footer">
                            <span class="price"><?php echo formatPrice($p['price']); ?></span>
                            <a href="<?php echo SITE_URL; ?>/products-services-view.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-outline">Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
