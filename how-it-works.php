<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);

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
    <title>How It Works | Roshan Ka Tech</title>
    
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
                    <li><a href="dev.php">Developers & Contribution</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="auth/logout.php" class="btn btn-outline" style="padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.9rem;">Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php" style="font-weight: 600;">Login</a></li>
                        <li><a href="auth/register.php" class="btn btn-primary" style="padding: 0.4rem 1.25rem; color: white; border-radius: 8px; font-size: 0.9rem;">Sign Up</a></li>
                    <?php endif; ?>
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

    <!-- How It Works Section -->
    <section class="section how-it-works" style="padding-top: 12rem; min-height: 80vh;">
        <div class="container">
            <div class="text-center reveal">
                <h2>How It Works</h2>
                <p>Three simple steps to buy or sell your gift cards.</p>
            </div>
            
            <div class="steps-grid reveal">
                <div class="step">
                    <span class="step-number">01</span>
                    <h3>Choose Your Gift Card</h3>
                    <p>Select the brand of the gift card you want to trade from our supported list.</p>
                </div>
                <div class="step">
                    <span class="step-number">02</span>
                    <h3>Buy or Sell</h3>
                    <p>Choose whether to purchase a new card or sell your existing one securely.</p>
                </div>
                <div class="step">
                    <span class="step-number">03</span>
                    <h3>Complete Transaction</h3>
                    <p>Get your card details instantly or receive payment for your sale.</p>
                </div>
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
                        <li><a href="dev.php">Developers & Contribution</a></li>
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






