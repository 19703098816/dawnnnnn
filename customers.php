<?php
$page_title = "Customer List - Dawn's ArtisanCraft";
$page_description = "View all registered customers";
require_once 'config/database.php';
require_once 'includes/functions.php';

// Require admin access
redirectIfNotAdmin();

// Get all customers from FluentCRM subscribers table
try {
    // Try to get from FluentCRM table first
    $stmt = $wp885_pdo->prepare("SELECT * FROM wpt0_fc_subscribers WHERE status = 'active' ORDER BY created_at DESC");
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no FluentCRM data, try local users table
    if (empty($customers)) {
        $stmt = $pdo->prepare("SELECT user_id, email, first_name, last_name, phone, role, created_at FROM users WHERE role = 'customer' OR role IS NULL ORDER BY created_at DESC");
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $customers = [];
    $error_msg = 'Error loading customers: ' . $e->getMessage();
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
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="container" style="padding: 2rem 0;">
    <h1>Customer List</h1>
    <p>All registered customers (FluentCRM subscribers)</p>
    
    <?php if (isset($error_msg)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($error_msg); ?>
        </div>
    <?php endif; ?>
    
    <div style="margin: 2rem 0;">
        <p style="color: #dde;">Total Customers: <strong><?php echo count($customers); ?></strong></p>
    </div>
    
    <?php if (empty($customers)): ?>
        <div class="blue-card" style="padding: 2rem; text-align: center;">
            <p>No customers found.</p>
        </div>
    <?php else: ?>
        <div class="table-container" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden;">
                <thead>
                    <tr style="background: #0055aa; color: white;">
                        <th style="padding: 1rem; text-align: left;">ID</th>
                        <th style="padding: 1rem; text-align: left;">Name</th>
                        <th style="padding: 1rem; text-align: left;">Email</th>
                        <th style="padding: 1rem; text-align: left;">Phone</th>
                        <th style="padding: 1rem; text-align: left;">Status</th>
                        <th style="padding: 1rem; text-align: left;">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 1rem;"><?php echo esc_html($customer['id'] ?? $customer['user_id'] ?? 'N/A'); ?></td>
                            <td style="padding: 1rem;">
                                <?php 
                                $name = '';
                                if (isset($customer['first_name']) || isset($customer['last_name'])) {
                                    $name = trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
                                }
                                echo esc_html($name ?: 'N/A'); 
                                ?>
                            </td>
                            <td style="padding: 1rem;"><?php echo esc_html($customer['email'] ?? 'N/A'); ?></td>
                            <td style="padding: 1rem;"><?php echo esc_html($customer['phone'] ?? 'N/A'); ?></td>
                            <td style="padding: 1rem;">
                                <span style="background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">
                                    <?php echo esc_html(ucfirst($customer['status'] ?? 'active')); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <?php 
                                $date = $customer['created_at'] ?? '';
                                echo $date ? date('M d, Y', strtotime($date)) : 'N/A'; 
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>

