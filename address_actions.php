<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate CSRF
validateCSRFToken();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($action === 'add') {
        $data = [
            'title' => trim($_POST['title']),
            'address_line' => trim($_POST['address_line']),
            'city' => trim($_POST['city']),
            'phone' => trim($_POST['phone']),
            'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];
        
        if (addAddress($pdo, $user_id, $data)) {
            $_SESSION['success'] = "Address added successfully.";
        } else {
            $_SESSION['error'] = "Failed to add address.";
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['address_id'];
        if (deleteAddress($pdo, $id, $user_id)) {
            $_SESSION['success'] = "Address deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete address.";
        }
    }
}

// Redirect back
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: profile.php');
}
exit;
