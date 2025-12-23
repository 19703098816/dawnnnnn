<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Prevent unauthorized access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Get email from POST
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validate email
if (!validateEmail($email)) {
    $_SESSION['newsletter_error'] = 'Please enter a valid email address.';
    header("Location: " . SITE_URL);
    exit();
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM newsletter_subscriptions WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        if ($existing['status'] === 'active') {
            $_SESSION['newsletter_success'] = 'You are already subscribed to our newsletter!';
        } else {
            // Reactivate subscription
            $stmt = $pdo->prepare("
                UPDATE newsletter_subscriptions 
                SET status = 'active', unsubscribed_at = NULL 
                WHERE email = :email
            ");
            $stmt->execute([':email' => $email]);
            $_SESSION['newsletter_success'] = 'Welcome back! Your subscription has been reactivated.';
        }
    } else {
        // Add new subscription
        $stmt = $pdo->prepare("
            INSERT INTO newsletter_subscriptions (email, status, subscribed_at) 
            VALUES (:email, 'active', NOW())
        ");
        $stmt->execute([':email' => $email]);
        $_SESSION['newsletter_success'] = 'Thank you for subscribing to our newsletter!';
        
        // Send welcome email (optional)
        sendNewsletterWelcomeEmail($email);
    }
    
    header("Location: " . SITE_URL);
    exit();
    
} catch (PDOException $e) {
    error_log("Newsletter subscription error: " . $e->getMessage());
    $_SESSION['newsletter_error'] = 'An error occurred. Please try again later.';
    header("Location: " . SITE_URL);
    exit();
}

function sendNewsletterWelcomeEmail($email) {
    $subject = "Welcome to the " . SITE_NAME . " Newsletter!";
    $message = "
        <h2>Welcome to " . SITE_NAME . "!</h2>
        <p>Thank you for subscribing to our newsletter. You'll now receive updates about:</p>
        <ul>
            <li>New artisan products</li>
            <li>Special offers and discounts</li>
            <li>Featured artisans and their stories</li>
            <li>Tips for sustainable living</li>
        </ul>
        <p>We're excited to share unique, handcrafted treasures with you!</p>
        <p><a href='" . SITE_URL . "/'>Visit our marketplace</a></p>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . SITE_NAME . " <" . ADMIN_EMAIL . ">\r\n";
    
    mail($email, $subject, $message, $headers);
}

