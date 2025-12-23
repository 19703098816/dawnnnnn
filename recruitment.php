<?php
$page_title = "Recruitment - Dawn's ArtisanCraft";
$page_description = "Join our team at Dawn's ArtisanCraft Marketplace";
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
    <style>
        /* Override any conflicting styles for recruitment page */
        body.recruitment-page {
            background: #fff !important;
            color: #333 !important;
        }
        .recruitment-container {
            background: #fff !important;
            min-height: 100vh;
            width: 100% !important;
        }
        .job-positions-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
            gap: 2rem !important;
            width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }
        .job-card {
            background: #111 !important;
            color: #fff !important;
            display: flex !important;
            flex-direction: column !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 1 !important;
            width: 100% !important;
            min-height: 500px !important;
            padding: 2rem !important;
            border-radius: 12px !important;
            box-shadow: 0 7px 34px rgba(42, 47, 52, 0.2), 0 1px 0 rgba(17, 17, 17, 0.1) !important;
        }
        .job-card * {
            visibility: visible !important;
            opacity: 1 !important;
        }
        .job-card h3 {
            color: #ffe066 !important;
        }
        .job-card p {
            color: #ddd !important;
        }
        .job-card ul {
            color: #ccc !important;
        }
        .job-positions-grid-container {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 2rem !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        .job-card-item {
            display: flex !important;
            flex-direction: column !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        @media (max-width: 768px) {
            .job-positions-grid-container {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</head>
<body class="recruitment-page">
<?php require_once __DIR__ . '/includes/header.php'; ?>

<?php
$success_msg = '';
$error_msg = '';
$full_name = '';
$email = '';
$phone = '';
$position = '';
$cover_letter = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize_text_field($_POST['full_name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $position = sanitize_text_field($_POST['position'] ?? '');
    $cover_letter = sanitize_textarea_field($_POST['cover_letter'] ?? '');
    $cv_file = $_FILES['cv_file'] ?? null;
    
    // Split full name into first and last name for database
    $name_parts = explode(' ', trim($full_name), 2);
    $first_name = $name_parts[0] ?? '';
    $last_name = $name_parts[1] ?? '';
    
    if (empty($full_name) || empty($email) || empty($phone) || empty($position)) {
        $error_msg = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please enter a valid email address.';
    } elseif (!$cv_file || $cv_file['error'] !== UPLOAD_ERR_OK) {
        $error_msg = 'Please upload your resume.';
    } else {
        // Validate file type
        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
        $allowed_extensions = ['pdf', 'doc', 'docx', 'txt'];
        $file_type = $cv_file['type'];
        $file_ext = strtolower(pathinfo($cv_file['name'], PATHINFO_EXTENSION));
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file_type, $allowed_types) && !in_array($file_ext, $allowed_extensions)) {
            $error_msg = 'Resume must be a PDF, Word document, or TXT file.';
        } elseif ($cv_file['size'] > $max_size) {
            $error_msg = 'CV file size must be less than 5MB.';
        } else {
            try {
                // Create uploads directory if it doesn't exist
                $upload_dir = __DIR__ . '/uploads/cvs/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0775, true);
                }
                
                // Generate unique filename
                $file_ext = pathinfo($cv_file['name'], PATHINFO_EXTENSION);
                $file_name = 'cv_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($cv_file['tmp_name'], $file_path)) {
                    // Save to database (experience field is optional now, use empty string)
                    $stmt = $pdo->prepare("INSERT INTO job_applications (first_name, last_name, email, phone, position, experience, cover_letter, cv_file_path, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$first_name, $last_name, $email, $phone, $position, '', $cover_letter, $file_name]);
                    
                    // Send email notification
                    $to = ADMIN_EMAIL ?? 'admin@example.com';
                    $subject = "New Job Application: " . $position;
                    $message = "A new job application has been received:\n\n";
                    $message .= "Name: $full_name\n";
                    $message .= "Email: $email\n";
                    $message .= "Phone: $phone\n";
                    $message .= "Position: $position\n\n";
                    $message .= "Cover Letter:\n$cover_letter\n\n";
                    $message .= "Resume File: $file_name";
                    $headers = "From: $email\r\nReply-To: $email\r\n";
                    
                    @mail($to, $subject, $message, $headers);
                    
                    $success_msg = 'Thank you for your application! We will review it and get back to you soon.';
                    $full_name = $email = $phone = $position = $cover_letter = '';
                } else {
                    $error_msg = 'Failed to upload CV. Please try again.';
                }
            } catch (Exception $e) {
                $error_msg = 'An error occurred. Please try again later.';
            }
        }
    }
}
?>

<div class="container recruitment-container" style="padding: 2rem 0; background: #fff !important; min-height: calc(100vh - 200px); padding-bottom: 4rem;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem; background: #fff !important;">
        <h1 style="text-align: center; margin-bottom: 1rem; color: #333;">Join Our Team</h1>
        <p style="text-align: center; margin-bottom: 3rem; color: #666; font-size: 1.1rem;">We're always looking for talented individuals to join our growing team. Explore our open positions below.</p>
    
    <?php if ($success_msg): ?>
            <div class="alert alert-success" style="max-width: 800px; margin: 0 auto 2rem;">
            <i class="fas fa-check-circle"></i> <?php echo esc_html($success_msg); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_msg): ?>
            <div class="alert alert-error" style="max-width: 800px; margin: 0 auto 2rem;">
            <i class="fas fa-exclamation-circle"></i> <?php echo esc_html($error_msg); ?>
        </div>
    <?php endif; ?>
    
        <!-- Job Positions Section -->
        <section style="margin: 3rem 0; background: #fff !important; padding: 2rem 0; width: 100%; display: block !important; visibility: visible !important;">
            <h2 style="text-align: center; margin-bottom: 1rem; color: #ffe066 !important; font-size: 2.5rem; font-weight: bold; visibility: visible !important;">OPEN POSITIONS</h2>
            <p style="text-align: center; margin-bottom: 3rem; color: #666; font-size: 1.1rem;">Roles we are hiring for</p>
            <div style="max-width: 1200px; margin: 0 auto; background: #fff !important; padding: 0 1rem;">
            <?php
            // Define 4 positions - ALWAYS show all 4
            $positions = [
                [
                    'title' => 'Web Developer',
                    'icon' => 'fas fa-code',
                    'category' => 'Development',
                    'description' => 'Develop and maintain our e-commerce platform and web applications using modern technologies.',
                    'requirements' => ['3+ years PHP/JavaScript experience', 'Experience with databases (MySQL, PostgreSQL)', 'Strong problem-solving skills', 'Familiarity with version control (Git)'],
                    'location' => 'Remote / On-site',
                    'type' => 'Full-time'
                ],
                [
                    'title' => 'Marketing Specialist',
                    'icon' => 'fas fa-bullhorn',
                    'category' => 'Marketing',
                    'description' => 'Drive brand awareness and customer engagement through digital marketing campaigns and social media strategies.',
                    'requirements' => ['2+ years marketing experience', 'Social media expertise', 'Analytics proficiency (Google Analytics)', 'Content creation skills'],
                    'location' => 'Hybrid',
                    'type' => 'Full-time'
                ],
                [
                    'title' => 'Customer Support',
                    'icon' => 'fas fa-headset',
                    'category' => 'Support',
                    'description' => 'Provide exceptional customer service and support to our community of artisans and buyers.',
                    'requirements' => ['Excellent communication skills', 'Customer service experience', 'Problem-solving ability', 'Multilingual preferred'],
                    'location' => 'Remote',
                    'type' => 'Full-time / Part-time'
                ],
                [
                    'title' => 'Product Manager',
                    'icon' => 'fas fa-tasks',
                    'category' => 'Management',
                    'description' => 'Lead product development and strategy to enhance our marketplace platform and user experience.',
                    'requirements' => ['5+ years product management', 'E-commerce experience', 'Strong leadership skills', 'Data-driven decision making'],
                    'location' => 'On-site',
                    'type' => 'Full-time'
                ]
            ];
            ?>
            <div class="job-positions-grid-container" style="display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 2rem !important; margin-bottom: 4rem !important; width: 100% !important;">
                <!-- Job Card 1: Web Developer -->
                <div class="job-card-item" style="background: #f8f9fa !important; border: 1px solid #e0e0e0 !important; border-radius: 12px !important; padding: 2rem !important; display: flex !important; flex-direction: column !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; transition: transform 0.3s, box-shadow 0.3s !important; width: 100% !important; visibility: visible !important; opacity: 1 !important; min-height: 400px !important;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                    <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; margin-bottom: 1rem !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 1rem !important;">
                            <i class="fas fa-code" style="font-size: 2rem !important; color: #ffe066 !important;"></i>
                            <div>
                                <h3 style="margin: 0 !important; color: #333 !important; font-size: 1.5rem !important; font-weight: bold !important;">Web Developer</h3>
                                <span style="color: #666 !important; font-size: 0.9rem !important;">Development</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex !important; gap: 1rem !important; margin-bottom: 1rem !important; flex-wrap: wrap !important;">
                        <span style="background: #ffe066 !important; color: #181818 !important; padding: 0.25rem 0.75rem !important; border-radius: 20px !important; font-size: 0.85rem !important; font-weight: bold !important;">Full-time</span>
                        <span style="color: #666 !important; font-size: 0.9rem !important; display: flex !important; align-items: center !important; gap: 0.25rem !important;">
                            <i class="fas fa-map-marker-alt" style="color: #ffe066 !important;"></i> Remote / On-site
                        </span>
                    </div>
                    <p style="color: #666 !important; margin-bottom: 1.5rem !important; line-height: 1.6 !important; flex-grow: 1 !important;">Develop and maintain our e-commerce platform and web applications using modern technologies.</p>
                    <div style="margin-bottom: 1.5rem !important;">
                        <strong style="color: #333 !important; display: block !important; margin-bottom: 0.5rem !important;">Requirements:</strong>
                        <ul style="margin: 0 !important; padding-left: 1.5rem !important; color: #666 !important; line-height: 1.8 !important;">
                            <li style="color: #666 !important;">3+ years PHP/JavaScript experience</li>
                            <li style="color: #666 !important;">Experience with databases (MySQL, PostgreSQL)</li>
                            <li style="color: #666 !important;">Strong problem-solving skills</li>
                            <li style="color: #666 !important;">Familiarity with version control (Git)</li>
                        </ul>
                    </div>
                    <button type="button" 
                            onclick="selectPosition('Web Developer')"
                            style="background: #ffe066 !important; color: #181818 !important; border: none !important; padding: 0.75rem 1.5rem !important; font-weight: bold !important; cursor: pointer !important; border-radius: 6px !important; font-size: 1rem !important; transition: all 0.3s !important; width: 100% !important; margin-top: auto !important;"
                            onmouseover="this.style.background='#ffed4e';"
                            onmouseout="this.style.background='#ffe066';">
                        Apply Now
                    </button>
                </div>
                
                <!-- Job Card 2: Marketing Specialist -->
                <div class="job-card-item" style="background: #f8f9fa !important; border: 1px solid #e0e0e0 !important; border-radius: 12px !important; padding: 2rem !important; display: flex !important; flex-direction: column !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; transition: transform 0.3s, box-shadow 0.3s !important; width: 100% !important; visibility: visible !important; opacity: 1 !important; min-height: 400px !important;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                    <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; margin-bottom: 1rem !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 1rem !important;">
                            <i class="fas fa-bullhorn" style="font-size: 2rem !important; color: #ffe066 !important;"></i>
                            <div>
                                <h3 style="margin: 0 !important; color: #333 !important; font-size: 1.5rem !important; font-weight: bold !important;">Marketing Specialist</h3>
                                <span style="color: #666 !important; font-size: 0.9rem !important;">Marketing</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex !important; gap: 1rem !important; margin-bottom: 1rem !important; flex-wrap: wrap !important;">
                        <span style="background: #ffe066 !important; color: #181818 !important; padding: 0.25rem 0.75rem !important; border-radius: 20px !important; font-size: 0.85rem !important; font-weight: bold !important;">Full-time</span>
                        <span style="color: #666 !important; font-size: 0.9rem !important; display: flex !important; align-items: center !important; gap: 0.25rem !important;">
                            <i class="fas fa-map-marker-alt" style="color: #ffe066 !important;"></i> Hybrid
                        </span>
                    </div>
                    <p style="color: #666 !important; margin-bottom: 1.5rem !important; line-height: 1.6 !important; flex-grow: 1 !important;">Drive brand awareness and customer engagement through digital marketing campaigns and social media strategies.</p>
                    <div style="margin-bottom: 1.5rem !important;">
                        <strong style="color: #333 !important; display: block !important; margin-bottom: 0.5rem !important;">Requirements:</strong>
                        <ul style="margin: 0 !important; padding-left: 1.5rem !important; color: #666 !important; line-height: 1.8 !important;">
                            <li style="color: #666 !important;">2+ years marketing experience</li>
                            <li style="color: #666 !important;">Social media expertise</li>
                            <li style="color: #666 !important;">Analytics proficiency (Google Analytics)</li>
                            <li style="color: #666 !important;">Content creation skills</li>
                        </ul>
                    </div>
                    <button type="button" 
                            onclick="selectPosition('Marketing Specialist')"
                            style="background: #ffe066 !important; color: #181818 !important; border: none !important; padding: 0.75rem 1.5rem !important; font-weight: bold !important; cursor: pointer !important; border-radius: 6px !important; font-size: 1rem !important; transition: all 0.3s !important; width: 100% !important; margin-top: auto !important;"
                            onmouseover="this.style.background='#ffed4e';"
                            onmouseout="this.style.background='#ffe066';">
                        Apply Now
                    </button>
                </div>
                
                <!-- Job Card 3: Customer Support -->
                <div class="job-card-item" style="background: #f8f9fa !important; border: 1px solid #e0e0e0 !important; border-radius: 12px !important; padding: 2rem !important; display: flex !important; flex-direction: column !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; transition: transform 0.3s, box-shadow 0.3s !important; width: 100% !important; visibility: visible !important; opacity: 1 !important; min-height: 400px !important;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                    <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; margin-bottom: 1rem !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 1rem !important;">
                            <i class="fas fa-headset" style="font-size: 2rem !important; color: #ffe066 !important;"></i>
                            <div>
                                <h3 style="margin: 0 !important; color: #333 !important; font-size: 1.5rem !important; font-weight: bold !important;">Customer Support</h3>
                                <span style="color: #666 !important; font-size: 0.9rem !important;">Support</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex !important; gap: 1rem !important; margin-bottom: 1rem !important; flex-wrap: wrap !important;">
                        <span style="background: #ffe066 !important; color: #181818 !important; padding: 0.25rem 0.75rem !important; border-radius: 20px !important; font-size: 0.85rem !important; font-weight: bold !important;">Full-time / Part-time</span>
                        <span style="color: #666 !important; font-size: 0.9rem !important; display: flex !important; align-items: center !important; gap: 0.25rem !important;">
                            <i class="fas fa-map-marker-alt" style="color: #ffe066 !important;"></i> Remote
                        </span>
                    </div>
                    <p style="color: #666 !important; margin-bottom: 1.5rem !important; line-height: 1.6 !important; flex-grow: 1 !important;">Provide exceptional customer service and support to our community of artisans and buyers.</p>
                    <div style="margin-bottom: 1.5rem !important;">
                        <strong style="color: #333 !important; display: block !important; margin-bottom: 0.5rem !important;">Requirements:</strong>
                        <ul style="margin: 0 !important; padding-left: 1.5rem !important; color: #666 !important; line-height: 1.8 !important;">
                            <li style="color: #666 !important;">Excellent communication skills</li>
                            <li style="color: #666 !important;">Customer service experience</li>
                            <li style="color: #666 !important;">Problem-solving ability</li>
                            <li style="color: #666 !important;">Multilingual preferred</li>
                        </ul>
                    </div>
                    <button type="button" 
                            onclick="selectPosition('Customer Support')"
                            style="background: #ffe066 !important; color: #181818 !important; border: none !important; padding: 0.75rem 1.5rem !important; font-weight: bold !important; cursor: pointer !important; border-radius: 6px !important; font-size: 1rem !important; transition: all 0.3s !important; width: 100% !important; margin-top: auto !important;"
                            onmouseover="this.style.background='#ffed4e';"
                            onmouseout="this.style.background='#ffe066';">
                        Apply Now
                    </button>
                </div>
                
                <!-- Job Card 4: Product Manager -->
                <div class="job-card-item" style="background: #f8f9fa !important; border: 1px solid #e0e0e0 !important; border-radius: 12px !important; padding: 2rem !important; display: flex !important; flex-direction: column !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; transition: transform 0.3s, box-shadow 0.3s !important; width: 100% !important; visibility: visible !important; opacity: 1 !important; min-height: 400px !important;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                    <div style="display: flex !important; align-items: center !important; justify-content: space-between !important; margin-bottom: 1rem !important;">
                        <div style="display: flex !important; align-items: center !important; gap: 1rem !important;">
                            <i class="fas fa-tasks" style="font-size: 2rem !important; color: #ffe066 !important;"></i>
                            <div>
                                <h3 style="margin: 0 !important; color: #333 !important; font-size: 1.5rem !important; font-weight: bold !important;">Product Manager</h3>
                                <span style="color: #666 !important; font-size: 0.9rem !important;">Management</span>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex !important; gap: 1rem !important; margin-bottom: 1rem !important; flex-wrap: wrap !important;">
                        <span style="background: #ffe066 !important; color: #181818 !important; padding: 0.25rem 0.75rem !important; border-radius: 20px !important; font-size: 0.85rem !important; font-weight: bold !important;">Full-time</span>
                        <span style="color: #666 !important; font-size: 0.9rem !important; display: flex !important; align-items: center !important; gap: 0.25rem !important;">
                            <i class="fas fa-map-marker-alt" style="color: #ffe066 !important;"></i> On-site
                        </span>
                    </div>
                    <p style="color: #666 !important; margin-bottom: 1.5rem !important; line-height: 1.6 !important; flex-grow: 1 !important;">Lead product development and strategy to enhance our marketplace platform and user experience.</p>
                    <div style="margin-bottom: 1.5rem !important;">
                        <strong style="color: #333 !important; display: block !important; margin-bottom: 0.5rem !important;">Requirements:</strong>
                        <ul style="margin: 0 !important; padding-left: 1.5rem !important; color: #666 !important; line-height: 1.8 !important;">
                            <li style="color: #666 !important;">5+ years product management</li>
                            <li style="color: #666 !important;">E-commerce experience</li>
                            <li style="color: #666 !important;">Strong leadership skills</li>
                            <li style="color: #666 !important;">Data-driven decision making</li>
                        </ul>
                    </div>
                    <button type="button" 
                            onclick="selectPosition('Product Manager')"
                            style="background: #ffe066 !important; color: #181818 !important; border: none !important; padding: 0.75rem 1.5rem !important; font-weight: bold !important; cursor: pointer !important; border-radius: 6px !important; font-size: 1rem !important; transition: all 0.3s !important; width: 100% !important; margin-top: auto !important;"
                            onmouseover="this.style.background='#ffed4e';"
                            onmouseout="this.style.background='#ffe066';">
                        Apply Now
                    </button>
                </div>
            </div>
            </div>
        </section>
        
        <!-- Application Form Section -->
        <div style="text-align: center; margin: 4rem 0 2rem; background: #fff !important; padding: 2rem 0;">
            <h2 style="margin-bottom: 0.5rem; color: #ffe066 !important; font-size: 2rem; font-weight: bold;">JOB APPLICATION</h2>
            <p style="color: #666 !important; margin-bottom: 3rem; font-size: 1.1rem;">Send us your resume</p>
        </div>
        
        <div style="max-width: 900px; margin: 0 auto 3rem; padding: 2.5rem; background: #f8f9fa !important; border: 1px solid #e0e0e0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important; display: block !important; visibility: visible !important;">
        <form method="POST" enctype="multipart/form-data" class="form" id="applicationForm">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="full_name" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Full Name *</label>
                <input type="text" id="full_name" name="full_name" required value="<?php echo esc_attr($full_name ?? ''); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; background: #fff !important; color: #333 !important; font-size: 1rem;">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="email" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Email *</label>
                    <input type="email" id="email" name="email" required value="<?php echo esc_attr($email ?? ''); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; background: #fff !important; color: #333 !important; font-size: 1rem;">
                </div>
                <div class="form-group">
                    <label for="phone" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Phone *</label>
                    <input type="tel" id="phone" name="phone" required value="<?php echo esc_attr($phone ?? ''); ?>" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; background: #fff !important; color: #333 !important; font-size: 1rem;">
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="position" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Role *</label>
                <select id="position" name="position" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; background: #fff !important; color: #333 !important; font-size: 1rem;">
                    <option value="">Select an open role</option>
                    <?php 
                    $pos_options = ['Web Developer', 'Marketing Specialist', 'Customer Support', 'Product Manager'];
                    foreach ($pos_options as $pos_option):
                    ?>
                    <option value="<?php echo esc_attr($pos_option); ?>" <?php echo ($position ?? '') == $pos_option ? 'selected' : ''; ?>><?php echo esc_html($pos_option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="cv_file" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Upload Resume *</label>
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx,.txt" required 
                           style="position: absolute; opacity: 0; width: 0.1px; height: 0.1px;">
                    <label for="cv_file" style="display: inline-block; padding: 0.75rem 1.5rem; background: #ffe066 !important; color: #181818 !important; border-radius: 6px; cursor: pointer; text-align: center; font-weight: bold; border: none;">
                        <i class="fas fa-upload"></i> Select File
                    </label>
                    <span id="file-name" style="color: #666 !important;">No file selected</span>
                </div>
                <small style="color: #999 !important; display: block; margin-top: 0.5rem;">Accepted formats: PDF, DOC, DOCX, TXT - Max 5MB</small>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="cover_letter" style="color: #333 !important; display: block; margin-bottom: 0.5rem; font-weight: bold;">Cover Letter / Portfolio Link (optional)</label>
                <textarea id="cover_letter" name="cover_letter" rows="6" placeholder="Tell us why you're interested in this position or share your portfolio link..." style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; background: #fff !important; color: #333 !important; font-size: 1rem; font-family: inherit; resize: vertical;"><?php echo esc_textarea($cover_letter ?? ''); ?></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="background: #ffe066 !important; color: #181818 !important; padding: 1rem 2rem; font-size: 1.1rem; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; flex: 1;">Submit Application</button>
                <button type="reset" class="btn btn-secondary" onclick="document.getElementById('applicationForm').reset(); document.getElementById('file-name').textContent='No file selected';" style="background: #fff !important; color: #333 !important; padding: 1rem 2rem; font-size: 1.1rem; font-weight: bold; border: 1px solid #ddd; border-radius: 8px; cursor: pointer; flex: 1;">Reset Form</button>
            </div>
        </form>
        </div>
        
        <script>
            document.getElementById('cv_file').addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
                document.getElementById('file-name').textContent = fileName;
            });
            
            function selectPosition(positionName) {
                document.getElementById('position').value = positionName;
                var formCard = document.querySelector('form').closest('div[style*="max-width: 900px"]');
                if (formCard) {
                    formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    formCard.style.animation = 'pulse 0.5s';
                    setTimeout(function() {
                        formCard.style.animation = '';
                    }, 500);
                }
            }
        </script>
        <style>
            @keyframes pulse {
                0%, 100% { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
                50% { box-shadow: 0 4px 16px rgba(255, 224, 102, 0.3); }
            }
        </style>
    </div>
</div>

<div style="clear: both; width: 100%;"></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<style>
    /* Ensure footer is visible */
    .site-footer {
        background: #111 !important;
        color: #fff !important;
        margin-top: 4rem !important;
        padding: 2rem 0 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 10 !important;
        width: 100% !important;
        clear: both !important;
    }
    .footer-main {
        background: #111 !important;
        color: #fff !important;
        padding: 2rem 0 !important;
        display: block !important;
        visibility: visible !important;
    }
    .footer-main .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 1rem !important;
    }
    .footer-links {
        display: inline-flex !important;
        list-style: none !important;
        gap: 1.2em !important;
        margin: 1em 0 0 0 !important;
        padding: 0 !important;
        justify-content: center !important;
        visibility: visible !important;
    }
    .footer-links li {
        visibility: visible !important;
    }
    .footer-links a {
        color: #fff !important;
        text-decoration: none !important;
        visibility: visible !important;
    }
    .footer-links a:hover {
        color: #ffe066 !important;
    }
    .footer-bottom {
        background: #0055aa !important;
        margin-top: 1em !important;
        padding: 0.7em 0 0.3em 0 !important;
        display: block !important;
        visibility: visible !important;
        width: 100% !important;
    }
    .footer-bottom .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        padding: 0 1rem !important;
    }
    .footer-bottom p {
        color: #fff !important;
        margin: 0 !important;
        visibility: visible !important;
    }
</style>
</body>
</html>

