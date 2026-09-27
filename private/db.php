<?php
/**
 * Database connection for Roshan Ka Tech
 * Centralized DB connection to be used by public and admin panels.
 */

// Local fallback credentials
$db_host = 'localhost';
$db_name = 'roshan_ka_tech';
$db_user = 'root';
$db_pass = '';
$google_script_url = '';

// Load production secrets if created by GitHub Actions
if (file_exists(__DIR__ . '/secrets.php')) {
    require_once __DIR__ . '/secrets.php';
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pdo = null;
}

/**
 * Centralized Email Sender
 * Uses Google Apps Script if URL is provided, otherwise falls back to native mail()
 */
function send_google_mail($to, $subject, $body) {
    global $google_script_url;
    
    if (empty($google_script_url)) {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Roshan Ka Tech <noreply@roshankatech.com>\r\n";
        return @mail($to, $subject, $body, $headers);
    }
    
    $ch = curl_init($google_script_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'email' => $to,
        'subject' => $subject,
        'body' => $body
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

function get_email_template($name, $otp) {
    return '
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 30px; background-color: #f4f7fb; border-radius: 12px; border: 1px solid #e2e8f0;">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #2563eb; margin: 0; font-size: 28px;">Roshan Ka Tech</h2>
        </div>
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <h3 style="color: #1e293b; margin-top: 0; font-size: 20px;">Hello ' . htmlspecialchars($name) . ',</h3>
            <p style="color: #475569; font-size: 16px; line-height: 1.6;">Your verification code is ready. Please use the 6-digit OTP below to securely access your account.</p>
            <div style="text-align: center; margin: 35px 0;">
                <span style="display: inline-block; background: #e0e7ff; color: #1d4ed8; font-size: 36px; font-weight: bold; padding: 15px 30px; border-radius: 8px; letter-spacing: 8px;">' . $otp . '</span>
            </div>
            <p style="color: #475569; font-size: 14px; text-align: center; margin-bottom: 0;">Please do not share this OTP with anyone.</p>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 13px;">
            &copy; ' . date("Y") . ' Roshan Ka Tech. All rights reserved.
        </div>
    </div>';
}
?>
