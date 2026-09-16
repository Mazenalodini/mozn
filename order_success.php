<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple check so users don't access this page directly easily unless they know the URL
// In a real app, we'd check a flash session variable.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Placed! - Mozn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="text-center bg-white p-12 rounded-3xl shadow-xl border border-gray-100 max-w-lg w-full transform transition-all duration-500 hover:scale-105">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-check text-5xl text-green-500"></i>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-900 mb-2 font-['Inter']">Thank You!</h1>
        <h2 class="text-xl text-gray-500 font-medium mb-8">Your order has been placed successfully.</h2>
        
        <p class="text-gray-400 text-sm mb-8 px-8">
            We've received your order and are getting it ready to be shipped. You can track your order status in your profile.
        </p>

        <div class="space-y-4">
            <a href="profile.php" class="block w-full bg-gray-900 text-white py-4 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                View My Orders
            </a>
            <a href="shop.php" class="block w-full text-gray-500 font-bold hover:text-gray-800 transition">
                Continue Shopping <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <script>
        // Trigger confetti
        confetti({
            particleCount: 150,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#000000', '#3B82F6', '#10B981']
        });
    </script>

</body>
</html>
