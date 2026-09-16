<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location.href='shop.php';</script>";
    exit;
}

// Fetch Product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='py-20 text-center'>Product not found.</div>";
    require_once 'includes/footer.php';
    exit;
}

// Fetch Category Name
$cat_stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$cat_stmt->execute([$product['category_id']]);
$category = $cat_stmt->fetch();
?>

<?php
// Handle Review Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isLoggedIn()) {
        echo "<script>alert('Please login first');</script>";
    } else {
        $rating = (int)$_POST['rating'];
        $comment = strip_tags($_POST['comment']);
        if (addProductReview($pdo, $_SESSION['user_id'], $id, $rating, $comment)) {
            echo "<script>window.location.href='product.php?id=$id';</script>";
        }
    }
}

$reviews = getProductReviews($pdo, $id);
$related_products = getRelatedProducts($pdo, $product['category_id'], $id);
$avg_rating = count($reviews) > 0 ? array_sum(array_column($reviews, 'rating')) / count($reviews) : 0;
?>

<div class="py-12 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400">
            <a href="index.php" class="hover:text-primary dark:hover:text-white transition"><?php echo __('home'); ?></a>
            <span class="mx-2">/</span>
            <a href="shop.php" class="hover:text-primary dark:hover:text-white transition"><?php echo __('shop'); ?></a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 dark:text-white font-medium"><?php echo $product['name_' . $current_lang]; ?></span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 mb-20">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="aspect-w-1 aspect-h-1 bg-gray-100 dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700">
                    <img src="<?php echo strpos($product['image'], 'http') === 0 ? $product['image'] : 'assets/images/' . $product['image']; ?>" alt="Product" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Product Info -->
            <div>
                <span class="text-accent font-medium tracking-wide text-sm uppercase">
                    <?php echo $category['name_' . $current_lang] ?? 'Category'; ?>
                </span>
                <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white mt-2 mb-2 font-arabic">
                    <?php echo $product['name_' . $current_lang]; ?>
                </h1>
                
                <!-- Rating Summary -->
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex text-yellow-400 text-sm">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="<?php echo $i <= round($avg_rating) ? 'fa-solid' : 'fa-regular'; ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400 text-sm">(<?php echo count($reviews); ?> Reviews)</span>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                        $<?php echo $product['price']; ?>
                    </div>
                    
                    <?php 
                    $in_wishlist = false;
                    if (isLoggedIn()) {
                        $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                        $stmt->execute([$_SESSION['user_id'], $product['id']]);
                        $in_wishlist = $stmt->fetch();
                    }
                    ?>
                    <a href="wishlist.php?product_id=<?php echo $product['id']; ?>" class="w-10 h-10 rounded-full flex items-center justify-center border transition <?php echo $in_wishlist ? 'bg-red-50 border-red-200 text-red-500' : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-gray-400 hover:text-red-500'; ?>">
                        <i class="<?php echo $in_wishlist ? 'fa-solid' : 'fa-regular'; ?> fa-heart text-xl"></i>
                    </a>
                </div>

                <div class="prose prose-blue dark:prose-invert text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    <?php echo nl2br($product['description_' . $current_lang]); ?>
                </div>

                <!-- Add to Cart Form -->
                <form action="cart.php" method="POST" class="space-y-6 border-t border-gray-100 dark:border-slate-800 pt-8" x-data="{ qty: 1 }">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="flex items-center gap-6">
                        <!-- Quantity -->
                        <div class="flex items-center border border-gray-300 dark:border-slate-600 rounded-full">
                            <button type="button" @click="qty > 1 ? qty-- : null" class="w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition rounded-l-full">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <input type="number" name="quantity" x-model="qty" class="w-12 text-center border-none focus:ring-0 text-gray-900 dark:text-white bg-transparent font-medium h-10 p-0" min="1" readonly>
                            <button type="button" @click="qty++" class="w-10 h-10 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition rounded-r-full">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>

                        <!-- Button -->
                        <button type="submit" class="flex-1 bg-primary dark:bg-accent text-white px-8 py-3 rounded-full font-bold hover:bg-gray-800 dark:hover:bg-blue-700 transition transform active:scale-95 shadow-lg flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <?php echo __('add_to_cart'); ?>
                        </button>
                    </div>
                </form>

                <!-- Value Props -->
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-rotate-left text-accent"></i>
                        30 Day Return
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-shield-halved text-accent"></i>
                        Secure Payment
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
        <div class="mb-20">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">You May Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($related_products as $rp): ?>
                <a href="product.php?id=<?php echo $rp['id']; ?>" class="group block bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition">
                    <div class="aspect-w-1 aspect-h-1 bg-gray-100 dark:bg-slate-700">
                         <img src="<?php echo strpos($rp['image'], 'http') === 0 ? $rp['image'] : 'assets/images/' . $rp['image']; ?>" class="object-cover w-full h-full group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 dark:text-white truncate"><?php echo $rp['name_' . $current_lang]; ?></h3>
                        <p class="text-accent font-bold mt-1">$<?php echo $rp['price']; ?></p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Reviews Section -->
        <div class="bg-gray-50 dark:bg-slate-800 rounded-3xl p-8 lg:p-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Customer Reviews</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Review Form -->
                <div class="lg:col-span-1">
                    <?php if (isLoggedIn()): ?>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Write a Review</h3>
                        <form method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rating</label>
                                <select name="rating" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-accent focus:ring focus:ring-accent/20">
                                    <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                    <option value="4">⭐⭐⭐⭐ (Good)</option>
                                    <option value="3">⭐⭐⭐ (Average)</option>
                                    <option value="2">⭐⭐ (Poor)</option>
                                    <option value="1">⭐ (Terrible)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comment</label>
                                <textarea name="comment" rows="4" class="w-full rounded-lg border-gray-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-accent focus:ring focus:ring-accent/20" required></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="w-full bg-primary dark:bg-accent text-white py-3 rounded-lg font-bold hover:opacity-90 transition">Submit Review</button>
                        </form>
                    <?php else: ?>
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl text-center border border-gray-100 dark:border-slate-700">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Please log in to write a review.</p>
                            <a href="login.php" class="inline-block bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold">Login</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews List -->
                <div class="lg:col-span-2 space-y-6">
                    <?php if (empty($reviews)): ?>
                        <p class="text-gray-500 dark:text-gray-400 italic">No reviews yet. Be the first to review this product!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-700">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-random text-white flex items-center justify-center font-bold text-sm" style="background-color: #<?php echo substr(md5($review['full_name']), 0, 6); ?>">
                                        <?php echo strtoupper(substr($review['full_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-sm"><?php echo $review['full_name']; ?></h4>
                                        <div class="flex text-yellow-400 text-xs">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?php echo $i <= $review['rating'] ? 'fa-solid' : 'fa-regular'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed"><?php echo htmlspecialchars($review['comment']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
