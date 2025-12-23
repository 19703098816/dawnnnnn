<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$page_title = 'Home';
$homepage_products = [
  [
    'id'=>1,
    'name'=>'Elegant Bracelet',
    'desc'=>'A handcrafted bracelet made from fine materials. Perfect for daily elegance.',
    'img'=>'image/1.jpg',
    'price'=>'59.00',
    'artisan'=>'Artisan A',
    'category'=>'Jewelry'
  ],[
    'id'=>2,
    'name'=>'Ceramic Vase',
    'desc'=>'A unique ceramic vase with modern flair. Ideal for flowers or decor.',
    'img'=>'image/2.jpg',
    'price'=>'42.00',
    'artisan'=>'Artisan B',
    'category'=>'Home Decor'
  ],[
    'id'=>3,
    'name'=>'Wool Scarf',
    'desc'=>'Warm and soft scarf woven by hand. Essential for autumn and winter.',
    'img'=>'image/3.jpg',
    'price'=>'28.00',
    'artisan'=>'Artisan C',
    'category'=>'Textiles'
  ],[
    'id'=>4,
    'name'=>'Wooden Chair',
    'desc'=>'Solid oak chair designed for comfort and style, suitable for any interior.',
    'img'=>'image/4.jpg',
    'price'=>'120.00',
    'artisan'=>'Artisan D',
    'category'=>'Furniture'
  ],[
    'id'=>5,
    'name'=>'Abstract Painting',
    'desc'=>'Colorful abstract wall art to energize your living space.',
    'img'=>'image/5.jpg',
    'price'=>'150.00',
    'artisan'=>'Artisan E',
    'category'=>'Art'
  ],[
    'id'=>6,
    'name'=>'Organic Tea Set',
    'desc'=>'Handmade tea set from organic clay, for your healthy moments.',
    'img'=>'image/6.jpg',
    'price'=>'38.00',
    'artisan'=>'Artisan F',
    'category'=>'Kitchenware'
  ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Handcrafted Artisan Marketplace</title>
    <meta name="description" content="Discover unique handcrafted products from talented artisans. Shop jewelry, home decor, textiles, and more at Dawn's ArtisanCraft Marketplace.">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- UI Personalization Panel -->
    <div id="personalizationPanel" class="personalization-panel" style="display: none;">
        <div class="panel-header">
            <h3><i class="fas fa-cog"></i> Personalize Your Experience</h3>
            <button class="close-panel" onclick="togglePersonalizationPanel()" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="panel-content">
            <div class="personalization-option">
                <label for="fontSize">
                    <i class="fas fa-text-height"></i> Text Size
                </label>
                <div class="slider-container">
                    <input type="range" id="fontSize" min="0.8" max="1.5" step="0.1" value="1" oninput="changeFontSize(this.value)">
                    <span id="fontSizeValue">100%</span>
                </div>
            </div>
            
            <div class="personalization-option">
                <label for="colorTheme">
                    <i class="fas fa-palette"></i> Color Theme
                </label>
                <div class="color-themes">
                    <button class="theme-btn active" data-theme="default" onclick="changeColorTheme('default')" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);"></button>
                    <button class="theme-btn" data-theme="blue" onclick="changeColorTheme('blue')" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);"></button>
                    <button class="theme-btn" data-theme="green" onclick="changeColorTheme('green')" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></button>
                    <button class="theme-btn" data-theme="purple" onclick="changeColorTheme('purple')" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);"></button>
                    <button class="theme-btn" data-theme="orange" onclick="changeColorTheme('orange')" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);"></button>
                    <button class="theme-btn" data-theme="pink" onclick="changeColorTheme('pink')" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);"></button>
                </div>
            </div>
            
            <div class="personalization-option">
                <button class="reset-btn" onclick="resetPersonalization()">
                    <i class="fas fa-undo"></i> Reset to Default
                </button>
            </div>
        </div>
    </div>
    
    <!-- Personalization Toggle Button -->
    <button id="personalizationToggle" class="personalization-toggle" onclick="togglePersonalizationPanel()" aria-label="Personalize UI">
        <i class="fas fa-adjust"></i>
    </button>

    <!-- Banner Section with Video Background and Enhanced UI -->
    <section class="hero-banner-section">
        <!-- Video Background -->
        <div class="video-background">
            <video autoplay muted loop playsinline id="heroVideo" preload="auto">
                <!-- Multiple video sources for better compatibility -->
                <source src="https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4" type="video/mp4">
                <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                <!-- Fallback image if video fails -->
                <img src="<?php echo SITE_URL; ?>/image/logo.jpg" alt="Background" style="width: 100%; height: 100%; object-fit: cover;">
            </video>
            <div class="video-overlay"></div>
        </div>
        
        <!-- Content -->
        <div class="hero-content">
            <div class="container">
                <div class="hero-inner">
                    <!-- Logo with Animation -->
                    <div class="hero-logo-wrapper">
                        <div class="logo-box">
                            <div class="logo-border"></div>
                            <div class="logo-text">
                                <span class="logo-main">DAWN</span>
                                <div class="logo-line"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Heading with Typing Effect -->
                    <h1 class="hero-title">
                        <span class="title-line">Discover</span>
                        <span class="title-line highlight">Handcrafted</span>
                        <span class="title-line">Excellence</span>
                    </h1>
                    
                    <!-- Tagline -->
                    <p class="hero-tagline">
                        <span class="quote-mark">"</span>
                        <span class="tagline-text">Where Art Meets Craft, Quality Meets Passion</span>
                        <span class="quote-mark">"</span>
                    </p>
                    
                    <!-- Description -->
                    <p class="hero-description">
                        Support local artisans and find exclusive, sustainable products for your lifestyle.
                    </p>
                    
                    <!-- Action Buttons -->
                    <div class="hero-actions">
                        <a href="products.php" class="btn-hero btn-primary-hero">
                            <span class="btn-text">Shop Now</span>
                            <i class="fas fa-arrow-right btn-icon"></i>
                        </a>
                        <a href="https://dawn1.infinityfreeapp.com/5-2/" class="btn-hero btn-secondary-hero">
                            <span class="btn-text">Become an Artisan</span>
                            <i class="fas fa-palette btn-icon"></i>
                        </a>
                    </div>
                    
                    <!-- Scroll Indicator -->
                    <div class="scroll-indicator">
                        <div class="scroll-mouse">
                            <div class="scroll-wheel"></div>
                        </div>
                        <span class="scroll-text">Scroll to explore</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <style>
    /* Hero Banner Section Styles */
    .hero-banner-section {
        position: relative;
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    
    /* Video Background */
    .video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }
    
    .video-background video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(107, 114, 128, 0.85) 0%, rgba(75, 85, 99, 0.9) 100%);
        z-index: 1;
    }
    
    /* Hero Content */
    .hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 4rem 0;
        text-align: center;
        color: white;
    }
    
    .hero-inner {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    
    /* Logo Box Design */
    .hero-logo-wrapper {
        margin-bottom: 2rem;
        animation: fadeInDown 1s ease-out;
    }
    
    .logo-box {
        position: relative;
        display: inline-block;
        padding: 2rem 3rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .logo-border {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 12px;
        animation: borderPulse 2s ease-in-out infinite;
    }
    
    .logo-text {
        position: relative;
        z-index: 1;
    }
    
    .logo-main {
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        display: block;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .logo-line {
        width: 80px;
        height: 2px;
        background: white;
        margin: 0.5rem auto 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    /* Hero Title */
    .hero-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 1s ease-out 0.3s both;
    }
    
    .title-line {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .title-line.highlight {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
        position: relative;
    }
    
    /* Tagline */
    .hero-tagline {
        font-size: clamp(1.2rem, 3vw, 1.8rem);
        font-style: italic;
        margin-bottom: 1.5rem;
        opacity: 0.95;
        animation: fadeInUp 1s ease-out 0.6s both;
    }
    
    .quote-mark {
        font-size: 1.5em;
        color: #fbbf24;
        font-weight: bold;
    }
    
    .tagline-text {
        margin: 0 0.5rem;
    }
    
    /* Description */
    .hero-description {
        font-size: clamp(1rem, 2vw, 1.2rem);
        margin-bottom: 2.5rem;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 1s ease-out 0.9s both;
    }
    
    /* Hero Buttons */
    .hero-actions {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
        animation: fadeInUp 1s ease-out 1.2s both;
    }
    
    .btn-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-primary-hero {
        background: white;
        color: #4b5563;
        border: 2px solid white;
    }
    
    .btn-primary-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
        background: #f8f9fa;
    }
    
    .btn-secondary-hero {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(10px);
    }
    
    .btn-secondary-hero:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.25);
        border-color: white;
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
    }
    
    .btn-icon {
        transition: transform 0.3s ease;
    }
    
    .btn-hero:hover .btn-icon {
        transform: translateX(5px);
    }
    
    /* Scroll Indicator */
    .scroll-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        margin-top: 3rem;
        animation: fadeIn 1s ease-out 1.5s both;
    }
    
    .scroll-mouse {
        width: 30px;
        height: 50px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        position: relative;
        margin-bottom: 0.5rem;
    }
    
    .scroll-wheel {
        width: 4px;
        height: 10px;
        background: white;
        border-radius: 2px;
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        animation: scrollWheel 2s ease-in-out infinite;
    }
    
    .scroll-text {
        font-size: 0.9rem;
        opacity: 0.7;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    
    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes borderPulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.02);
        }
    }
    
    @keyframes scrollWheel {
        0% {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        100% {
            opacity: 0;
            transform: translateX(-50%) translateY(20px);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-banner-section {
            min-height: 80vh;
        }
        
        .hero-inner {
            padding: 0 1rem;
        }
        
        .logo-box {
            padding: 1.5rem 2rem;
        }
        
        .logo-main {
            font-size: 1.8rem;
        }
        
        .hero-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn-hero {
            width: 100%;
            justify-content: center;
        }
        
        .video-background video {
            object-position: center;
        }
    }
    
    @media (max-width: 480px) {
        .hero-banner-section {
            min-height: 70vh;
        }
        
        .logo-box {
            padding: 1rem 1.5rem;
        }
        
        .logo-main {
            font-size: 1.5rem;
        }
    }
    </style>
    
    <style>
    /* Personalization Panel Styles */
    .personalization-panel {
        position: fixed;
        top: 80px;
        right: 20px;
        width: 320px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
    }
    
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    
    .panel-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .close-panel {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    
    .close-panel:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .panel-content {
        padding: 1.5rem;
    }
    
    .personalization-option {
        margin-bottom: 1.5rem;
    }
    
    .personalization-option:last-child {
        margin-bottom: 0;
    }
    
    .personalization-option label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }
    
    .slider-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .slider-container input[type="range"] {
        flex: 1;
        height: 6px;
        border-radius: 3px;
        background: #e5e7eb;
        outline: none;
        -webkit-appearance: none;
    }
    
    .slider-container input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #6b7280;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .slider-container input[type="range"]::-webkit-slider-thumb:hover {
        background: #4b5563;
    }
    
    .slider-container input[type="range"]::-moz-range-thumb {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #6b7280;
        cursor: pointer;
        border: none;
    }
    
    #fontSizeValue {
        min-width: 50px;
        text-align: right;
        font-weight: 600;
        color: #6b7280;
    }
    
    .color-themes {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    
    .theme-btn {
        width: 100%;
        height: 50px;
        border: 3px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }
    
    .theme-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .theme-btn.active {
        border-color: #333;
        box-shadow: 0 0 0 2px white, 0 0 0 4px #333;
    }
    
    .theme-btn.active::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 1.2rem;
        font-weight: bold;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .reset-btn {
        width: 100%;
        padding: 0.75rem;
        background: #f3f4f6;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .reset-btn:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }
    
    .personalization-toggle {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s;
    }
    
    .personalization-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Theme Variables */
    :root {
        --theme-primary: #6b7280;
        --theme-secondary: #4b5563;
        --font-size-multiplier: 1;
    }
    
    /* Apply font size to body */
    body {
        font-size: calc(1rem * var(--font-size-multiplier));
    }
    
    /* Theme-specific styles */
    body.theme-blue .video-overlay {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.85) 0%, rgba(30, 64, 175, 0.9) 100%);
    }
    
    body.theme-green .video-overlay {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.85) 0%, rgba(5, 150, 105, 0.9) 100%);
    }
    
    body.theme-purple .video-overlay {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.85) 0%, rgba(109, 40, 217, 0.9) 100%);
    }
    
    body.theme-orange .video-overlay {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.85) 0%, rgba(234, 88, 12, 0.9) 100%);
    }
    
    body.theme-pink .video-overlay {
        background: linear-gradient(135deg, rgba(236, 72, 153, 0.85) 0%, rgba(219, 39, 119, 0.9) 100%);
    }
    
    body.theme-blue .personalization-toggle,
    body.theme-blue .panel-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
    }
    
    body.theme-green .personalization-toggle,
    body.theme-green .panel-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    body.theme-purple .personalization-toggle,
    body.theme-purple .panel-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
    }
    
    body.theme-orange .personalization-toggle,
    body.theme-orange .panel-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }
    
    body.theme-pink .personalization-toggle,
    body.theme-pink .panel-header {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
    }
    
    @media (max-width: 768px) {
        .personalization-panel {
            width: calc(100% - 40px);
            right: 20px;
            left: 20px;
        }
        
        .personalization-toggle {
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
    }
    </style>
    
    <script>
    // Video fallback if video fails to load
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('heroVideo');
        if (video) {
            // Try multiple video sources
            const videoSources = [
                'https://videos.pexels.com/video-files/3045163/3045163-hd_1920_1080_30fps.mp4',
                'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'
            ];
            
            let currentSourceIndex = 0;
            
            video.addEventListener('error', function() {
                if (currentSourceIndex < videoSources.length - 1) {
                    currentSourceIndex++;
                    const source = video.querySelector('source');
                    if (source) {
                        source.src = videoSources[currentSourceIndex];
                        video.load();
                    }
                } else {
                    // If all videos fail, use gradient background
                    const videoBackground = document.querySelector('.video-background');
                    if (videoBackground) {
                        videoBackground.style.background = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                        video.style.display = 'none';
                    }
                }
            });
            
            // Try to play video
            video.play().catch(function(error) {
                console.log('Video autoplay prevented:', error);
                // Fallback to gradient if autoplay fails
                const videoBackground = document.querySelector('.video-background');
                if (videoBackground) {
                    videoBackground.style.background = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                    video.style.display = 'none';
                }
            });
        }
        
        // Load saved personalization settings
        loadPersonalizationSettings();
    });
    
    // Personalization Functions
    function togglePersonalizationPanel() {
        const panel = document.getElementById('personalizationPanel');
        if (panel) {
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
    }
    
    function changeFontSize(value) {
        const fontSizeValue = document.getElementById('fontSizeValue');
        const percentage = Math.round(value * 100);
        if (fontSizeValue) {
            fontSizeValue.textContent = percentage + '%';
        }
        
        document.documentElement.style.setProperty('--font-size-multiplier', value);
        localStorage.setItem('fontSize', value);
    }
    
    function changeColorTheme(theme) {
        // Remove all theme classes
        document.body.classList.remove('theme-blue', 'theme-green', 'theme-purple', 'theme-orange', 'theme-pink');
        
        // Add selected theme
        if (theme !== 'default') {
            document.body.classList.add('theme-' + theme);
        }
        
        // Update active button
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-theme="${theme}"]`).classList.add('active');
        
        localStorage.setItem('colorTheme', theme);
    }
    
    function resetPersonalization() {
        changeFontSize(1);
        document.getElementById('fontSize').value = 1;
        changeColorTheme('default');
        localStorage.removeItem('fontSize');
        localStorage.removeItem('colorTheme');
    }
    
    function loadPersonalizationSettings() {
        // Load font size
        const savedFontSize = localStorage.getItem('fontSize');
        if (savedFontSize) {
            changeFontSize(savedFontSize);
            document.getElementById('fontSize').value = savedFontSize;
        }
        
        // Load color theme
        const savedTheme = localStorage.getItem('colorTheme');
        if (savedTheme) {
            changeColorTheme(savedTheme);
        }
    }
    
    // Close panel when clicking outside
    document.addEventListener('click', function(event) {
        const panel = document.getElementById('personalizationPanel');
        const toggle = document.getElementById('personalizationToggle');
        
        if (panel && toggle && 
            !panel.contains(event.target) && 
            !toggle.contains(event.target) &&
            panel.style.display === 'block') {
            panel.style.display = 'none';
        }
    });
    </script>

    <!-- Homepage Products (Always 6) -->
    <section class="featured-products">
        <div class="container">
            <h2>Our Featured Products</h2>
            <div class="products-grid">
                <?php foreach($homepage_products as $prod): ?>
                <div class="product-card blue-card" id="card-home-<?php echo $prod['id']; ?>">
                    <div class="product-icon product-image" style="position:relative;">
                        <img src="<?php echo $prod['img']; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                        <div class="wm-title" style="top:18px;left:50%;transform:translateX(-50%);position:absolute;"> <?php echo htmlspecialchars($prod['name']); ?> </div>
                    </div>
                    <div class="product-info">
                        <div class="product-meta">
                            <span class="artisan-name">by <?php echo htmlspecialchars($prod['artisan']); ?></span>
                            <span class="category"><?php echo htmlspecialchars($prod['category']); ?></span>
                        </div>
                        <h3><?php echo htmlspecialchars($prod['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($prod['desc']); ?></p>
                        <div class="product-footer">
                            <span class="price">$<?php echo htmlspecialchars($prod['price']); ?></span>
                            <a href="products.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-outline light-btn">Details</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center" style="margin-top:2em;">
                <a href="products.php" class="btn btn-primary">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="why-choose-us">
        <div class="container">
            <h2>Why Choose ArtisanCraft?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3>Handcrafted Quality</h3>
                    <p>Every product is carefully crafted by skilled artisans using traditional techniques and premium materials.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustainable & Eco-Friendly</h3>
                    <p>Support environmentally conscious practices with products made from sustainable and recycled materials.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Support Local Artisans</h3>
                    <p>Your purchase directly supports local artists and craftspeople, helping them continue their passion.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Fast & Secure Shipping</h3>
                    <p>Free shipping on orders over $75. Secure packaging ensures your items arrive in perfect condition.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <h2>Share Your Feedback</h2>
                <p>We value your opinion! Help us improve by sharing your thoughts, suggestions, or any issues you've encountered.</p>
                <a href="feedback.php" class="btn btn-primary" style="display: inline-block; padding: 0.75rem 2rem; font-size: 1.1rem; text-decoration: none; border-radius: 5px; background: white; color: #4b5563; margin-top: 1rem;">Feedback</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/main.js"></script>
    <script>
        // Add to cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    
                    fetch('ajax/add-to-cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showNotification('Product added to cart!', 'success');
                            
                            // Update cart count if element exists
                            const cartCount = document.querySelector('.cart-count');
                            if (cartCount) {
                                cartCount.textContent = data.cart_count;
                            }
                        } else {
                            showNotification(data.message || 'Error adding product to cart', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error adding product to cart', 'error');
                    });
                });
            });
        });

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>
