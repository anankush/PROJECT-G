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

// Include mail functions separately to keep this file clean
require_once __DIR__ . '/mail.php';
?>
