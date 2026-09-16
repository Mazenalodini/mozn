<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $user_id = $_SESSION['user_id'];
    
    toggleWishlist($pdo, $user_id, $product_id);
    
    // Redirect back
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: shop.php');
    }
    exit;
}

header('Location: shop.php');
exit;
