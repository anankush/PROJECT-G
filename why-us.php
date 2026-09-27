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
    <title>Why Us | Roshan Ka Tech</title>
    
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
                    <li><a href="how-it-works.php">How It Works</a></li>
                    <li><a href="#gift-cards">Gift Cards</a></li>
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

    <!-- Why Choose Us -->
    <section class="section" style="padding-top: 12rem; min-height: 80vh;">
        <div class="container">
            <div class="text-center reveal">
                <h2>Why Roshan Ka Tech</h2>
                <p>We provide a reliable platform for your gift card trades.</p>
            </div>
            
            <div class="features-grid reveal">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <h3>Simple Process</h3>
                    <p>No complicated forms. Just straight to the point.</p><a href="why-us.php#simple-process" class="know-more-link">Know More &rarr;</a>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3>Fast Verification</h3>
                    <p>We work quickly to verify your card's validity.</p><a href="why-us.php#fast-verification" class="know-more-link">Know More &rarr;</a>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h3>Secure Handling</h3>
                    <p>Your details are always processed securely.</p><a href="why-us.php#secure-handling" class="know-more-link">Know More &rarr;</a>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <h3>Easy Payout</h3>
                    <p>Receive your funds conveniently after approval.</p><a href="why-us.php#easy-payout" class="know-more-link">Know More &rarr;</a>
                </div>
            </div>
            
            <div class="mt-4" style="margin-top: 5rem;">
                <div id="simple-process" class="glass-panel" style="padding: 3rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: var(--primary-color);">Simple Process</h3>
                    <p style="font-size: 1.1rem; color: var(--text-light);">Our system is designed to remove all the complexity from trading gift cards. No need to fill out endless forms or jump through hoops. Just select your card, choose the value, and you are ready to go. The entire process takes less than a minute.</p>
                </div>
                <div id="fast-verification" class="glass-panel" style="padding: 3rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: var(--primary-color);">Fast Verification</h3>
                    <p style="font-size: 1.1rem; color: var(--text-light);">We understand that your time is valuable. Our dedicated team and automated systems work round-the-clock to verify the validity of your gift cards as quickly as possible. Most trades are verified within minutes, so you never have to wait.</p>
                </div>
                <div id="secure-handling" class="glass-panel" style="padding: 3rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: var(--primary-color);">Secure Handling</h3>
                    <p style="font-size: 1.1rem; color: var(--text-light);">Security is our top priority. We use industry-standard encryption and strict data protection policies to ensure that your personal information and gift card details are always safe. You can trade with complete peace of mind.</p>
                </div>
                <div id="easy-payout" class="glass-panel" style="padding: 3rem; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.8rem; margin-bottom: 1rem; color: var(--primary-color);">Easy Payout</h3>
                    <p style="font-size: 1.1rem; color: var(--text-light);">Getting paid should be the easiest part. Once your card is verified, your funds are immediately processed and sent to your preferred payment method. We support multiple payout options to make it as convenient as possible for you.</p>
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
                        <li><a href="index.php#gift-cards">Buy / Sell Gift Cards</a></li>
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

