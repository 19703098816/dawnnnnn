<?php
// Generates a single INSERT SQL statement for creating the admin user
// Usage (on server):
// 1) Open in browser: https://your-site/create_admin_insert.php
// 2) or run from CLI: php generate_admin_insert.php
// It uses PHP's password_hash() to produce a bcrypt-compatible hash.

require_once __DIR__ . '/config/database.php';

$email = '3442835688@qq.com';
$firstName = 'Junyi';
$lastName = 'Hu';
$rawPassword = 'hjy20041206'; // temporary password — change after first login

try {
    $hash = password_hash($rawPassword, PASSWORD_DEFAULT);

    // Use PDO->quote to safely escape values for SQL output
    $emailQ = $pdo->quote($email);
    $hashQ = $pdo->quote($hash);
    $firstQ = $pdo->quote($firstName);
    $lastQ = $pdo->quote($lastName);

    $sql = "INSERT INTO users (email, password, first_name, last_name, role, status, email_verified, created_at)\n" .
           "SELECT $emailQ, $hashQ, $firstQ, $lastQ, 'admin', 'active', 1, NOW()\n" .
           "FROM DUAL\n" .
           "WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = $emailQ);";

    header('Content-Type: text/plain; charset=utf-8');
    echo "-- Generated INSERT SQL for admin (run this in phpMyAdmin or MySQL client)\n";
    echo $sql . "\n";
    echo "\n-- Note: temporary password is: $rawPassword\n";
    echo "-- After executing, ask user to reset password via admin UI or run password change.\n";

} catch (Exception $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Error generating SQL: " . $e->getMessage();
}

