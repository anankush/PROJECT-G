<?php
/**
 * Database connection for Roshan Ka Tech
 * Centralized DB connection to be used by public and admin panels.
 */

// Replace these with your actual database credentials when ready
$db_host = 'localhost';
$db_name = 'roshan_ka_tech';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch results as associative arrays by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fails gracefully if the database is not set up yet.
    // In a production environment, you might want to log this instead of silent failure.
    $pdo = null;
}
?>