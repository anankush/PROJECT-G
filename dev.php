<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);

// Background images generation
$bgImages = [];
$imageDir = __DIR__ . '/landing/images/';
if (is_dir($imageDir)) {
    $files = scandir($imageDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'svg', 'webp', 'gif'])) {
            $bgImages[] = 'landing/images/' . $file;
        }
    }
}
function getTrackHtml($images) {
    if (empty($images)) return '';
    shuffle($images);
    $html = '';
    foreach($images as $img) {
        $html .= '<img src="' . htmlspecialchars($img) . '" class="floating-bg-card" alt="bg">';
    }
    return $html . $html . $html . $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developers & Contribution | Roshan Ka Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing/assets/css/style.css?v=2.2">
    <style>
        .collab-section {
            padding-top: 12rem;
            min-height: 80vh;
        }
        .collab-wrapper {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: var(--glass-border);
            box-shadow: var(--glass-shadow);
            border-radius: 32px;
            padding: 4rem;
            text-align: center;
        }
        .collab-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), #0f172a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .collab-grid {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3rem;
            margin-top: 4rem;
            flex-wrap: wrap;
        }
        .collab-card {
            flex: 1;
            min-width: 250px;
            max-width: 350px;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: var(--transition);
        }
        .collab-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.6);
            box-shadow: 0 20px 40px rgba(0, 98, 255, 0.1);
        }
        .collab-avatar {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .collab-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .collab-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }
        .collab-card p {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }
        .collab-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            opacity: 0.5;
            animation: pulse-icon 2s infinite alternate;
        }
        @keyframes pulse-icon {
            0% { transform: scale(1); opacity: 0.3; }
            100% { transform: scale(1.2); opacity: 0.8; }
        }
        .social-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .social-btns a {
            flex: 1;
            padding: 0.6rem 0;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }
        .btn-linkedin {
            background: rgba(10, 102, 194, 0.1);
            color: #0a66c2;
            border: 1px solid rgba(10, 102, 194, 0.3);
        }
        .btn-linkedin:hover {
            background: #0a66c2;
            color: white;
        }
        .btn-github {
            background: rgba(36, 41, 46, 0.1);
            color: #24292e;
            border: 1px solid rgba(36, 41, 46, 0.3);
        }
        .btn-github:hover {
            background: #24292e;
            color: white;
        }
        
        @media (max-width: 768px) {
            .collab-wrapper { padding: 2.5rem 1.5rem; }
            .collab-grid { gap: 2rem; flex-direction: column; }
            .collab-icon { transform: rotate(45deg); } /* Plus symbol looks cool rotated */
        }
    </style>
</head>
<body>
    <div class="floating-bg-container">
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
        <div class="bg-marquee-track"><?= getTrackHtml($bgImages) ?></div>
    </div>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

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

    <section class="collab-section container">
        <div class="collab-wrapper reveal">
            <div class="collab-header">
                <h2>Project Collaboration</h2>
                <p style="color: var(--text-light); max-width: 600px; margin: 0 auto;">This platform was brought to life through an exclusive partnership, combining a strong brand vision with cutting-edge software engineering.</p>
            </div>
            
            <div class="collab-grid">
                <!-- Roshan Ka Tech (Brand) -->
                <div class="collab-card">
                    <div class="collab-avatar">
                        <!-- Pulling the original logo provided by the user in the root images folder -->
                        <img src="images/roshan.png" alt="Roshan Ka Tech Logo" onerror="this.src='https://ui-avatars.com/api/?name=R+K&background=0f172a&color=fff'">
                    </div>
                    <h3>Roshan Ka Tech</h3>
                    <p>Brand & Operations</p>
                    <div class="social-btns">
                        <a href="https://www.youtube.com/@RoshanKaTech1" target="_blank" class="btn-github" style="background: rgba(255,0,0,0.1); color: #ff0000; border-color: rgba(255,0,0,0.3);">YouTube Channel</a>
                    </div>
                </div>
                
                <!-- Collaboration Icon -->
                <div class="collab-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>

                <!-- Developer (Itz Nayan) -->
                <div class="collab-card">
                    <div class="collab-avatar">
                        <!-- Developer Avatar -->
                        <img src="https://ui-avatars.com/api/?name=Itz+Nayan&background=2563eb&color=fff&size=150" alt="Itz Nayan">
                    </div>
                    <h3>Itz Nayan</h3>
                    <p>Lead full stack Web Developer & UI Engineer</p>
                    <div class="social-btns">
                        <a href="https://linkedin.com/in/itznayan" target="_blank" class="btn-linkedin">LinkedIn</a>
                        <a href="https://github.com/anankush" target="_blank" class="btn-github">GitHub</a>
                    </div>
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

    <script src="landing/assets/js/main.js?v=2.1"></script>
</body>
</html>


