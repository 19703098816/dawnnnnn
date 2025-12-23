<?php
$page_title = "Dawn's Products";
$page_description = "Discover unique handcrafted products at Dawn's ArtisanCraft Marketplace.";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
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
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// 保持PHP模式打开，因为文件使用echo输出HTML内容

// Load products from JSON (robust with debug)
$jsonFile = __DIR__ . '/data/products.json';
$productsData = [];
$jsonError = '';
if (file_exists($jsonFile)) {
    $raw = @file_get_contents($jsonFile);
    if ($raw !== false) {
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $productsData = $decoded;
        } else {
            $jsonError = 'JSON decode error: ' . json_last_error_msg();
        }
    } else {
        $jsonError = 'Cannot read data file.';
    }
} else {
    $jsonError = 'Data file not found: data/products.json';
}

// Guaranteed fallback: if no products loaded (missing or invalid JSON), use built-in demo data
if (empty($productsData)) {
    $productsData = [
        [ 'id'=>1,'name'=>'Elegant Bracelet','price'=>59.00,'discount_percentage'=>10,'quantity'=>12,
          'short_description'=>'Handcrafted bracelet for daily elegance.','description'=>'Individually handmade and timeless.',
          'image_url'=>'image/1.jpg','image_link'=>'#','supplier'=>'Artisan A','category'=>'Jewelry',
          'origin_country'=>'Italy','warranty_period'=>'12 months','material_type'=>'Stainless Steel' ],
        [ 'id'=>2,'name'=>'Ceramic Vase','price'=>42.00,'discount_percentage'=>0,'quantity'=>8,
          'short_description'=>'Unique ceramic vase with modern flair.','description'=>'Minimal and versatile for any interior.',
          'image_url'=>'image/2.jpg','image_link'=>'#','supplier'=>'Artisan B','category'=>'Home Decor',
          'origin_country'=>'Japan','warranty_period'=>'6 months','material_type'=>'Ceramic' ],
        [ 'id'=>3,'name'=>'Wool Scarf','price'=>28.00,'discount_percentage'=>5,'quantity'=>15,
          'short_description'=>'Warm hand-woven scarf.','description'=>'Soft wool for cool weather comfort.',
          'image_url'=>'image/3.jpg','image_link'=>'#','supplier'=>'Artisan C','category'=>'Textiles',
          'origin_country'=>'Mongolia','warranty_period'=>'3 months','material_type'=>'Wool' ],
        [ 'id'=>4,'name'=>'Wooden Chair','price'=>120.00,'discount_percentage'=>15,'quantity'=>4,
          'short_description'=>'Solid oak chair, stylish and comfy.','description'=>'Ergonomic backrest for all occasions.',
          'image_url'=>'image/4.jpg','image_link'=>'#','supplier'=>'Artisan D','category'=>'Furniture',
          'origin_country'=>'Canada','warranty_period'=>'24 months','material_type'=>'Oak Wood' ],
        [ 'id'=>5,'name'=>'Abstract Painting','price'=>150.00,'discount_percentage'=>20,'quantity'=>2,
          'short_description'=>'Colorful abstract wall art.','description'=>'Hand painted, bursting with color.',
          'image_url'=>'image/5.jpg','image_link'=>'#','supplier'=>'Artisan E','category'=>'Art',
          'origin_country'=>'France','warranty_period'=>'12 months','material_type'=>'Acrylic on Canvas' ],
        [ 'id'=>6,'name'=>'Organic Tea Set','price'=>38.00,'discount_percentage'=>0,'quantity'=>10,
          'short_description'=>'Handmade tea set from organic clay.','description'=>'Relaxing tea experience in every sip.',
          'image_url'=>'image/6.jpg','image_link'=>'#','supplier'=>'Artisan F','category'=>'Kitchenware',
          'origin_country'=>'China','warranty_period'=>'12 months','material_type'=>'Stoneware' ],
        [ 'id'=>7,'name'=>'Antique Watch','price'=>299.99,'discount_percentage'=>15,'quantity'=>3,
          'short_description'=>'Vintage timepiece with classic design.','description'=>'A timeless antique watch, carefully restored and crafted.','image_url'=>'image/7.jpg',
          'image_link'=>'#','supplier'=>'Artisan G','category'=>'Accessories','origin_country'=>'Switzerland',
          'warranty_period'=>'24 months','material_type'=>'Brass' ],
        [ 'id'=>8,'name'=>'Handmade Basket','price'=>35.00,'discount_percentage'=>0,'quantity'=>18,
          'short_description'=>'Woven basket for storage or decoration.','description'=>'Handwoven from natural materials, perfect for home use.','image_url'=>'image/8.jpg',
          'image_link'=>'#','supplier'=>'Artisan H','category'=>'Home Decor','origin_country'=>'Kenya',
          'warranty_period'=>'6 months','material_type'=>'Wicker' ],
        [ 'id'=>9,'name'=>'Silver Pendant','price'=>85.00,'discount_percentage'=>10,'quantity'=>7,
          'short_description'=>'Handcrafted silver pendant with gemstone.','description'=>'A beautiful silver pendant with a genuine gemstone.','image_url'=>'image/9.jpg',
          'image_link'=>'#','supplier'=>'Artisan I','category'=>'Jewelry','origin_country'=>'India',
          'warranty_period'=>'12 months','material_type'=>'Silver' ],
        [ 'id'=>10,'name'=>'Ceramic Mug','price'=>12.99,'discount_percentage'=>0,'quantity'=>25,
          'short_description'=>'Hand-painted ceramic mug.','description'=>'A unique hand-painted ceramic mug, perfect for your morning coffee.','image_url'=>'image/10.jpg',
          'image_link'=>'#','supplier'=>'Artisan J','category'=>'Kitchenware','origin_country'=>'China',
          'warranty_period'=>'6 months','material_type'=>'Ceramic' ]
    ];
}

// Admin inline CRUD handlers (save/delete) on products page
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $products = $productsData;
    $changed = false;
    // Save (add or edit)
    if (isset($_POST['save_product'])) {
        $idPost = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
        // next id
        $nextId = 1; $maxId = 0; foreach ($products as $pp) { $pid = (int)($pp['id'] ?? 0); if ($pid > $maxId) $maxId = $pid; }
        $nextId = $maxId + 1;
        // upload image if provided
        $imageUrl = trim($_POST['image_url'] ?? '');
        if (isset($_FILES['image_file']) && isset($_FILES['image_file']['tmp_name']) && is_uploaded_file($_FILES['image_file']['tmp_name'])) {
            $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif'];
            $mime = function_exists('mime_content_type') ? @mime_content_type($_FILES['image_file']['tmp_name']) : $_FILES['image_file']['type'];
            if (isset($allowed[$mime])) {
                $ext = $allowed[$mime];
                $uploadDir = __DIR__ . '/image/uploads';
                if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
                $fileName = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . '/' . $fileName)) {
                    $imageUrl = 'image/uploads/' . $fileName;
                }
            }
        }
        $item = [
            'id' => $idPost ?: $nextId,
            'name' => trim($_POST['name'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'discount_percentage' => (float)($_POST['discount_percentage'] ?? 0),
            'quantity' => (int)($_POST['quantity'] ?? 0),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'image_url' => $imageUrl,
            'image_link' => trim($_POST['image_link'] ?? ''),
            'supplier' => trim($_POST['supplier'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'origin_country' => trim($_POST['origin_country'] ?? ''),
            'warranty_period' => trim($_POST['warranty_period'] ?? ''),
            'material_type' => trim($_POST['material_type'] ?? '')
        ];
        if ($idPost) {
            foreach ($products as &$p) { if ((int)$p['id'] === $idPost) { $p = $item; break; } }
        } else {
            $products[] = $item;
        }
        $changed = true;
    }
    // Delete
    if (isset($_POST['delete_id'])) {
        $delId = (int)$_POST['delete_id'];
        $products = array_values(array_filter($products, function($p) use ($delId){ return (int)$p['id'] !== $delId; }));
        $changed = true;
    }
    if ($changed) {
        @file_put_contents($jsonFile, json_encode(array_values($products), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
        header('Location: ' . SITE_URL . '/products.php');
        exit;
    }
}

// Index by id for quick lookup
$productsById = [];
foreach ($productsData as $prod) {
    $productsById[(int)$prod['id']] = $prod;
}

// Product detail page
if ($id && isset($productsById[$id])) {
    $product = $productsById[$id];
    $price = (float)$product['price'];
    $discount = isset($product['discount_percentage']) ? (float)$product['discount_percentage'] : 0.0;
    $finalPrice = $discount > 0 ? round($price * (1 - $discount/100), 2) : $price;
    $qtyMax = max((int)($product['quantity'] ?? 1), 1);
    echo '<div class="container" style="padding:2rem 0;">';
    echo '<h1>Product Details</h1>';
    echo '<div class="product-detail blue-card" style="display:grid; grid-template-columns:1fr 1.2fr; gap:2rem; align-items:center;">';
    echo '<div style="text-align:center;position:relative;">';
    $imgSrc = strpos($product['image_url'], 'http') === 0 ? $product['image_url'] : (SITE_URL . '/' . ltrim($product['image_url'], '/'));
    echo '<img class="product-main-image" src="' . htmlspecialchars($imgSrc) . '" alt="Product image" style="max-width:100%;max-height:300px;">';
    echo '<div class="wm-title" style="top:36px;left:50%;transform:translateX(-50%);position:absolute;">'.htmlspecialchars($product['name']).'</div>';
    echo '</div>';
    echo '<div>';
    echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
    echo '<p style="color:#dde; margin:0.25rem 0 1rem;">By ' . htmlspecialchars($product['supplier']) . ' • ' . htmlspecialchars($product['category']) . '</p>';
    echo '<div class="price" style="font-size:1.75rem; color:#3396ff; margin-bottom:1rem;">';
    if ($discount > 0) {
        echo '<span style="text-decoration:line-through;color:#bbb;margin-right:0.5rem;">$' . number_format($price,2) . '</span>';
        echo '<span>$' . number_format($finalPrice,2) . '</span>';
        echo ' <span style="color:#ffe066;font-size:0.9rem;">(' . (int)$discount . '% off)</span>';
    } else {
        echo '$' . number_format($price,2);
    }
    echo '</div>';
    echo '<p style="margin-bottom:1rem; line-height:1.8; color:#fff; ">' . nl2br(htmlspecialchars($product['description'])) . '</p>';
    echo '<ul style="color:#dde;line-height:1.9;margin:0 0 1rem 1rem;">';
    echo '<li>Origin Country: ' . htmlspecialchars($product['origin_country']) . '</li>';
    echo '<li>Warranty Period: ' . htmlspecialchars($product['warranty_period']) . '</li>';
    echo '<li>Material Type: ' . htmlspecialchars($product['material_type']) . '</li>';
    echo '<li>Image Link: <a href="' . htmlspecialchars($product['image_link']) . '" target="_blank" class="btn btn-sm btn-outline light-btn">Open</a></li>';
    echo '</ul>';
    echo '<form id="addToCartForm" onsubmit="return false;" style="display:flex; gap:0.75rem; align-items:center;">';
    echo '<div style="display:flex; align-items:center; gap:0.5rem;">';
    echo '<button class="quantity-decrease btn btn-outline light-btn" type="button">-</button>';
    echo '<input class="quantity-input" type="number" value="1" min="1" max="' . (int)$qtyMax . '" style="width:70px; text-align:center; background:#fff; color:#333;">';
    echo '<button class="quantity-increase btn btn-outline light-btn" type="button">+</button>';
    echo '</div>';
    echo '<button class="btn btn-primary light-btn add-to-cart" data-product-id="' . (int)$product['id'] . '">Add to Cart</button>';
    echo '</form>';
    echo '<p style="margin-top:1rem; color:#dde;">Stock: ' . (int)$qtyMax . ' available</p>';
    echo '<p><a href="products-services.php#card'.(int)$id.'" class="btn btn-outline light-btn" style="margin-top:1em;">Back to list</a></p>';
    echo '</div></div>';
    echo '</div>';
    ?>
    <script>
    (function(){
        const form = document.querySelector('#addToCartForm');
        if (!form) return;
        const input = form.querySelector('.quantity-input');
        form.querySelector('.quantity-decrease').addEventListener('click', function(){
            const v = Math.max(parseInt(input.value||'1',10)-1, 1); input.value = v;
        });
        form.querySelector('.quantity-increase').addEventListener('click', function(){
            const maxV = parseInt(input.getAttribute('max')||'99',10);
            const v = Math.min(parseInt(input.value||'1',10)+1, maxV); input.value = v;
        });
        form.querySelector('.add-to-cart').addEventListener('click', function() {
            const qty = input.value;
            const productId = this.getAttribute('data-product-id');
            fetch('<?php echo SITE_URL; ?>/ajax/add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, quantity: parseInt(qty, 10) })
            })
            .then(r=>r.json())
            .then(d=>{
                if (d.success) {
                    showNotification('Added to cart', 'success');
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) cartCount.textContent = d.cart_count;
                } else {
                    showNotification(d.message||'Failed to add', 'error');
                }
            })
            .catch(()=>showNotification('Network error', 'error'));
        });
    })();
    </script>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>
<div class="container" style="padding:2rem 0;">
    <h1>Products</h1>
    <?php if (isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
    <div style="margin:0.5rem 0 1rem;">
        <a href="#" class="btn btn-primary" onclick="document.getElementById('admin-add-form').style.display='block';return false;">Add New</a>
    </div>
    <div id="admin-add-form" class="form-container" style="display:none;max-width:900px;">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group"><label>Name</label><input name="name" required></div>
            <div class="form-group"><label>Price</label><input type="number" step="0.01" name="price" required></div>
            <div class="form-group"><label>Discount %</label><input type="number" step="1" min="0" max="90" name="discount_percentage"></div>
            <div class="form-group"><label>Quantity</label><input type="number" step="1" name="quantity" required></div>
            <div class="form-group"><label>Supplier</label><input name="supplier" required></div>
            <div class="form-group"><label>Category</label><input name="category" required></div>
            <div class="form-group"><label>Origin Country</label><input name="origin_country" required></div>
            <div class="form-group"><label>Warranty Period</label><input name="warranty_period"></div>
            <div class="form-group"><label>Material Type</label><input name="material_type"></div>
            <div class="form-group"><label>Image URL (or upload)</label><input name="image_url"></div>
            <div class="form-group"><label>Upload Image</label><input type="file" name="image_file" accept="image/*"></div>
            <div class="form-group"><label>Image Link</label><input name="image_link"></div>
            <div class="form-group"><label>Short Description</label><input name="short_description" required></div>
            <div class="form-group"><label>Description</label><textarea rows="5" name="description" required></textarea></div>
            <button type="submit" name="save_product" class="btn btn-primary">Save</button>
            <a href="#" class="btn btn-outline" onclick="document.getElementById('admin-add-form').style.display='none';return false;" style="margin-left:0.5rem;">Cancel</a>
        </form>
    </div>
    <?php endif; ?>
    <?php
    // Group by category (robust handling)
    $byCategory = [];
    if (!empty($productsData)) {
        foreach ($productsData as $p) {
            if (!is_array($p)) continue; // Skip invalid entries
            $cat = isset($p['category']) && !empty(trim($p['category'])) ? trim($p['category']) : 'Uncategorized';
            if (!isset($byCategory[$cat])) {
                $byCategory[$cat] = [];
            }
            $byCategory[$cat][] = $p;
        }
    }
    // Fallback: if grouping fails, show all products in one category
    if (empty($byCategory) && !empty($productsData)) {
        $byCategory['Products'] = $productsData;
    }
    // Display products grouped by category
    if (!empty($byCategory)):
        foreach ($byCategory as $category => $plist): ?>
        <h2 style="margin-top:1.5rem;color:#ffe066;"><?php echo htmlspecialchars($category); ?></h2>
        <div class="products-grid">
        <?php foreach ($plist as $p): 
            if (!is_array($p)) continue; // Skip invalid entries
            $imgSrc = isset($p['image_url']) && !empty($p['image_url']) 
                ? (strpos($p['image_url'], 'http') === 0 ? $p['image_url'] : (SITE_URL . '/' . ltrim($p['image_url'], '/')))
                : (SITE_URL . '/image/placeholder.jpg');
            $price = isset($p['price']) ? (float)$p['price'] : 0.0;
            $discount = isset($p['discount_percentage']) ? (float)$p['discount_percentage'] : 0.0;
            $finalPrice = $discount > 0 ? round($price * (1 - $discount/100), 2) : $price;
        ?>
            <div class="product-card blue-card" id="card<?php echo (int)$p['id']; ?>">
                <div class="product-icon product-image" style="position:relative;">
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Product image">
                    <div class="wm-title" style="top:20px;left:50%;transform:translateX(-50%);position:absolute;"> <?php echo htmlspecialchars($p['name']); ?> </div>
                </div>
                <div class="product-info">
                    <div class="product-meta">
                        <span class="artisan-name">by <?php echo htmlspecialchars($p['supplier']); ?></span>
                        <span class="category"><?php echo htmlspecialchars($p['category']); ?></span>
                    </div>
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($p['short_description']); ?></p>
                    <div style="color:#dde;font-size:0.9rem;margin-bottom:0.5rem;">
                        <span>Origin: <?php echo htmlspecialchars($p['origin_country']); ?></span> •
                        <span>Material: <?php echo htmlspecialchars($p['material_type']); ?></span>
                    </div>
                    <div class="product-footer">
                        <span class="price">
                            <?php if ($discount>0): ?>
                                <span style="text-decoration:line-through;color:#bbb;margin-right:0.35rem;">$<?php echo number_format($price,2); ?></span>
                                <span>$<?php echo number_format($finalPrice,2); ?></span>
                                <span style="color:#ffe066;font-size:0.85rem;">(<?php echo (int)$discount; ?>% off)</span>
                            <?php else: ?>
                                $<?php echo number_format($price,2); ?>
                            <?php endif; ?>
                        </span>
                        <a href="products-services-view.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-outline light-btn">Details</a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo (int)$p['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Delete product #<?php echo (int)$p['id']; ?>?');">Delete</button>
                        </form>
                        <a href="#" class="btn btn-sm btn-primary" onclick="openEdit<?php echo (int)$p['id']; ?>();return false;">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role']==='admin'): ?>
            <div id="edit-form-<?php echo (int)$p['id']; ?>" class="form-container" style="display:none;max-width:900px;margin:12px auto;">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                    <div class="form-group"><label>Name</label><input name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required></div>
                    <div class="form-group"><label>Price</label><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars((string)$p['price']); ?>" required></div>
                    <div class="form-group"><label>Discount %</label><input type="number" step="1" min="0" max="90" name="discount_percentage" value="<?php echo htmlspecialchars((string)($p['discount_percentage'] ?? 0)); ?>"></div>
                    <div class="form-group"><label>Quantity</label><input type="number" step="1" name="quantity" value="<?php echo htmlspecialchars((string)$p['quantity']); ?>" required></div>
                    <div class="form-group"><label>Supplier</label><input name="supplier" value="<?php echo htmlspecialchars($p['supplier']); ?>" required></div>
                    <div class="form-group"><label>Category</label><input name="category" value="<?php echo htmlspecialchars($p['category']); ?>" required></div>
                    <div class="form-group"><label>Origin Country</label><input name="origin_country" value="<?php echo htmlspecialchars($p['origin_country']); ?>" required></div>
                    <div class="form-group"><label>Warranty Period</label><input name="warranty_period" value="<?php echo htmlspecialchars($p['warranty_period']); ?>"></div>
                    <div class="form-group"><label>Material Type</label><input name="material_type" value="<?php echo htmlspecialchars($p['material_type']); ?>"></div>
                    <div class="form-group"><label>Image URL (or upload)</label><input name="image_url" value="<?php echo htmlspecialchars($p['image_url']); ?>"></div>
                    <div class="form-group"><label>Upload Image</label><input type="file" name="image_file" accept="image/*"></div>
                    <div class="form-group"><label>Image Link</label><input name="image_link" value="<?php echo htmlspecialchars($p['image_link']); ?>"></div>
                    <div class="form-group"><label>Short Description</label><input name="short_description" value="<?php echo htmlspecialchars($p['short_description']); ?>" required></div>
                    <div class="form-group"><label>Description</label><textarea rows="5" name="description" required></textarea></div>
                    <button type="submit" name="save_product" class="btn btn-primary">Save</button>
                    <a href="#" class="btn btn-outline" onclick="document.getElementById('edit-form-<?php echo (int)$p['id']; ?>').style.display='none';return false;" style="margin-left:0.5rem;">Cancel</a>
                </form>
            </div>
            <script>
            function openEdit<?php echo (int)$p['id']; ?>(){
                var el = document.getElementById('edit-form-<?php echo (int)$p['id']; ?>');
                if (el) el.style.display = 'block';
            }
            </script>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endforeach; 
    else: ?>
        <div class="empty-state" style="color:#bbb;margin:1rem 0;">No products to display.</div>
    <?php endif; ?>
</div>
<script>if(location.hash) {var t = document.querySelector(location.hash); if(t) t.scrollIntoView({behavior:'smooth'});}</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
