<?php
// Centralized Database and Logic
require_once __DIR__ . '/private/db.php';
require_once __DIR__ . '/private/giftcard_functions.php';

// Fetch active gift cards from the database dynamically
$giftCards = getActiveGiftCards($pdo);

// Read background images dynamically from the /images folder
$bgImages = [];
$imageDir = __DIR__ . '/landing/images/';
if (is_dir($imageDir)) {
    $files = scandir($imageDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        // Only include standard image formats
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'svg', 'webp', 'gif'])) {
            $bgImages[] = 'landing/images/' . $file;
        }
    }
}

// Function to generate a randomly shuffled HTML track for seamless loop
function getTrackHtml($images) {
    shuffle($images);
    $html = '';
    foreach($images as $img) {
        $html .= '<img src="' . htmlspecialchars($img) . '" class="floating-bg-card" alt="floating bg">';
    }
    // Quadruple the set to absolutely guarantee it never leaves an empty gap on any screen size
    return $html . $html . $html . $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#f4f7fb">
    <title>Contact | Roshan Ka Tech</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="landing/assets/css/style.css?v=2.1">
</head>
<body>

    <!-- Dynamic Floating Background Cards -->
    <div class="floating-bg-container">
        <!-- 7 Parallel Tracks to guarantee full vertical coverage on massive desktop monitors -->
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
    </div>

    <!-- Background Glassmorphism Blobs -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <!-- Header / Navigation -->
    <header class="header">
        <div class="container nav-container">
            <a href="index.php" class="logo">Roshan Ka Tech</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="how-it-works.php">How It Works</a></li>
                    <li><a href="gift-cards.php">Buy / Sell Gift Cards</a></li>
                    <li><a href="why-us.php">Why Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-btn" aria-label="Open Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="section" style="padding-top: 12rem; min-height: 80vh;">
        <div class="container">
            <div class="text-center reveal">
                <h2>Contact Us</h2>
                <p>We're here to help! Send us a message and we'll get back to you shortly.</p>
            </div>
            <div class="glass-panel reveal" style="max-width: 600px; margin: 3rem auto; padding: 3rem;">
                <div class="glass-form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Name</label>
                    <input type="text" class="glass-select" placeholder="Your Name" style="width: 100%;">
                </div>
                <div class="glass-form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Email</label>
                    <input type="email" class="glass-select" placeholder="Your Email" style="width: 100%;">
                </div>
                <div class="glass-form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Message</label>
                    <textarea class="glass-select" rows="5" placeholder="How can we help you?" style="width: 100%; resize: vertical;"></textarea>
                </div>
                <button class="btn btn-primary" style="width: 100%;">Send Message</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3 class="mb-4">Roshan Ka Tech</h3>
                    <p>Your trusted platform to securely buy and sell gift cards.</p>
                </div>
                
                <div>
                    <h3 class="mb-4">Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="how-it-works.php">How It Works</a></li>
                        <li><a href="gift-cards.php">Buy / Sell Gift Cards</a></li>
                        <li><a href="why-us.php">Why Us</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="mb-4">Contact</h3>
                    <ul class="footer-links">
                        <li><a href="#">support@placeholder.com</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 <strong>Roshan Ka Tech.</strong> All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JS -->
    <script src="landing/assets/js/main.js?v=2.1"></script>
</body>
</html>




