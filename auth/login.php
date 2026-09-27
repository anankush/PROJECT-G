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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    if ($user['role'] === 'superadmin') {
                        header("Location: ../super/index.php");
                    } elseif ($user['role'] === 'admin') {
                        header("Location: ../admin/index.php");
                    } else {
                        header("Location: ../index.php");
                    }
                    exit;
                } else {
                    $error = "Invalid email or password.";
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
    <title>Login | Roshan Ka Tech</title>
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
            <h2>Welcome Back</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="glass-form-group" style="margin-bottom: 1.25rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Email Address</label>
                    <input type="email" name="email" class="glass-select" placeholder="Enter your email" style="width: 100%;" required>
                </div>
                <div class="glass-form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Password</label>
                    <input type="password" name="password" class="glass-select" placeholder="Enter your password" style="width: 100%;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem;">Login</button>
            </form>

            <div class="auth-links">
                <p>Don't have an account? <a href="register.php">Sign up here</a></p>
            </div>
        </div>
    </div>

    <script src="../landing/assets/js/main.js?v=2.1"></script>
</body>
</html>