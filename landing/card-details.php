<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Centralized Database and Logic
require_once __DIR__ . '/../private/db.php';
require_once __DIR__ . '/../private/giftcard_functions.php';

// Check if user is logged in (Assuming 'user_id' is stored in session upon login)
$isLoggedIn = isset($_SESSION['user_id']);

// Get the gift card ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the specific gift card details
$card = getGiftCardById($pdo, $id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $card ? htmlspecialchars($card['brand_name']) . ' | Roshan Ka Tech' : 'Card Not Found | Roshan Ka Tech' ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

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
                    <li><a href="index.php#how-it-works">How It Works</a></li>
                    <li><a href="index.php#gift-cards">Gift Cards</a></li>
                    <li><a href="index.php#why-us">Why Us</a></li>
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

    <!-- Details Section -->
    <section class="details-section">
        <div class="container">
            
            <?php if (!$card): ?>
                
                <!-- Fallback / Empty State -->
                <div class="empty-state glass-panel reveal" style="max-width: 600px; margin: 0 auto; margin-top: 5rem;">
                    <h3>Gift Card Not Found</h3>
                    <p class="mb-4">We couldn't find the details for this gift card. It may have been removed or the inventory is updating.</p>
                    <a href="index.php" class="btn btn-primary">Return to Home</a>
                </div>

            <?php else: ?>

                <!-- Gift Card Details View -->
                <div class="details-grid reveal">
                    
                    <!-- Left: Image -->
                    <div class="details-img-wrapper">
                        <img src="<?= htmlspecialchars($card['image_path'] ?? '') ?>" alt="<?= htmlspecialchars($card['brand_name']) ?> Gift Card">
                    </div>

                    <!-- Right: Info & Actions -->
                    <div class="details-info">
                        <h1><?= htmlspecialchars($card['brand_name']) ?></h1>
                        <p class="desc">Sell your <?= htmlspecialchars($card['brand_name']) ?> gift card safely and securely. Please select your card's denomination to proceed.</p>

                        <!-- Denomination / Value Selection -->
                        <div class="glass-form-group">
                            <label for="denomination">Select Card Value</label>
                            <select id="denomination" class="glass-select" name="denomination">
                                <option value="" disabled selected>-- Select an Option --</option>
                                <?php 
                                    // Admin sets the exact options in the database (e.g. "800 Robux, 1000 Robux, 2000 Robux")
                                    // The options are strictly dynamically loaded from the admin's configuration.
                                    $options = !empty($card['denominations']) ? explode(',', $card['denominations']) : [];
                                    
                                    if (count($options) > 0) {
                                        foreach ($options as $opt) {
                                            $opt = trim($opt);
                                            echo '<option value="' . htmlspecialchars($opt) . '">' . htmlspecialchars($opt) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="default">Standard Value</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <?php if ($isLoggedIn): ?>
                                <!-- User is logged in: Proceed to sell directly -->
                                <button type="button" id="sellNowBtn" class="btn btn-primary" onclick="proceedToSell()" style="display: none;">Sell Now</button>
                            <?php else: ?>
                                <!-- User is NOT logged in: Show Signup Modal -->
                                <button type="button" id="sellNowBtn" class="btn btn-primary" onclick="openSignupModal()" style="display: none;">Sell Now</button>
                            <?php endif; ?>
                            
                            <a href="index.php" class="btn btn-outline">Go Back</a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- Signup Required Modal (Only rendered if user is NOT logged in) -->
    <?php if ($card && !$isLoggedIn): ?>
    <div id="signup-modal" class="modal-overlay">
        <div class="modal-content glass-panel">
            <h3>Sign Up Required</h3>
            <p>You need to create an account to sell your <?= htmlspecialchars($card['brand_name']) ?> gift card. Join Roshan Ka Tech today!</p>
            <div class="modal-actions">
                <a href="../auth/signup.php?card_id=<?= htmlspecialchars($card['id']) ?>" class="btn btn-primary">Sign Up Now</a>
                <button type="button" class="btn btn-outline" onclick="closeSignupModal()">Cancel</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom" style="border-top: none; padding-top: 0;">
                <p>&copy; 2026 Roshan Ka Tech. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        // Logic to show "Sell Now" button only after a value is selected
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('denomination');
            const sellBtn = document.getElementById('sellNowBtn');
            
            if (selectElement && sellBtn) {
                selectElement.addEventListener('change', function() {
                    if (this.value !== "") {
                        sellBtn.style.display = 'inline-flex';
                    } else {
                        sellBtn.style.display = 'none';
                    }
                });
            }
        });

        // Modal & Action functions
        function openSignupModal() {
            const select = document.getElementById('denomination');
            // Check if user has selected an option before selling
            if (select && select.value === "") {
                alert("Please select a card value first.");
                return;
            }
            document.getElementById('signup-modal').classList.add('active');
        }

        function proceedToSell() {
            const select = document.getElementById('denomination');
            if (select && select.value === "") {
                alert("Please select a card value first.");
                return;
            }
            // If logged in, redirect directly to the selling dashboard/form
            // Sending the selected denomination as a URL parameter
            const val = encodeURIComponent(select.value);
            const cardId = "<?= $card ? htmlspecialchars($card['id']) : '' ?>";
            
            // Adjust this path to wherever your logged-in user dashboard/sell process is
            window.location.href = "../dashboard/sell-process.php?card_id=" + cardId + "&value=" + val;
        }

        function closeSignupModal() {
            document.getElementById('signup-modal').classList.remove('active');
        }

        // Close modal when clicking outside of it
        document.getElementById('signup-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeSignupModal();
            }
        });
    </script>
</body>
</html>