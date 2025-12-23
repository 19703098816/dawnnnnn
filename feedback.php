<?php
$page_title = "Feedback - Dawn's ArtisanCraft";
$page_description = "Share your feedback with us";
require_once 'config/database.php';
require_once 'includes/functions.php';

// Process form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validate input
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Handle file upload
        $file_path = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/uploads/feedback/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['file']['name']);
            $file_path_full = $upload_dir . $file_name;
            
            // Validate file type and size
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (in_array($_FILES['file']['type'], $allowed_types) && $_FILES['file']['size'] <= $max_size) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path_full)) {
                    $file_path = 'uploads/feedback/' . $file_name;
                } else {
                    $error_message = 'Error uploading file. Please try again.';
                }
            } else {
                $error_message = 'Invalid file type or file too large (max 5MB).';
            }
        }
        
        if (empty($error_message)) {
            // Save to database (using 513week7 database via $pdo)
            // Ensure table exists first with correct structure
            try {
                // Check if table exists and has file_path column
                $table_check = $pdo->query("SHOW COLUMNS FROM feedback LIKE 'file_path'")->fetch();
                
                // Create or alter table
                $pdo->exec("CREATE TABLE IF NOT EXISTS feedback (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    message TEXT NOT NULL,
                    file_path VARCHAR(500) NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_email (email),
                    INDEX idx_created_at (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
                
                // If table exists but file_path column doesn't, add it
                if (!$table_check) {
                    try {
                        $pdo->exec("ALTER TABLE feedback ADD COLUMN file_path VARCHAR(500) NULL AFTER message");
                    } catch (PDOException $e) {
                        // Column might already exist or other error
                        error_log("Feedback table alter error: " . $e->getMessage());
                    }
                }
            } catch (PDOException $e) {
                error_log("Feedback table creation error: " . $e->getMessage());
            }
            
            // Now try to insert
            try {
                // Verify database connection
                if (!isset($pdo)) {
                    throw new Exception("Database connection not available");
                }
                
                // Check if file_path column exists before inserting
                $columns = $pdo->query("SHOW COLUMNS FROM feedback")->fetchAll(PDO::FETCH_COLUMN);
                $has_file_path = in_array('file_path', $columns);
                
                if ($has_file_path) {
                    // Prepare and execute insert with file_path
                    $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message, file_path, created_at) VALUES (?, ?, ?, ?, NOW())");
                    $result = $stmt->execute([$name, $email, $message, $file_path]);
                } else {
                    // Insert without file_path column
                    $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
                    $result = $stmt->execute([$name, $email, $message]);
                }
                
                if ($result) {
                    $inserted_id = $pdo->lastInsertId();
                    error_log("Feedback saved successfully - ID: " . $inserted_id);
                    $success_message = 'Thank you for your feedback! We appreciate your input.';
                    // Clear form
                    $name = $email = $message = '';
                } else {
                    $error_message = 'Failed to save feedback. Please try again.';
                    error_log("Feedback insert failed - no exception but result is false");
                }
            } catch (PDOException $e) {
                $error_message = 'Database error: ' . htmlspecialchars($e->getMessage());
                error_log("Feedback insert error: " . $e->getMessage() . " | SQL State: " . $e->getCode());
                error_log("Feedback data - Name: " . substr($name, 0, 20) . ", Email: " . substr($email, 0, 20) . ", Message length: " . strlen($message));
                error_log("Database connection check - pdo exists: " . (isset($pdo) ? 'yes' : 'no'));
                if (isset($pdo)) {
                    error_log("Database name: " . $pdo->query("SELECT DATABASE()")->fetchColumn());
                }
            } catch (Exception $e) {
                $error_message = 'Error: ' . htmlspecialchars($e->getMessage());
                error_log("Feedback general error: " . $e->getMessage());
            }
        }
    }
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

<div class="container" style="padding:2rem 0;">
    <h1>Feedback</h1>
    <p style="margin-bottom: 2rem; color: #666;">We value your feedback! Please share your thoughts, suggestions, or any issues you've encountered.</p>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #f5c6cb;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" style="max-width: 800px; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Name: <span style="color: #dc2626;">*</span></label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box;">
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Email: <span style="color: #dc2626;">*</span></label>
            <input type="email" id="email" name="email" required 
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                   style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box;">
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="message" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Message: <span style="color: #dc2626;">*</span></label>
            <textarea id="message" name="message" rows="6" required 
                      style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; font-family: inherit; resize: vertical; box-sizing: border-box;"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="file" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">Upload Files (Optional):</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <label for="file" style="display: inline-block; padding: 0.5rem 1.5rem; background: #0055aa; color: white; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 600; white-space: nowrap;">Choose File</label>
                <input type="file" id="file" name="file" 
                       accept="image/*,.pdf,.txt"
                       style="display: none;"
                       onchange="document.getElementById('file-label').textContent = this.files.length > 0 ? this.files[0].name : 'No file selected'">
                <span id="file-label" style="color: #666; font-size: 0.9rem;">No file selected</span>
            </div>
            <small style="display: block; margin-top: 0.5rem; color: #666; font-size: 0.875rem;">Accepted formats: Images (JPEG, PNG, GIF), PDF, Text files. Max size: 5MB</small>
        </div>
        
        <button type="submit" class="btn btn-primary" style="background: #0055aa; color: white; padding: 0.75rem 2rem; font-size: 1rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: background 0.3s;">
            Submit Feedback
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
