<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Initialize Cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF for any POST action
    validateCSRFToken();

    $action = $_POST['action'] ?? '';
    $product_id = $_POST['product_id'] ?? 0;
    
    if ($action === 'add') {
        $quantity = (int)($_POST['quantity'] ?? 1);
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$product_id]);
    } elseif ($action === 'apply_coupon') {
        $code = strtoupper(trim($_POST['coupon_code']));
        require_once 'includes/functions.php'; // Ensure functions are loaded
        $coupon = getCoupon($pdo, $code);
        if ($coupon) {
            $_SESSION['coupon'] = $coupon;
        } else {
            $_SESSION['coupon_error'] = "Invalid coupon code";
        }
    } elseif ($action === 'remove_coupon') {
        unset($_SESSION['coupon']);
    }
    
    // Redirect back to avoid resubmission
    header("Location: cart.php");
    exit;
}

require_once 'includes/header.php';

// Fetch Cart Products
$cart_items = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    // Check if ids string is not empty to avoid SQL error
    if($ids) {
        $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
        $products = $stmt->fetchAll();
        
        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $subtotal = $p['price'] * $qty;
            $total_price += $subtotal;
            $p['qty'] = $qty;
            $p['subtotal'] = $subtotal;
            $cart_items[] = $p;
        }
    }
}

// Calculate Discount
$discount_amount = 0;
if (isset($_SESSION['coupon'])) {
    $coupon = $_SESSION['coupon'];
    if ($coupon['discount_type'] === 'percentage') {
        $discount_amount = $total_price * ($coupon['discount_value'] / 100);
    } else {
        $discount_amount = $coupon['discount_value'];
    }
}
$final_total = max(0, $total_price - $discount_amount);
?>

<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-arabic"><?php echo __('cart'); ?></h1>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-20 bg-white rounded-2xl shadow-sm">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-cart-arrow-down text-3xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Your Cart is Empty</h3>
                <p class="text-gray-500 mb-8">It looks like you haven't added anything yet.</p>
                <a href="shop.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full text-white bg-primary hover:bg-gray-800 transition">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-1 space-y-4">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                            <?php 
                                $imgSrc = strpos($item['image'], 'http') === 0 ? $item['image'] : 'assets/images/' . $item['image']; 
                            ?>
                            <img src="<?php echo $imgSrc; ?>" class="w-20 h-20 object-cover rounded-lg bg-gray-100">
                            
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900"><?php echo $item['name_' . $current_lang]; ?></h3>
                                <p class="text-gray-500 text-sm">$<?php echo $item['price']; ?></p>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="font-bold text-gray-900 px-3 py-1 bg-gray-50 rounded-lg">
                                    x<?php echo $item['qty']; ?>
                                </div>
                                <div class="font-bold text-primary">
                                    $<?php echo number_format($item['subtotal'], 2); ?>
                                </div>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition p-2">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Summary -->
                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="font-bold text-gray-900 mb-6 text-lg">Order Summary</h3>
                        
                        <!-- Coupon Input -->
                        <form action="cart.php" method="POST" class="mb-6">
                            <?php if (isset($_SESSION['coupon'])): ?>
                                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center gap-2 text-green-700">
                                        <i class="fa-solid fa-tag"></i>
                                        <span class="font-bold"><?php echo $_SESSION['coupon']['code']; ?></span>
                                    </div>
                                    <input type="hidden" name="action" value="remove_coupon">
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="flex gap-2">
                                    <input type="hidden" name="action" value="apply_coupon">
                                    <input type="text" name="coupon_code" placeholder="Enter coupon code" class="flex-1 rounded-lg border-gray-200 focus:ring-accent focus:border-accent text-sm" required>
                                    <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-gray-800 transition">Apply</button>
                                </div>
                                <?php if (isset($_SESSION['coupon_error'])): ?>
                                    <p class="text-red-500 text-xs mt-2"><?php echo $_SESSION['coupon_error']; unset($_SESSION['coupon_error']); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            
                            <?php if ($discount_amount > 0): ?>
                            <div class="flex justify-between text-green-600 font-medium">
                                <span>Discount</span>
                                <span>-$<?php echo number_format($discount_amount, 2); ?></span>
                            </div>
                            <?php endif; ?>

                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                                <span>Total</span>
                                <span>$<?php echo number_format($final_total, 2); ?></span>
                            </div>
                        </div>

                        <a href="checkout.php" class="block w-full text-center bg-accent text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            <?php echo __('checkout'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
