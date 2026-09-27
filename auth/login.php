<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../private/db.php';

// If already logged in, redirect home
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';
$step = $_SESSION['login_step'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if ($identifier && $password) {
            if (isset($pdo) && $pdo) {
                try {
                    // Check by email OR phone
                    $stmt = $pdo->prepare("SELECT id, name, email, phone, password_hash, role FROM users WHERE email = ? OR phone = ? LIMIT 1");
                    $stmt->execute([$identifier, $identifier]);
                    $user = $stmt->fetch();
                    
                    if ($user && password_verify($password, $user['password_hash'])) {
                        if ($user['role'] !== 'user') {
                            $error = "Access denied. Admins must log in through the admin portal.";
                        } else {
                            $otp = rand(100000, 999999);
                            $expires_at = date('Y-m-d H:i:s', time() + 120); // exactly 2 mins
                            
                            // Delete old OTPs for this email to maintain 1 active OTP
                            $stmt = $pdo->prepare("DELETE FROM otps WHERE email = ? AND type = 'login'");
                            $stmt->execute([$user['email']]);
                            
                            // Insert into Database
                            $stmt = $pdo->prepare("INSERT INTO otps (email, otp_code, type, expires_at) VALUES (?, ?, 'login', ?)");
                            $stmt->execute([$user['email'], $otp, $expires_at]);
                            
                            $_SESSION['pending_user'] = [
                                'id' => $user['id'],
                                'name' => $user['name'],
                                'email' => $user['email'],
                                'role' => $user['role']
                            ];
                            
                            // Send Email OTP
                            $subject = "Your Login Verification OTP - Roshan Ka Tech";
                            $message = get_email_template($user['name'], $otp);
                            $mail_status = send_google_mail($user['email'], $subject, $message);
                            
                            if ($mail_status === true) {
                                $_SESSION['login_step'] = 2;
                                $step = 2;
                                $success = "OTP has been sent to your registered email address.";
                            } else {
                                $error = "Failed to send OTP: " . (is_string($mail_status) ? $mail_status : "Unknown configuration error.");
                            }
                        }
                    } else {
                        $error = "Invalid credentials. Please try again.";
                    }
                } catch (PDOException $e) {
                    $error = "Database error. Please try again later.";
                }
            } else {
                $error = "Database connection error.";
            }
        } else {
            $error = "Please fill in all fields.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
        $user_otp = trim($_POST['otp'] ?? '');
        $u = $_SESSION['pending_user'] ?? [];
        $email = $u['email'] ?? '';
        
        if ($email) {
            try {
                // Verify OTP is correct and NOT expired
                $stmt = $pdo->prepare("SELECT id FROM otps WHERE email = ? AND otp_code = ? AND type = 'login' AND expires_at >= NOW()");
                $stmt->execute([$email, $user_otp]);
                
                if ($stmt->fetch()) {
                    // OTP is Valid - Delete it IMMEDIATELY so it can't be reused
                    $stmt = $pdo->prepare("DELETE FROM otps WHERE email = ? AND type = 'login'");
                    $stmt->execute([$email]);
                    
                    // Set final session variables
                    $_SESSION['user_id'] = $u['id'];
                    $_SESSION['user_name'] = $u['name'];
                    $_SESSION['user_role'] = $u['role'];
                    
                    // Clear pending login data
                    unset($_SESSION['pending_user'], $_SESSION['login_step']);
                    
                    header("Location: ../index.php");
                    exit;
                } else {
                    $error = "Invalid or Expired OTP.";
                }
            } catch (PDOException $e) {
                $error = "Database error during login.";
            }
        } else {
            $error = "Session expired. Please login again.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'resend_otp') {
        $u = $_SESSION['pending_user'] ?? [];
        $email = $u['email'] ?? '';
        
        if ($email) {
            $otp = rand(100000, 999999);
            $expires_at = date('Y-m-d H:i:s', time() + 120); // exactly 2 mins
            
            // Delete old, insert new
            $stmt = $pdo->prepare("DELETE FROM otps WHERE email = ? AND type = 'login'");
            $stmt->execute([$email]);
            $stmt = $pdo->prepare("INSERT INTO otps (email, otp_code, type, expires_at) VALUES (?, ?, 'login', ?)");
            $stmt->execute([$email, $otp, $expires_at]);
            
            $subject = "Your New Login Verification OTP - Roshan Ka Tech";
            $message = get_email_template($u['name'], $otp);
            $mail_status = send_google_mail($email, $subject, $message);
            
            if ($mail_status !== true) {
                $error = "Failed to resend OTP: " . (is_string($mail_status) ? $mail_status : "Unknown error.");
            } else {
                $success = "A new OTP has been sent to your registered email address.";
            }
        } else {
            // Session expired or invalid
            unset($_SESSION['login_step'], $_SESSION['pending_user']);
            $step = 1;
            $error = "Session expired. Please login again.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'cancel_login') {
        unset($_SESSION['login_step'], $_SESSION['pending_user']);
        $step = 1;
    }
}

// Background images generation for the visual theme
$bgImages = [];
$imageDir = __DIR__ . '/../landing/images/';
if (is_dir($imageDir)) {
    $files = scandir($imageDir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'svg', 'webp', 'gif'])) {
            $bgImages[] = '../landing/images/' . $file;
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
    return str_repeat($html, 4);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Roshan Ka Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../landing/assets/css/style.css?v=2.6">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }
        .auth-card {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: var(--glass-border);
            box-shadow: var(--glass-shadow);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
        }
        .auth-card h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-color);
            font-size: 2rem;
        }
        .error-message {
            background: rgba(255, 0, 0, 0.1);
            color: #d32f2f;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
            border: 1px solid rgba(255, 0, 0, 0.2);
        }
        .success-message {
            background: rgba(0, 128, 0, 0.1);
            color: #2e7d32;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 500;
            border: 1px solid rgba(0, 128, 0, 0.2);
        }
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.95rem;
        }
        .auth-links a, .auth-links button {
            color: var(--primary-color);
            font-weight: 600;
            transition: var(--transition);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
        }
        .auth-links a:hover, .auth-links button:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        .toggle-password:hover {
            color: var(--primary-color);
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
        }
        .checkbox-container input[type="checkbox"] {
            accent-color: var(--primary-color);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        .countdown-timer {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #d32f2f;
            margin-bottom: 1.5rem;
            background: rgba(255, 0, 0, 0.05);
            padding: 0.75rem;
            border-radius: 8px;
            border: 1px dashed rgba(211, 47, 47, 0.3);
        }
        .btn-disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
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
    </div>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="auth-container">
        <a href="../index.php" class="back-btn-glass">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Home
        </a>
        
        <div class="auth-card reveal active">
            <div class="text-center mb-4">
                <a href="../index.php" class="logo" style="font-size: 1.5rem;">Roshan Ka Tech</a>
            </div>
            
            <?php if ($step === 1): ?>
                <h2>Welcome Back</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="glass-form-group" style="margin-bottom: 1.25rem;">
                        <label for="identifier" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Email or Mobile Number</label>
                        <input type="text" id="identifier" name="identifier" class="glass-select" placeholder="Enter your email or phone" style="width: 100%;" required>
                    </div>
                    
                    <div class="glass-form-group" style="margin-bottom: 1.25rem;">
                        <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="glass-select" placeholder="Enter your password" style="width: 100%; padding-right: 2.8rem;" required>
                            <button type="button" id="togglePassword" class="toggle-password" aria-label="Toggle password visibility">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg id="eye-off-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember" id="remember">
                            Remember me
                        </label>
                        <a href="forgot-password.php" style="font-size: 0.9rem; color: var(--primary-color); font-weight: 600; text-decoration: none; transition: var(--transition);">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">Login</button>
                </form>

                <div class="auth-links">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>

            <?php else: ?>
                <!-- OTP Verification Step -->
                <h2>Verify Login</h2>
                <p style="text-align: center; color: var(--text-light); margin-bottom: 1.5rem;">
                    We've sent a 6-digit OTP to your email <strong><?= htmlspecialchars($_SESSION['pending_user']['email'] ?? '') ?></strong>.
                </p>
                
                <div class="countdown-timer" id="countdown">02:00</div>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="verify_otp">
                    <div class="glass-form-group" style="margin-bottom: 2rem;">
                        <label for="otp" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark); text-align: center;">Enter OTP</label>
                        <input type="text" id="otp-input" name="otp" class="glass-select" placeholder="e.g. 123456" style="width: 100%; text-align: center; font-size: 1.2rem; letter-spacing: 2px;" required maxlength="6">
                    </div>
                    <button type="submit" id="verify-btn" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">Verify & Login</button>
                </form>

                <div class="auth-links" style="display: flex; justify-content: space-between; margin-top: 2rem;">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="cancel_login">
                        <button type="submit">Back to Login</button>
                    </form>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="resend_otp">
                        <button type="submit" id="resend-btn" class="btn-disabled">Resend OTP (Wait)</button>
                    </form>
                </div>
                
                <script>
                    let timeInSecs = 120;
                    let timer = setInterval(function() {
                        timeInSecs--;
                        let m = Math.floor(timeInSecs / 60).toString().padStart(2, '0');
                        let s = (timeInSecs % 60).toString().padStart(2, '0');
                        document.getElementById('countdown').innerText = m + ":" + s;
                        
                        if (timeInSecs <= 0) {
                            clearInterval(timer);
                            document.getElementById('countdown').innerText = "OTP Expired!";
                            document.getElementById('verify-btn').classList.add('btn-disabled');
                            document.getElementById('otp-input').disabled = true;
                            
                            // Enable Resend Button
                            let resendBtn = document.getElementById('resend-btn');
                            resendBtn.classList.remove('btn-disabled');
                            resendBtn.innerText = 'Resend OTP';
                        }
                    }, 1000);
                </script>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Password Show/Hide Toggle Logic
        const toggleBtn = document.getElementById('togglePassword');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeOffIcon = document.getElementById('eye-off-icon');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                }
            });
        }
    </script>
    <script src="../landing/assets/js/main.js?v=2.6"></script>
</body>
</html>