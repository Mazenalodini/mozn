<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

// Fetch User Orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8 font-arabic"><?php echo __('profile'); ?></h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar / User Info -->
            <div class="col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center sticky top-24">
                    <div class="relative inline-block mb-4">
                        <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['user_name']; ?>&background=random&size=128" alt="Profile" class="w-24 h-24 rounded-full border-4 border-white shadow-lg mx-auto">
                        <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-white"></div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900"><?php echo $_SESSION['user_name']; ?></h2>
                    <p class="text-gray-500 text-sm mb-6"><?php echo $_SESSION['user_email']; ?></p>
                    
                    <a href="logout.php" class="block w-full py-2 px-4 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition text-sm font-bold">
                        <?php echo __('logout'); ?>
                    </a>
                </div>
            </div>

            <!-- Main Content (Tabs) -->
            <div class="col-span-1 lg:col-span-3" x-data="{ tab: 'orders' }">
                <!-- Tabs Nav -->
                <div class="flex space-x-4 mb-6 border-b border-gray-200">
                    <button @click="tab = 'orders'" :class="{ 'border-primary text-primary': tab === 'orders', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'orders' }" class="pb-4 px-2 border-b-2 font-bold transition">
                        Order History
                    </button>
                    <button @click="tab = 'wishlist'" :class="{ 'border-primary text-primary': tab === 'wishlist', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'wishlist' }" class="pb-4 px-2 border-b-2 font-bold transition">
                        My Wishlist
                    </button>
                    <button @click="tab = 'addresses'" :class="{ 'border-primary text-primary': tab === 'addresses', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'addresses' }" class="pb-4 px-2 border-b-2 font-bold transition">
                        Addresses
                    </button>
                </div>

                <!-- Orders Tab -->
                <div x-show="tab === 'orders'" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Your Orders</h3>
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="p-12 text-center">
                            <i class="fa-solid fa-receipt text-4xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500">You haven't placed any orders yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">Order ID</th>
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($orders as $order): ?>
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4 font-bold text-primary">#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-6 py-4 text-gray-600"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                                <?php 
                                                    echo $order['status'] === 'completed' ? 'bg-green-100 text-green-700' : 
                                                        ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'); 
                                                ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-gray-900">$<?php echo $order['total_amount']; ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <button class="text-accent hover:text-blue-700 font-medium">View Details</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Wishlist Tab -->
                <div x-show="tab === 'wishlist'" style="display: none;">
                    <?php 
                    $wishlist_items = getWishlist($pdo, $_SESSION['user_id']); 
                    ?>
                    
                    <?php if (empty($wishlist_items)): ?>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <i class="fa-regular fa-heart text-4xl text-gray-200 mb-4"></i>
                            <p class="text-gray-500 mb-4">Your wishlist is empty.</p>
                            <a href="shop.php" class="inline-block bg-primary text-white px-6 py-2 rounded-full font-bold text-sm">Start Shopping</a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($wishlist_items as $item): ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition">
                                <div class="relative h-48 bg-gray-100">
                                    <img src="<?php echo strpos($item['image'], 'http') === 0 ? $item['image'] : 'assets/images/' . $item['image']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <a href="wishlist.php?product_id=<?php echo $item['id']; ?>" class="absolute top-2 right-2 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm" title="Remove">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-900 truncate mb-1"><?php echo $item['name_' . $current_lang]; ?></h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-accent font-bold">$<?php echo $item['price']; ?></span>
                                        <a href="product.php?id=<?php echo $item['id']; ?>" class="text-primary text-sm font-medium hover:underline">View</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Addresses Tab -->
                <div x-show="tab === 'addresses'" style="display: none;">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900">My Addresses</h3>
                            <button @click="$refs.addressForm.classList.toggle('hidden')" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-accent transition">
                                <i class="fa-solid fa-plus mr-2"></i> Add New
                            </button>
                        </div>

                        <!-- Add Address Form -->
                        <div x-ref="addressForm" class="hidden mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <form action="address_actions.php" method="POST" class="mt-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="add">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="title" placeholder="Address Title (e.g. Home, Work)" class="w-full rounded-lg border-gray-200" required>
                                    <input type="text" name="city" placeholder="City" class="w-full rounded-lg border-gray-200" required>
                                </div>
                                <input type="text" name="address_line" placeholder="Full Address Details" class="w-full rounded-lg border-gray-200" required>
                                <input type="text" name="phone" placeholder="Phone Number" class="w-full rounded-lg border-gray-200" required>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="is_default" id="is_default" class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <label for="is_default" class="text-sm text-gray-700">Set as default address</label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="$refs.addressForm.classList.add('hidden')" class="px-4 py-2 text-gray-500 font-bold hover:text-gray-700">Cancel</button>
                                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Save Address</button>
                                </div>
                            </form>
                        </div>

                        <!-- Address List -->
                        <?php 
                        $addresses = getUserAddresses($pdo, $_SESSION['user_id']); 
                        if (empty($addresses)): 
                        ?>
                            <div class="text-center py-12 text-gray-500">
                                <i class="fa-solid fa-map-location-dot text-4xl mb-4 text-gray-200"></i>
                                <p>No addresses saved yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php foreach ($addresses as $addr): ?>
                                <div class="border border-gray-200 rounded-xl p-4 relative hover:border-primary transition <?php echo $addr['is_default'] ? 'bg-blue-50/50 ring-1 ring-blue-100' : ''; ?>">
                                    <?php if ($addr['is_default']): ?>
                                        <span class="absolute top-4 right-4 text-xs font-bold text-primary bg-blue-100 px-2 py-1 rounded-full">Default</span>
                                    <?php endif; ?>
                                    
                                    <div class="flex items-start gap-4 mb-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($addr['title']); ?></h4>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($addr['city']); ?></p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                        <?php echo htmlspecialchars($addr['address_line']); ?><br>
                                        <span class="text-gray-400"><i class="fa-solid fa-phone mr-1"></i> <?php echo htmlspecialchars($addr['phone']); ?></span>
                                    </p>

                                    <div class="flex justify-end pt-3 border-t border-gray-100">
                                        <form action="address_actions.php" method="POST" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
                                            <button type="submit" class="text-red-500 text-xs font-bold hover:underline">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
