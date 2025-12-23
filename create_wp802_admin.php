<?php
/**
 * Create admin account in wp802 database (wpah_fc_subscribers table)
 * This script creates the admin user in the FluentCRM subscribers table
 */
require_once 'config/database.php';

$email = '3442835688@qq.com';
$first_name = 'hjy';
$last_name = 'hjy';
$phone = 'hjy20041206'; // In wpah_fc_subscribers, phone field stores the password
$status = 'subscribed';
$contact_type = 'lead';
$source = 'FluentForms';

echo "<h2>Creating Admin Account in wp802 Database</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .error { color: #dc3545; background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .info { color: #004085; background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 10px 0; }
</style>";

try {
    // Check if admin already exists
    $stmt = $wp885_pdo->prepare("SELECT id, email, first_name, last_name, phone FROM wpah_fc_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        echo "<div class='info'>";
        echo "<strong>Admin account already exists:</strong><br>";
        echo "ID: " . htmlspecialchars($existing['id']) . "<br>";
        echo "Email: " . htmlspecialchars($existing['email']) . "<br>";
        echo "Name: " . htmlspecialchars($existing['first_name'] . ' ' . $existing['last_name']) . "<br>";
        echo "Phone (Password): " . htmlspecialchars($existing['phone']) . "<br>";
        echo "</div>";
        
        // Update if needed
        $update_needed = false;
        if ($existing['first_name'] !== $first_name || $existing['last_name'] !== $last_name || $existing['phone'] !== $phone) {
            $update_needed = true;
        }
        
        if ($update_needed) {
            $stmt = $wp885_pdo->prepare("
                UPDATE wpah_fc_subscribers 
                SET first_name = ?, last_name = ?, phone = ?, updated_at = NOW()
                WHERE email = ?
            ");
            $stmt->execute([$first_name, $last_name, $phone, $email]);
            echo "<div class='success'>Admin account updated successfully!</div>";
        } else {
            echo "<div class='info'>Admin account is up to date.</div>";
        }
    } else {
        // Create new admin account
        $stmt = $wp885_pdo->prepare("
            INSERT INTO wpah_fc_subscribers 
            (first_name, last_name, email, phone, status, contact_type, source, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([$first_name, $last_name, $email, $phone, $status, $contact_type, $source]);
        $admin_id = $wp885_pdo->lastInsertId();
        
        echo "<div class='success'>";
        echo "<strong>Admin account created successfully!</strong><br>";
        echo "ID: " . $admin_id . "<br>";
        echo "Email: " . htmlspecialchars($email) . "<br>";
        echo "Name: " . htmlspecialchars($first_name . ' ' . $last_name) . "<br>";
        echo "Phone (Password): " . htmlspecialchars($phone) . "<br>";
        echo "</div>";
    }
    
    echo "<div class='info'>";
    echo "<strong>Login Information:</strong><br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($phone) . "<br>";
    echo "<br>";
    echo "You can now login at: <a href='" . SITE_URL . "/auth/login.php'>" . SITE_URL . "/auth/login.php</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>";
    echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Make sure the wpah_fc_subscribers table exists in the wp802 database.";
    echo "</div>";
}
?>

