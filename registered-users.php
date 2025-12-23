<?php
$page_title = "Registered Users - Dawn's ArtisanCraft";
$page_description = "View all registered users in the system";
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
    <h1>Registered Users</h1>
    
    <?php
    // Function to fix encoding
    function fixEncoding($str) {
        if (empty($str)) return $str;
        
        // Try different source encodings
        $encodings = ['GBK', 'GB2312', 'ISO-8859-1', 'Windows-1252', 'UTF-8'];
        
        foreach ($encodings as $enc) {
            $converted = @mb_convert_encoding($str, 'UTF-8', $enc);
            if ($converted && mb_check_encoding($converted, 'UTF-8')) {
                if (mb_strlen($converted) > 0) {
                    return $converted;
                }
            }
        }
        
        // If all fails, try iconv
        $converted = @iconv('GBK', 'UTF-8//IGNORE', $str);
        if ($converted !== false) {
            return $converted;
        }
        
        return $str;
    }
    
    try {
        // Use wp802 database connection (for user registration data)
        // The table wpah_fc_subscribers is the FluentCRM subscribers table
        // In this table: phone field stores the password
        $users = [];
        $table_found = false;
        $table_name = '';
        
        // Try wpah_fc_subscribers first (FluentCRM table - most likely)
        try {
            // Check if table exists
            $check_stmt = $wp885_pdo->query("SHOW TABLES LIKE 'wpah_fc_subscribers'");
            if ($check_stmt->rowCount() > 0) {
                $stmt = $wp885_pdo->query("SELECT first_name, last_name, email, phone FROM wpah_fc_subscribers ORDER BY created_at DESC");
                $users = $stmt->fetchAll();
                $table_found = true;
                $table_name = 'wpah_fc_subscribers';
            }
        } catch (PDOException $e) {
            // Table doesn't exist or query failed
        }
        
        // If wpah_fc_subscribers not found, try ac_users
        if (!$table_found) {
            try {
                $check_stmt = $wp885_pdo->query("SHOW TABLES LIKE 'ac_users'");
                if ($check_stmt->rowCount() > 0) {
                    $stmt = $wp885_pdo->query("SELECT first_name, last_name, email, phone FROM ac_users ORDER BY created_at DESC");
                    $users = $stmt->fetchAll();
                    $table_found = true;
                    $table_name = 'ac_users';
                }
            } catch (PDOException $e2) {
                // Table doesn't exist
            }
        }
        
        // If still not found, try users table
        if (!$table_found) {
            try {
                $check_stmt = $wp885_pdo->query("SHOW TABLES LIKE 'users'");
                if ($check_stmt->rowCount() > 0) {
                    $stmt = $wp885_pdo->query("SELECT first_name, last_name, email, phone FROM users ORDER BY created_at DESC");
                    $users = $stmt->fetchAll();
                    $table_found = true;
                    $table_name = 'users';
                }
            } catch (PDOException $e3) {
                // Table doesn't exist
            }
        }
        
        // If no table found, show error
        if (!$table_found) {
            echo "<div class='alert alert-error'>";
            echo "Error: Could not find user table. Please check if one of these tables exists: wpah_fc_subscribers, ac_users, or users";
            echo "</div>";
        }
        
        if ($table_found && count($users) > 0) {
            // Show which table is being used (for debugging)
            echo "<p style='color:#666; font-size:0.9rem; margin-bottom:1rem;'>";
            echo "<i class='fas fa-database'></i> Data from table: <strong>" . htmlspecialchars($table_name, ENT_QUOTES, 'UTF-8') . "</strong>";
            echo "</p>";
            
            echo "<div style='overflow-x:auto; margin-top:1.5rem;'>";
            echo "<table style='width:100%; border-collapse:collapse; background:white; box-shadow:0 2px 10px rgba(0,0,0,0.1);'>";
            echo "<thead>";
            echo "<tr style='background:linear-gradient(135deg, #0055aa 0%, #003d7a 100%); color:white;'>";
            echo "<th style='padding:15px; text-align:left; font-weight:600;'>Last Name</th>";
            echo "<th style='padding:15px; text-align:left; font-weight:600;'>First Name</th>";
            echo "<th style='padding:15px; text-align:left; font-weight:600;'>Email</th>";
            echo "<th style='padding:15px; text-align:left; font-weight:600;'>Password</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            foreach ($users as $user) {
                // Get raw data
                $last_name = $user['last_name'] ?? '';
                $first_name = $user['first_name'] ?? '';
                $email = $user['email'] ?? '';
                $password = $user['phone'] ?? '';
                
                // Fix encoding
                $last_name = fixEncoding($last_name);
                $first_name = fixEncoding($first_name);
                $email = fixEncoding($email);
                $password = fixEncoding($password);
                
                echo "<tr style='border-bottom:1px solid #e0e0e0; transition:background-color 0.2s;'>";
                echo "<td style='padding:15px;'>" . htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td style='padding:15px;'>" . htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td style='padding:15px;'>" . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td style='padding:15px;'>" . htmlspecialchars($password, ENT_QUOTES, 'UTF-8') . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "<p style='margin-top:1.5rem; color:#666;'>Total: " . count($users) . " users</p>";
        } elseif ($table_found && count($users) == 0) {
            echo "<div class='empty-state' style='text-align:center; padding:3rem; color:#999;'>";
            echo "<i class='fas fa-users-slash' style='font-size:3rem; margin-bottom:1rem; opacity:0.5;'></i>";
            echo "<p>No registered users found in table: <strong>" . htmlspecialchars($table_name, ENT_QUOTES, 'UTF-8') . "</strong></p>";
            echo "</div>";
        }
        
    } catch (PDOException $e) {
        echo "<div class='alert alert-error'>Database Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
    }
    ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
