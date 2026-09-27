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
        return @mail($to, $subject, $body, "From: noreply@roshankatech.com");
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
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>