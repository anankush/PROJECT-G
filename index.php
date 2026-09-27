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
    <title>Roshan Ka Tech | Buy & Sell Gift Cards</title>
    
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
            <a href="#" class="logo">Roshan Ka Tech</a>
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content reveal">
            <h1>Buy & Sell Gift Cards.<br>Fast & Secure.</h1>
            <p>Buy premium gift cards at great rates or turn your unused gift cards into real money.<br>
            <a href="https://www.youtube.com/@RoshanKaTech1" target="_blank" class="yt-channel-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                Roshan Ka Tech
            </a></p>
            <div class="hero-cta">
                <a href="#gift-cards" class="btn btn-primary">Start Trading</a>
                <a href="#how-it-works" class="btn btn-outline">How It Works</a>
            </div>
        </div>
    </section>

    <!-- Supported Gift Cards Section (Dynamic) -->
    <section id="gift-cards" class="section gift-cards">
        <div class="container">
            <div class="text-center reveal">
                <h2>Trade Gift Cards</h2>
                <p>Select your gift card brand below to buy or sell.</p>
            </div>
            
            <div style="position: relative; padding-top: 1.5rem;">
                <!-- View All Link (Absolutely positioned to sit perfectly flush at the top right of the cards grid) -->
                <a href="gift-cards.php" class="view-all-link" style="position: absolute; right: 0; top: 0; color: var(--primary-color); font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 0.3rem; z-index: 10;">
                    View All
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
                
                <div class="cards-grid reveal" style="margin-top: 1.5rem;">
                    <?php if (empty($giftCards)): ?>
                        
                        <!-- Empty State: Displayed when no active gift cards exist in the database -->
                        <div class="empty-state glass-panel">
                            <h3>Gift cards will appear here soon.</h3>
                            <p>We are currently updating our inventory. Please check back later.</p>
                        </div>
    
                    <?php else: ?>
                        
                        <!-- Dynamic Loop: Display active gift cards (Limit to 8 on homepage) -->
                        <?php 
                        $displayCards = array_slice($giftCards, 0, 8);
                        foreach ($displayCards as $card): 
                        ?>
                            <div class="card">
                                <div class="card-img-wrapper">
                                    <img src="<?= htmlspecialchars($card['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($card['brand_name']) ?> Gift Card" loading="lazy">
                                </div>
                                <h3><?= htmlspecialchars($card['brand_name']) ?></h3>
                                
                                <a href="landing/card-details.php?id=<?= htmlspecialchars($card['id']) ?>" class="btn btn-primary" style="margin-top: 1rem;">Buy / Sell Options</a>
                            </div>
                        <?php endforeach; ?>
    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section how-it-works">
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
        </div>
    </section>

    <!-- Trust / Info Section -->
    <section class="trust-info">
        <div class="container reveal">
            <h2>Ready to Trade?</h2>
            <p class="mt-4 mb-8">Buy premium gift cards instantly or submit your unused cards for quick payment. We maintain a secure environment to ensure your trades go smoothly.</p>
            <a href="#gift-cards" class="btn btn-primary">Start Trading</a>
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
                        <li><a href="why-us.php">Why Us</a></li>`n                        <li><a href="dev.php">Developers & Contribution</a></li>
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
                <p>&copy; 2026 <strong>Roshan Ka Tech.</strong> All rights reserved.</p>`n                
            </div>
        </div>
    </footer>

    <!-- Custom JS -->
    <script src="landing/assets/js/main.js?v=2.1"></script>
</body>
</html>





