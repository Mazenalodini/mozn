<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch Featured
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
$featured_products = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="relative bg-surface overflow-hidden min-h-[600px] flex items-center pt-24">
    <!-- Abstract Background Blobs -->
    <div class="absolute top-0 right-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-gradient-to-br from-blue-200 to-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-gradient-to-tr from-amber-100 to-orange-100 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left rtl:lg:text-right space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-gray-100 dark:border-slate-700 text-sm font-medium text-accent animate-fade-in-up">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    <?php echo $current_lang === 'ar' ? 'تشكيلة جديدة وصلتنا للتو!' : 'New Collection Just Dropped!'; ?>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-black text-primary dark:text-white leading-tight font-arabic animate-fade-in-up delay-100">
                    <?php echo $current_lang === 'ar' ? 'أناقتك تبدأ <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-purple-600">من هنا</span>' : 'Your Style Starts <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-purple-600">Right Here</span>'; ?>
                </h1>
                
                <p class="text-xl text-gray-500 dark:text-gray-400 font-light leading-relaxed max-w-lg mx-auto lg:mx-0 animate-fade-in-up delay-200">
                    <?php echo __('hero_subtitle'); ?>
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up delay-300">
                    <a href="shop.php" class="px-8 py-4 rounded-full bg-primary dark:bg-accent text-white font-bold shadow-xl shadow-primary/30 dark:shadow-blue-900/50 hover:shadow-2xl hover:-translate-y-1 transition transform flex items-center justify-center gap-2">
                        <span><?php echo __('shop_now'); ?></span>
                        <i class="fa-solid fa-arrow-right rtl:rotate-180"></i>
                    </a>
                    <a href="#featured" class="px-8 py-4 rounded-full bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 font-bold border border-gray-200 dark:border-slate-700 shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition transform flex items-center justify-center">
                        <?php echo __('featured_products'); ?>
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="pt-8 flex items-center justify-center lg:justify-start gap-8 opacity-70 grayscale hover:grayscale-0 transition duration-500 animate-fade-in-up delay-300 dark:invert">
                    <i class="fa-brands fa-cc-visa text-3xl"></i>
                    <i class="fa-brands fa-cc-mastercard text-3xl"></i>
                    <i class="fa-brands fa-apple-pay text-3xl"></i>
                </div>
            </div>

            <!-- Visual Content (Carousel) -->
            <div class="relative lg:h-[600px] flex items-center justify-center hidden md:flex animate-float" x-data="{ 
                activeSlide: 0, 
                slides: [
                    'assets/images/mozn_bag_arabic_1765391433032.png',
                    'assets/images/mozn_storefront_arabic_1765391462979.png',
                    'assets/images/mozn_logo_arabic_v2_1765391517652.png',
                    'assets/images/mozn_shopping_bag_1765389566372.png',
                    'assets/images/shopper_with_bags_1765389596781.png',
                    'assets/images/mozn_electronics_1765389623076.png',
                    'assets/images/mozn_delivery_box_1765389655943.png'
                ],
                init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slides.length }, 3000) }
            }">
                <div class="relative w-full max-w-md aspect-[4/5] rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-slate-700 rotate-[-5deg] hover:rotate-0 transition duration-500">
                    <template x-for="(slide, index) in slides" :key="index">
                        <img :src="slide" 
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out"
                             :class="{ 'opacity-100': activeSlide === index, 'opacity-0': activeSlide !== index }"
                             alt="Collection">
                    </template>
                    
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-black/40"></div>
                    
                    <!-- Text Overlay (Moved to Bottom) -->
                    <div class="absolute inset-0 flex items-end justify-center pb-16 text-center z-10">
                        <p class="text-white font-bold text-lg leading-relaxed drop-shadow-md font-arabic px-4">
                            <?php echo $current_lang === 'ar' ? 'توصيل مجاني للطلبات فوق 300 ريال <br> لفترة محدودة!' : 'Free Shipping on orders over $300 <br> Limited Time!'; ?>
                        </p>
                    </div>
                    
                    <!-- Carousel Indicators -->
                    <div class="absolute bottom-6 left-0 w-full flex justify-center gap-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :class="activeSlide === index ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80'">
                            </button>
                        </template>
                    </div>

                    <!-- Floating Badge -->
                    <div class="absolute top-6 right-6 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg z-20 animate-bounce" style="animation-duration: 4s;">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-sm font-bold text-gray-900">New Arrivals</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Special Offers Section -->
<section class="py-20 bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white font-arabic mb-4">
                <?php echo $current_lang === 'ar' ? 'عروض حصرية' : 'Exclusive Offers'; ?>
            </h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">
                Discover our latest deals and premium collections selected just for you.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Offer Card 1 -->
            <div class="relative h-[400px] rounded-3xl overflow-hidden group shadow-2xl">
                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-10 text-white">
                    <span class="bg-accent px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4 inline-block">Limited Time</span>
                    <h3 class="text-4xl font-black mb-4 font-arabic">Electronics Sale</h3>
                    <p class="text-gray-200 mb-6 font-light">Up to 50% off on premium gadgets and accessories.</p>
                    <a href="shop.php" class="inline-flex items-center gap-2 font-bold hover:text-accent transition">
                        Shop Now <i class="fa-solid fa-arrow-right rtl:rotate-180"></i>
                    </a>
                </div>
            </div>

            <!-- Offer Card 2 -->
            <div class="grid grid-rows-2 gap-8 h-[400px]">
                <!-- Sub Card 1 -->
                <div class="relative rounded-3xl overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>
                    <div class="absolute inset-0 flex flex-col justify-center px-8 text-white">
                        <h4 class="text-2xl font-bold mb-2 font-arabic">New Sneakers</h4>
                        <a href="shop.php" class="text-sm font-bold underline decoration-accent decoration-2 underline-offset-4 hover:text-accent transition">Discover</a>
                    </div>
                </div>
                <!-- Sub Card 2 -->
                <div class="relative rounded-3xl overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=800&q=80" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>
                    <div class="absolute inset-0 flex flex-col justify-center px-8 text-white">
                        <h4 class="text-2xl font-bold mb-2 font-arabic">Apple Collection</h4>
                        <a href="shop.php" class="text-sm font-bold underline decoration-accent decoration-2 underline-offset-4 hover:text-accent transition">Shop Brand</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Section -->
<section id="featured" class="py-24 bg-gray-50 dark:bg-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <span class="text-accent font-bold tracking-wider uppercase text-sm">Top Selling</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mt-2 font-arabic"><?php echo __('featured_products'); ?></h2>
            </div>
            <a href="shop.php" class="text-gray-600 dark:text-gray-400 font-bold hover:text-primary dark:hover:text-white transition flex items-center gap-2 group">
                View All Collection
                <i class="fa-solid fa-arrow-right list-disc rtl:rotate-180 transition transform group-hover:translate-x-1 rtl:group-hover:-translate-x-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featured_products as $product): ?>
            <!-- Premium Product Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-4 shadow-sm hover:shadow-2xl transition-all duration-500 group relative border border-gray-100 dark:border-slate-700">
                <!-- Image Container -->
                <div class="relative h-64 rounded-2xl bg-gray-100 dark:bg-slate-700 overflow-hidden mb-4">
                    <img src="<?php echo strpos($product['image'], 'http') === 0 ? $product['image'] : 'assets/images/' . $product['image']; ?>" alt="<?php echo $product['name_' . $current_lang]; ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    
                    <!-- Actions Overlay -->
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3">
                         <a href="product.php?id=<?php echo $product['id']; ?>" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-900 hover:bg-accent hover:text-white transition transform translate-y-4 group-hover:translate-y-0 duration-300">
                            <i class="fa-regular fa-eye"></i>
                         </a>
                         
                         <?php 
                            $in_wishlist = false;
                            if (function_exists('isLoggedIn') && isLoggedIn()) {
                                $stmt = $pdo->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
                                $stmt->execute([$_SESSION['user_id'], $product['id']]);
                                $in_wishlist = $stmt->fetch();
                            }
                        ?>
                         <a href="wishlist.php?product_id=<?php echo $product['id']; ?>" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-900 hover:bg-accent hover:text-white transition transform translate-y-4 group-hover:translate-y-0 delay-75 duration-300 <?php echo $in_wishlist ? 'text-red-500' : ''; ?>">
                            <i class="<?php echo $in_wishlist ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                         </a>
                    </div>

                    <!-- Badge -->
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold text-primary shadow-sm tracking-wide uppercase">
                        New
                    </div>
                </div>
                
                <!-- Info -->
                <div class="px-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 leading-tight group-hover:text-accent transition">
                        <a href="product.php?id=<?php echo $product['id']; ?>">
                            <?php echo $product['name_' . $current_lang]; ?>
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3"><?php echo $product['category_id'] == 1 ? 'Electronics' : 'Collection'; ?></p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-black text-gray-900 dark:text-white font-mono">$<?php echo $product['price']; ?></span>
                        <form action="cart.php" method="POST">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center hover:bg-accent transition shadow-lg hover:rotate-90 duration-300">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Promo / CTA Section -->
<section class="py-20 relative overflow-hidden bg-primary">
    <div class="absolute top-0 right-0 w-full h-full opacity-20">
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent blur-[100px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600 blur-[100px] rounded-full"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center text-white space-y-8">
        <h2 class="text-4xl md:text-5xl font-black font-arabic">
            <?php echo $current_lang === 'ar' ? 'جاهز للتسوق بذكاء؟' : 'Ready to Shop Smarter?'; ?>
        </h2>
        <p class="text-lg text-gray-300 max-w-2xl mx-auto">
            Join thousands of satisfied customers who trust Mozn for their daily needs. Premium quality, best market prices.
        </p>
        <?php if (isLoggedIn()): ?>
            <?php if (isAdmin()): ?>
                <a href="admin/index.php" class="inline-block px-10 py-4 bg-white text-primary font-bold rounded-full text-lg shadow-xl hover:bg-gray-100 transition transform hover:-translate-y-1">
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="shop.php" class="inline-block px-10 py-4 bg-white text-primary font-bold rounded-full text-lg shadow-xl hover:bg-gray-100 transition transform hover:-translate-y-1">
                    Start Shopping
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="register.php" class="inline-block px-10 py-4 bg-white text-primary font-bold rounded-full text-lg shadow-xl hover:bg-gray-100 transition transform hover:-translate-y-1">
                <?php echo __('create_account'); ?>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
