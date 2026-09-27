<?php
// Centralized Database and Logic
require_once __DIR__ . '/private/db.php';
require_once __DIR__ . '/private/giftcard_functions.php';

// Fetch active gift cards from the database dynamically
$giftCards = getActiveGiftCards($pdo);

// Read background images dynamically from the /images folder
$bgImages = [];
$imageDir = __DIR__ . '/images/';
if (is_dir($imageDir)) {
    $files = scandir($imageDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        // Only include standard image formats
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'svg', 'webp', 'gif'])) {
            $bgImages[] = 'images/' . $file;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roshan Ka Tech | Sell Your Gift Cards</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="landing/assets/css/style.css">
</head>
<body>

    <!-- Dynamic Floating Background Cards -->
    <div class="floating-bg-container">
        <?php foreach($bgImages as $bgImg): ?>
            <img src="<?= htmlspecialchars($bgImg) ?>" class="floating-bg-card" alt="floating bg">
        <?php endforeach; ?>
    </div>

    <!-- Background Glassmorphism Blobs -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <!-- Header / Navigation -->
    <header class="header">
        <div class="container nav-container">
            <a href="#" class="logo">Roshan Ka Tech</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#gift-cards">Gift Cards</a></li>
                    <li><a href="#why-us">Why Us</a></li>
                    <li><a href="#contact">Contact</a></li>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content reveal">
            <h1>Sell Your Gift Cards.<br>Get Paid Easily.</h1>
            <p>Turn your unused gift cards into real money with Roshan Ka Tech. Fast, secure, and hassle-free.</p>
            <div class="hero-cta">
                <a href="#gift-cards" class="btn btn-primary">Sell Your Gift Card</a>
                <a href="#how-it-works" class="btn btn-outline">How It Works</a>
            </div>
        </div>
    </section>

    <!-- Supported Gift Cards Section (Dynamic) -->
    <section id="gift-cards" class="section gift-cards">
        <div class="container">
            <div class="text-center reveal">
                <h2>Gift Cards We Buy</h2>
                <p>Select your gift card brand below to begin the selling process.</p>
            </div>
            
            <div class="cards-grid reveal">
                <?php if (empty($giftCards)): ?>
                    
                    <!-- Empty State: Displayed when no active gift cards exist in the database -->
                    <div class="empty-state glass-panel">
                        <h3>Gift cards will appear here soon.</h3>
                        <p>We are currently updating our inventory. Please check back later.</p>
                    </div>

                <?php else: ?>
                    
                    <!-- Dynamic Loop: Display active gift cards -->
                    <?php foreach ($giftCards as $card): ?>
                        <div class="card">
                            <div class="card-img-wrapper">
                                <img src="<?= htmlspecialchars($card['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($card['brand_name']) ?> Gift Card" loading="lazy">
                            </div>
                            <h3><?= htmlspecialchars($card['brand_name']) ?></h3>
                            
                            <a href="landing/card-details.php?id=<?= htmlspecialchars($card['id']) ?>" class="btn btn-primary" style="margin-top: 1rem;">View Details & Sell</a>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section how-it-works">
        <div class="container">
            <div class="text-center reveal">
                <h2>How It Works</h2>
                <p>Three simple steps to convert your gift cards to cash.</p>
            </div>
            
            <div class="steps-grid reveal">
                <div class="step">
                    <span class="step-number">01</span>
                    <h3>Choose Your Gift Card</h3>
                    <p>Select the brand of the gift card you want to sell from our supported list.</p>
                </div>
                <div class="step">
                    <span class="step-number">02</span>
                    <h3>Submit For Verification</h3>
                    <p>Provide your gift card details securely. We quickly verify the card balance.</p>
                </div>
                <div class="step">
                    <span class="step-number">03</span>
                    <h3>Get Paid</h3>
                    <p>Receive your payment easily once the verification is completed.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="why-us" class="section">
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
                    <p>No complicated forms. Just straight to the point.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3>Fast Verification</h3>
                    <p>We work quickly to verify your card's validity.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h3>Secure Handling</h3>
                    <p>Your details are always processed securely.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <h3>Easy Payout</h3>
                    <p>Receive your funds conveniently after approval.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust / Info Section -->
    <section class="trust-info">
        <div class="container reveal">
            <h2>Ready to Trade?</h2>
            <p class="mt-4 mb-8">Submit your gift cards for verification today and receive your payment directly once approved. We maintain a secure environment to ensure your trades go smoothly.</p>
            <a href="#gift-cards" class="btn btn-glass-alt">Start Selling</a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3 class="mb-4">Roshan Ka Tech</h3>
                    <p>Your trusted platform to securely sell unused gift cards.</p>
                </div>
                
                <div>
                    <h3 class="mb-4">Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#gift-cards">Sell Gift Cards</a></li>
                        <li><a href="#why-us">Why Us</a></li>
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
                <p>&copy; 2026 Roshan Ka Tech. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Custom JS -->
    <script src="landing/assets/js/main.js"></script>
</body>
</html>