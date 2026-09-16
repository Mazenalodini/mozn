<?php
require_once 'includes/header.php';
?>

<!-- Hero Section for About -->
<section class="relative bg-primary py-20 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-1/2 -right-1/2 w-[1000px] h-[1000px] rounded-full bg-accent opacity-10 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-1/2 -left-1/2 w-[800px] h-[800px] rounded-full bg-gold opacity-10 blur-3xl animate-blob"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 font-arabic animate-fade-in-up">
            <?php echo $current_lang === 'ar' ? 'قصتنا' : 'Our Story'; ?>
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto animate-fade-in-up delay-100">
            <?php echo $current_lang === 'ar' 
                ? 'في "مُزن"، نؤمن بأن التسوق ليس مجرد شراء منتجات، بل هو تجربة تعكس ذوقك الفريد.' 
                : 'At Mozn, we believe shopping is not just about buying products, but an experience that reflects your unique taste.'; ?>
        </p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16 bg-white dark:bg-slate-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="relative group">
                <div class="absolute -inset-4 bg-gradient-to-r from-accent to-purple-600 rounded-xl blur-lg opacity-30 group-hover:opacity-50 transition duration-1000"></div>
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" alt="Office" class="relative rounded-xl shadow-2xl transform transition duration-500 hover:scale-[1.02]">
            </div>
            
            <div class="space-y-8">
                <div class="p-6 bg-gray-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-accent mb-4">
                        <i class="fa-solid fa-bullseye text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 font-arabic">
                        <?php echo $current_lang === 'ar' ? 'رؤيتنا' : 'Our Vision'; ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        <?php echo $current_lang === 'ar' 
                            ? 'أن نكون الوجهة الأولى للتسوق الإلكتروني في المنطقة، من خلال تقديم منتجات تجمع بين الجودة العالية والتصميم العصري.' 
                            : 'To be the premier online shopping destination in the region, offering products that combine high quality with modern design.'; ?>
                    </p>
                </div>

                <div class="p-6 bg-gray-50 dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center text-gold mb-4">
                        <i class="fa-solid fa-hand-holding-heart text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 font-arabic">
                        <?php echo $current_lang === 'ar' ? 'قيمنا' : 'Our Values'; ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        <?php echo $current_lang === 'ar' 
                            ? 'الشفافية، الجودة، ورضا العملاء هم محور كل ما نقوم به.' 
                            : 'Transparency, quality, and customer satisfaction are at the core of everything we do.'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter -->
<section class="py-20 bg-primary text-white relative overflow-hidden">
    <!-- Abstract Background Pattern -->
    <svg class="absolute top-0 left-0 w-full h-full opacity-10" viewBox="0 0 100 100" preserveAspectRatio="none">
        <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
    </svg>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-accent">5K+</div>
                <div class="text-gray-400 font-medium"><?php echo $current_lang === 'ar' ? 'عميل سعيد' : 'Happy Customers'; ?></div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-gold">1.2K+</div>
                <div class="text-gray-400 font-medium"><?php echo $current_lang === 'ar' ? 'منتج فريد' : 'Unique Products'; ?></div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-purple-400">24/7</div>
                <div class="text-gray-400 font-medium"><?php echo $current_lang === 'ar' ? 'دعم فني' : 'Support'; ?></div>
            </div>
            <div class="space-y-2">
                <div class="text-4xl md:text-5xl font-bold text-green-400">99%</div>
                <div class="text-gray-400 font-medium"><?php echo $current_lang === 'ar' ? 'تقييمات إيجابية' : 'Positive Reviews'; ?></div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
