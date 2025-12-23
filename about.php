<?php
$page_title = "About Us - Dawn's ArtisanCraft";
$page_description = "Learn about Dawn's ArtisanCraft mission, our story, and our commitment to connecting artisans with art lovers worldwide.";
require_once 'config/database.php';
require_once 'includes/functions.php';
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
<div class="container" style="padding:2rem 0;">
    <h1>About Dawn's ArtisanCraft</h1>
    <p>Dawn's ArtisanCraft Marketplace connects art lovers with talented artisans. We focus on sustainable, handcrafted products and fair opportunities for creators.</p>
    <p>Browse unique items, support local makers, and enjoy a curated shopping experience at Dawn's ArtisanCraft.</p>
    
    <!-- Map Section -->
    <div style="margin-top:3rem;">
        <h2 style="font-size:1.5em; margin-bottom:1rem;">Our Location</h2>
        <div style="width:100%; height:400px; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
            <img src="<?php echo SITE_URL; ?>/image/address.jpg" alt="Dawn ArtisanCraft Location" style="width:100%; height:100%; object-fit:cover;">
        </div>
    </div>

    <!-- FAQ Section -->
    <div style="margin-top:3rem;">
        <h2 style="font-size:1.5em; margin-bottom:1rem;">Frequently Asked Questions</h2>
        <div style="display:grid; gap:1rem;">
            <div style="background:#f9fafb; padding:1.5rem; border-radius:8px; border-left:4px solid #3b82f6;">
                <h3 style="font-size:1.1em; margin-bottom:0.5rem;"><i class="fas fa-question-circle" style="color:#3b82f6;"></i> How do I become an artisan?</h3>
                <p>Visit our <a href="https://dawn1.infinityfreeapp.com/5-2/" target="_blank">registration page</a> and sign up as an artisan. Our team will review your application.</p>
            </div>
            <div style="background:#f9fafb; padding:1.5rem; border-radius:8px; border-left:4px solid #3b82f6;">
                <h3 style="font-size:1.1em; margin-bottom:0.5rem;"><i class="fas fa-question-circle" style="color:#3b82f6;"></i> What's your commission rate?</h3>
                <p>We charge a competitive 15% commission on all sales - lower than most marketplace platforms.</p>
            </div>
            <div style="background:#f9fafb; padding:1.5rem; border-radius:8px; border-left:4px solid #3b82f6;">
                <h3 style="font-size:1.1em; margin-bottom:0.5rem;"><i class="fas fa-question-circle" style="color:#3b82f6;"></i> How long does shipping take?</h3>
                <p>Shipping times vary by artisan and location. Orders include tracking information for your convenience.</p>
            </div>
            <div style="background:#f9fafb; padding:1.5rem; border-radius:8px; border-left:4px solid #3b82f6;">
                <h3 style="font-size:1.1em; margin-bottom:0.5rem;"><i class="fas fa-question-circle" style="color:#3b82f6;"></i> Need additional support?</h3>
                <p>Visit our <a href="https://dawn1.infinityfreeapp.com/152-2/" target="_blank">Support Center</a> for more resources and help.</p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
