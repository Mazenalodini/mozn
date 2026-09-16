<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='shop.php';</script>";
    exit;
}

// Calculate Total Again for Security
$total_price = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll();
$cart_items_data = [];
foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p['id']];
    $total_price += $p['price'] * $qty;
    $cart_items_data[] = ['product' => $p, 'qty' => $qty];
}

// Handle Order Placement
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCSRFToken();
    $address_text = '';
    $phone_text = '';

    if (isset($_POST['address_id']) && $_POST['address_id'] !== 'new') {
        // Fetch selected address
        require_once 'includes/functions.php'; // Ensure loaded
        $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
        $stmt->execute([$_POST['address_id'], $_SESSION['user_id']]);
        $saved_addr = $stmt->fetch();
        if ($saved_addr) {
            $address_text = $saved_addr['title'] . ": " . $saved_addr['address_line'] . ", " . $saved_addr['city'];
            $phone_text = $saved_addr['phone'];
        }
    } else {
        $address_text = $_POST['address'] ?? '';
        $phone_text = $_POST['phone'] ?? '';
    }

    try {
        $pdo->beginTransaction();

        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address, phone) VALUES (?, ?, 'pending', ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total_price, $address_text, $phone_text]);
        $order_id = $pdo->lastInsertId();

        // 2. Create Order Items
        $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items_data as $item) {
            $item_stmt->execute([
                $order_id,
                $item['product']['id'],
                $item['qty'],
                $item['product']['price']
            ]);
        }

        $pdo->commit();
        
        // 3. Send Order Confirmation Email
        require_once 'includes/mail.php';
        $user_email = $_SESSION['user_email'] ?? 'customer@example.com';
        $subject = "Order Confirmation #$order_id - Mozn";
        $body = "Dear Customer,\n\n";
        $body .= "Thank you for shopping with Mozn! Your order #$order_id has been received.\n";
        $body .= "Total Amount: $" . number_format($total_price, 2) . "\n\n";
        $body .= "We will notify you when your items are shipped.\n\n";
        $body .= "Best Regards,\nMozn Team";
        
        sendMail($user_email, $subject, $body);

        // 4. Clear Cart
        unset($_SESSION['cart']);
        unset($_SESSION['coupon']); // Also clear used coupon

        // 5. Redirect
        header("Location: order_success.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to place order. Please try again.";
    }
}

require_once 'includes/header.php';
?>

<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-arabic text-center"><?php echo __('checkout'); ?></h1>
        
        <?php if($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-center border border-red-100"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Shipping Info -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
                <h3 class="font-bold text-gray-900 mb-4 text-lg">Shipping Information</h3>
                
                <?php 
                require_once 'includes/functions.php';
                $saved_addresses = getUserAddresses($pdo, $_SESSION['user_id']);
                ?>

                <form id="checkout-form" method="POST" x-data="{ addressMode: '<?php echo !empty($saved_addresses) ? 'saved' : 'new'; ?>' }">
                    <?php echo csrfField(); ?>
                    
                    <?php if (!empty($saved_addresses)): ?>
                        <div class="mb-4 space-y-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Saved Address:</label>
                            <?php foreach ($saved_addresses as $index => $addr): ?>
                            <label class="flex items-start p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition <?php echo $index === 0 ? 'ring-1 ring-blue-500 border-blue-500 bg-blue-50' : ''; ?>" :class="{ 'ring-1 ring-blue-500 border-blue-500 bg-blue-50': addressMode === '<?php echo $addr['id']; ?>' }">
                                <input type="radio" name="address_id" value="<?php echo $addr['id']; ?>" class="mt-1 text-primary focus:ring-primary" <?php echo $index === 0 ? 'checked' : ''; ?> @click="addressMode = '<?php echo $addr['id']; ?>'">
                                <div class="ml-3">
                                    <span class="block text-sm font-bold text-gray-900"><?php echo htmlspecialchars($addr['title']); ?></span>
                                    <span class="block text-xs text-gray-500"><?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['address_line']); ?></span>
                                    <span class="block text-xs text-gray-400"><?php echo htmlspecialchars($addr['phone']); ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                            
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition" :class="{ 'ring-1 ring-blue-500 border-blue-500 bg-blue-50': addressMode === 'new' }">
                                <input type="radio" name="address_id" value="new" class="mt-1 text-primary focus:ring-primary" @click="addressMode = 'new'">
                                <span class="ml-3 text-sm font-bold text-gray-900">Use a different address</span>
                            </label>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="address_id" value="new">
                    <?php endif; ?>

                    <div x-show="addressMode === 'new'" class="space-y-4 pt-4 border-t border-gray-100" <?php echo !empty($saved_addresses) ? 'style="display: none;"' : ''; ?>>
                        <h4 class="text-sm font-bold text-gray-900">New Address Details</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" value="<?php echo $_SESSION['user_name']; ?>" class="w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 px-4 py-2 focus:ring-accent focus:border-accent" placeholder="Enter your full address"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone" class="w-full rounded-lg border-gray-300 px-4 py-2 focus:ring-accent focus:border-accent" placeholder="05xxxxxxxx">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Payment & Summary -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Payment Method</h3>
                    <div class="flex items-center gap-3 p-3 border border-accent bg-blue-50 rounded-lg">
                        <i class="fa-solid fa-money-bill-wave text-accent text-xl"></i>
                        <span class="font-medium text-blue-900">Cash on Delivery</span>
                        <i class="fa-solid fa-circle-check text-accent ml-auto"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Order Summary</h3>
                    <div class="space-y-2 mb-4 text-sm text-gray-600">
                        <?php foreach ($cart_items_data as $item): ?>
                            <div class="flex justify-between">
                                <span><?php echo $item['product']['name_' . $current_lang]; ?> (x<?php echo $item['qty']; ?>)</span>
                                <span>$<?php echo number_format($item['product']['price'] * $item['qty'], 2); ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Coupon Display in Checkout -->
                        <?php if (isset($_SESSION['coupon'])): ?>
                            <div class="flex justify-between text-green-600 font-medium py-2 border-t border-dashed border-gray-200 mt-2">
                                <span>Discount (<?php echo $_SESSION['coupon']['code']; ?>)</span>
                                <span>-$<?php
                                    $discount_amount = 0;
                                    if ($_SESSION['coupon']['discount_type'] === 'percentage') {
                                        $discount_amount = $total_price * ($_SESSION['coupon']['discount_value'] / 100);
                                    } else {
                                        $discount_amount = $_SESSION['coupon']['discount_value'];
                                    }
                                    echo number_format($discount_amount, 2);
                                ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Re-Calculate Final Total with Discount for Display -->
                    <?php
                        $discount = 0;
                        if (isset($_SESSION['coupon'])) {
                            if ($_SESSION['coupon']['discount_type'] === 'percentage') {
                                $discount = $total_price * ($_SESSION['coupon']['discount_value'] / 100);
                            } else {
                                $discount = $_SESSION['coupon']['discount_value'];
                            }
                        }
                        $final_display_total = max(0, $total_price - $discount);
                    ?>

                    <div class="border-t border-gray-100 pt-4 flex justify-between font-bold text-xl text-gray-900">
                        <span>Total</span>
                        <span>$<?php echo number_format($final_display_total, 2); ?></span>
                    </div>
                    
                    <button onclick="document.getElementById('checkout-form').submit();" class="mt-6 w-full block text-center bg-primary text-white py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-lg transform hover:-translate-y-0.5">
                        Place Order
                    </button>
                    <a href="cart.php" class="block text-center text-gray-500 text-sm mt-4 hover:underline">Back to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
