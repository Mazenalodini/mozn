<?php
/**
 * Main Configuration File (Example)
 * 
 * Instructions:
 * 1. Copy this file to config.php
 * 2. Update the DB_HOST, DB_USER, DB_PASS, DB_NAME constants with your database details.
 */

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mozn_db');

// App Settings
define('APP_NAME', 'Mozn');
define('APP_URL', 'http://localhost/mozn');

// Environment Config ('local' or 'production')
define('APP_ENV', 'local');

if (APP_ENV === 'production') {
    // Hide errors from users
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    
    // Log errors instead
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error_log.txt');
} else {
    // Show errors for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
