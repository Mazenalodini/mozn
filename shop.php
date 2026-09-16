<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Filter Logic
$category_slug = $_GET['category'] ?? null;
$search_query = $_GET['q'] ?? null;
$sort_option = $_GET['sort'] ?? 'newest';

$where_clauses = [];
$params = [];

// Category Filter
if ($category_slug) {
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->execute([$category_slug]);
    $cat = $stmt->fetch();
    if ($cat) {
        $where_clauses[] = "category_id = ?";
        $params[] = $cat['id'];
    }
}

// Search Filter
if ($search_query) {
    $where_clauses[] = "(name_en LIKE ? OR name_ar LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Build SQL
$sql = "SELECT * FROM products";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

// Sorting
switch ($sort_option) {
    case 'price_low':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY price DESC";
        break;
    default: // newest
        $sql .= " ORDER BY created_at DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch Categories for Sidebar
$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>

<div class="bg-gray-50 dark:bg-slate-900 py-12 min-h-screen transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-arabic"><?php echo __('shop'); ?></h1>
            
            <form action="" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <?php if($category_slug): ?><input type="hidden" name="category" value="<?php echo $category_slug; ?>"><?php endif; ?>
                
                <!-- Search -->
                <div class="relative">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($search_query ?? ''); ?>" placeholder="<?php echo __('search'); ?>" class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-accent focus:border-accent">
                    <span class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fa-solid fa-search"></i>
                    </span>
                </div>

                <!-- Sort -->
                <select name="sort" onchange="this.form.submit()" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:ring-accent focus:border-accent">
                    <option value="newest" <?php echo $sort_option=='newest'?'selected':''; ?>>Newest Arrivals</option>
                    <option value="price_low" <?php echo $sort_option=='price_low'?'selected':''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo $sort_option=='price_high'?'selected':''; ?>>Price: High to Low</option>
                </select>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 sticky top-24 transition-colors duration-300">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg"><?php echo __('categories'); ?></h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="shop.php" class="block px-3 py-2 rounded-lg text-sm transition <?php echo !$category_slug ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'; ?>">
                                All Products
                            </a>
                        </li>
                        <?php foreach($categories as $cat): ?>
                            <li>
                                <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="block px-3 py-2 rounded-lg text-sm transition <?php echo $category_slug === $cat['slug'] ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700'; ?>">
                                    <?php echo $cat['name_' . $current_lang]; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($products as $product): 
                        // Ensure image URL is handled (if local vs external)
                        $imgSrc = strpos($product['image'], 'http') === 0 ? $product['image'] : 'assets/images/' . $product['image'];
                    ?>
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl dark:shadow-slate-900/50 transition-all duration-300 transform hover:-translate-y-1 group overflow-hidden border border-gray-100 dark:border-slate-700">
                        <!-- Image -->
                        <div class="relative h-64 bg-gray-200 dark:bg-slate-700 overflow-hidden">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo $product['name_' . $current_lang]; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        
                        <!-- Wishlist Button -->
                        <?php 
                        $in_wishlist = false;
                        if (function_exists('isLoggedIn') && isLoggedIn()) { // Check if isLoggedIn function exists and user is logged in
                            $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                            $stmt->execute([$_SESSION['user_id'], $product['id']]);
                            $in_wishlist = $stmt->fetch();
                        }
                        ?>
                        <a href="wishlist.php?product_id=<?php echo $product['id']; ?>" class="absolute top-3 right-3 w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-110 z-10 <?php echo $in_wishlist ? 'bg-red-500 text-white' : 'bg-white text-gray-400 hover:text-red-500'; ?>">
                            <i class="<?php echo $in_wishlist ? 'fa-solid' : 'fa-regular'; ?> fa-heart text-sm"></i>
                        </a>

                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 truncate group-hover:text-accent transition">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <?php echo $product['name_' . $current_lang]; ?>
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                                <?php echo $product['description_' . $current_lang]; ?>
                            </p>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-xl font-bold text-gray-900 dark:text-white">$<?php echo $product['price']; ?></span>
                                <form action="cart.php" method="POST">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button class="w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-700 dark:text-white hover:bg-accent hover:text-white flex items-center justify-center transition" title="<?php echo __('add_to_cart'); ?>">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($products)): ?>
                        <div class="col-span-3 text-center py-20 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-gray-300 dark:border-slate-700">
                            <i class="fa-solid fa-search text-4xl text-gray-300 dark:text-slate-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400">No products found matching your criteria.</p>
                            <a href="shop.php" class="text-accent hover:underline mt-2 inline-block">Clear Filters</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
