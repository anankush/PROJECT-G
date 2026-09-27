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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($name && $email && $password) {
        if ($pdo) {
            try {
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $error = "Email address is already in use.";
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'user')");
                    if ($stmt->execute([$name, $email, $hash])) {
                        // Auto login after registration
                        $_SESSION['user_id'] = $pdo->lastInsertId();
                        $_SESSION['user_name'] = $name;
                        $_SESSION['user_role'] = 'user';
                        
                        header("Location: ../index.php");
                        exit;
                    } else {
                        $error = "Failed to register. Please try again.";
                    }
                }
            } catch (PDOException $e) {
                // If table doesn't exist, we can show a friendly message or we would auto-create it.
                $error = "Database error. Please ensure the database schema is imported.";
            }
        } else {
            $error = "Database connection error.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

// Background images generation
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
    <title>Register | Roshan Ka Tech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../landing/assets/css/style.css?v=2.1">
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
            max-width: 450px;
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
        .auth-links {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.95rem;
        }
        .auth-links a {
            color: var(--primary-color);
            font-weight: 600;
            transition: var(--transition);
        }
        .auth-links a:hover {
            color: var(--primary-hover);
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
        <div class="auth-card reveal active">
            <div class="text-center mb-4">
                <a href="../index.php" class="logo" style="font-size: 1.5rem;">Roshan Ka Tech</a>
            </div>
            <h2>Create Account</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="glass-form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Full Name</label>
                    <input type="text" name="name" class="glass-select" placeholder="Enter your full name" style="width: 100%;" required>
                </div>
                <div class="glass-form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Email Address</label>
                    <input type="email" name="email" class="glass-select" placeholder="Enter your email" style="width: 100%;" required>
                </div>
                <div class="glass-form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Password</label>
                    <input type="password" name="password" class="glass-select" placeholder="Create a password" style="width: 100%;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">Sign Up</button>
            </form>

            <div class="auth-links">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <script src="../landing/assets/js/main.js?v=2.1"></script>
</body>
</html>