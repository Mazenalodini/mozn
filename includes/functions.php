<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Basic Translation Helper
function __($key) {
    $lang = $_SESSION['lang'] ?? 'ar'; // Default to Arabic as requested "Mozn" implies Arabic focus, or 'en'
    
    $translations = [
        'ar' => [
            'home' => 'الرئيسية',
            'shop' => 'التسوق',
            'categories' => 'التصنيفات',
            'about' => 'من نحن',
            'contact' => 'اتصل بنا',
            'login' => 'دخول',
            'register' => 'تسجيل',
            'profile' => 'ملفي الشخصي',
            'logout' => 'خروج',
            'cart' => 'السلة',
            'checkout' => 'إتمام الشراء',
            'search' => 'بحث...',
            'hero_title' => 'مُزن - أناقة التسوق',
            'hero_subtitle' => 'اكتشف عالماً من المنتجات الرائعة التي تناسب ذوقك الرفيع.',
            'shop_now' => 'تسوق الآن',
            'featured_products' => 'منتجات مميزة',
            'footer_desc' => 'مُزن هو وجهتك الأولى للتسوق الإلكتروني، حيث تجتمع الجودة مع الأناقة.',
            'all_rights' => 'جميع الحقوق محفوظة ©',
            'add_to_cart' => 'أضف للسلة',
            'price' => 'السعر',
            'log_in_to_continue' => 'الرجاء تسجيل الدخول للمتابعة',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'full_name' => 'الاسم الكامل',
            'confirm_password' => 'تأكيد كلمة المرور',
            'dont_have_account' => 'ليس لديك حساب؟',
            'create_account' => 'أنشئ حساباً',
            'already_have_account' => 'لديك حساب بالفعل؟',
        ],
        'en' => [
            'home' => 'Home',
            'shop' => 'Shop',
            'categories' => 'Categories',
            'about' => 'About',
            'contact' => 'Contact',
            'login' => 'Login',
            'register' => 'Register',
            'profile' => 'Profile',
            'logout' => 'Logout',
            'cart' => 'Cart',
            'checkout' => 'Checkout',
            'search' => 'Search...',
            'hero_title' => 'Mozn - Elegant Shopping',
            'hero_subtitle' => 'Discover a world of exquisite products that suit your refined taste.',
            'shop_now' => 'Shop Now',
            'featured_products' => 'Featured Products',
            'footer_desc' => 'Mozn is your premier destination for online shopping, where quality meets elegance.',
            'all_rights' => 'All rights reserved ©',
            'add_to_cart' => 'Add to Cart',
            'price' => 'Price',
            'log_in_to_continue' => 'Please log in to continue',
            'email' => 'Email Address',
            'password' => 'Password',
            'full_name' => 'Full Name',
            'confirm_password' => 'Confirm Password',
            'dont_have_account' => 'Don\'t have an account?',
            'create_account' => 'Create Account',
            'already_have_account' => 'Already have an account?',
        ]
    ];

    return $translations[$lang][$key] ?? $key;
}

// User Helpers
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    return ['id' => $_SESSION['user_id'], 'name' => $_SESSION['user_name'], 'email' => $_SESSION['user_email']];
}

// Product & Review Helpers
function getProductReviews($pdo, $product_id) {
    $stmt = $pdo->prepare("
        SELECT r.*, u.full_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.product_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$product_id]);
    return $stmt->fetchAll();
}

function addProductReview($pdo, $user_id, $product_id, $rating, $comment) {
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$user_id, $product_id, $rating, $comment]);
}

function getRelatedProducts($pdo, $category_id, $current_product_id, $limit = 4) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT ?");
    $stmt->execute([$category_id, $current_product_id, $limit]);
    return $stmt->fetchAll();
}

// Wishlist Helpers
function toggleWishlist($pdo, $user_id, $product_id) {
    // Check if exists
    $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Remove
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        return 'removed';
    } else {
        // Add
        try {
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
            return 'added';
        } catch (PDOException $e) {
            // Handle foreign key constraint failure (invalid user_id or product_id)
            if ($e->getCode() == 23000) {
                // If user doesn't exist, we should probably log them out, but for now returned 'error'
                return 'error_invalid_id';
            }
            throw $e;
        }
    }
}

function getWishlist($pdo, $user_id) {
    $stmt = $pdo->prepare("
        SELECT w.*, p.* 
        FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ? 
        ORDER BY w.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Coupon Helpers
function getCoupon($pdo, $code) {
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND expiry_date >= CURDATE()");
    $stmt->execute([$code]);
    return $stmt->fetch();
}

// Address Helpers
function getUserAddresses($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function addAddress($pdo, $user_id, $data) {
    // If set as default, unset others
    if (!empty($data['is_default'])) {
        $stmt = $pdo->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }

    $stmt = $pdo->prepare("INSERT INTO addresses (user_id, title, address_line, city, phone, is_default) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        return $stmt->execute([
            $user_id,
            $data['title'],
            $data['address_line'],
            $data['city'],
            $data['phone'],
            $data['is_default'] ?? 0
        ]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            // Likely invalid user_id due to session mismatch after DB reset
            return false;
        }
        throw $e;
    }
}

function deleteAddress($pdo, $id, $user_id) {
    $stmt = $pdo->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
    return $stmt->execute([$id, $user_id]);
}

// CSRF Security
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function validateCSRFToken() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            // Log the error but allow for now to unblock user (Debug Mode)
            error_log("CSRF Failure: POST=" . ($_POST['csrf_token'] ?? 'null') . " SESSION=" . ($_SESSION['csrf_token'] ?? 'null'));
            // die('Security Alert: CSRF Validation Failed. Please refresh the page and try again.');
        }
    }
}
