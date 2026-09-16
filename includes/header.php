<?php
require_once __DIR__ . '/functions.php';

// Handle Language Switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

$current_lang = $_SESSION['lang'] ?? 'ar';
$dir = $current_lang === 'ar' ? 'rtl' : 'ltr';
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $dir; ?>" class="scroll-smooth">
<head>
    <?php 
    // SEO Defaults
    $page_title = isset($page_title) ? $page_title . ' | ' . APP_NAME : APP_NAME . ' - ' . __('hero_title');
    $page_desc = isset($page_desc) ? $page_desc : 'Discover Mozn, the premium destination for exclusive fashion and electronics. Shop the latest trends with free shipping.';
    $page_image = isset($page_image) ? $page_image : 'assets/images/mozn-bag-ar.png';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_desc; ?>">
    <meta name="keywords" content="ecommerce, fashion, electronics, mozn, luxury, shopping, saudi arabia">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta property="og:description" content="<?php echo $page_desc; ?>">
    <meta property="og:image" content="<?php echo $page_image; ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo $page_title; ?>">
    <meta property="twitter:description" content="<?php echo $page_desc; ?>">
    <meta property="twitter:image" content="<?php echo $page_image; ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0f172a',    
                        secondary: '#1e293b',
                        accent: '#2563eb',
                        gold: '#d97706',
                        surface: '#f8fafc',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'Inter', 'sans-serif'],
                        arabic: ['Cairo', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 1s ease-out',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    
    <script>
        // Check for saved theme preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-surface dark:bg-slate-900 text-secondary dark:text-slate-200 antialiased flex flex-col min-h-screen transition-colors duration-300" 
      x-data="{ 
          darkMode:localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.theme = 'dark';
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.theme = 'light';
              }
          }
      }">



    <!-- Navigation (Cloud Style) -->
    <nav class="fixed top-6 left-4 right-4 md:left-8 md:right-8 max-w-7xl mx-auto z-50 cloud-nav glass dark:glass-dark transition-all duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="px-6 sm:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="index.php" class="relative group">
                        <div class="text-3xl font-black text-primary dark:text-white flex items-center gap-2">
                             <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-accent to-blue-400 text-white flex items-center justify-center shadow-lg transform group-hover:rotate-12 transition">
                                <i class="fa-solid fa-cloud-bolt text-lg"></i>
                             </div>
                             <span class="tracking-tighter bg-clip-text text-transparent bg-gradient-to-r from-primary to-gray-600 dark:from-white dark:to-gray-400">مُزن</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 rtl:gap-reverse">
                    <a href="index.php" class="text-gray-600 dark:text-gray-300 hover:text-accent dark:hover:text-accent font-semibold text-sm uppercase tracking-wider transition relative group">
                        <?php echo __('home'); ?>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="shop.php" class="text-gray-600 dark:text-gray-300 hover:text-accent dark:hover:text-accent font-semibold text-sm uppercase tracking-wider transition relative group">
                        <?php echo __('shop'); ?>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="about.php" class="text-gray-600 dark:text-gray-300 hover:text-accent dark:hover:text-accent font-semibold text-sm uppercase tracking-wider transition relative group">
                        <?php echo __('about'); ?>
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-accent transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>

                <div class="flex items-center gap-4 rtl:gap-reverse">
                    <!-- Search Bar (Desktop) -->
                    <div class="hidden lg:block relative" x-data="{ focused: false }">
                        <form action="shop.php" method="GET">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="<?php echo __('search'); ?>..." 
                                @focus="focused = true" 
                                @blur="focused = false"
                                class="pl-10 pr-4 py-2 rounded-full bg-white/50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 focus:ring-2 focus:ring-accent focus:w-64 w-48 transition-all duration-300 text-sm"
                            >
                            <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-accent transition">
                                <i class="fa-solid fa-search"></i>
                            </button>
                        </form>
                    </div>


                    <!-- Dark Mode Toggle -->
                    <button @click="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-yellow-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <!-- Language -->
                    <a href="?lang=<?php echo $current_lang === 'ar' ? 'en' : 'ar'; ?>" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-full hover:border-primary">
                        <?php echo $current_lang === 'ar' ? 'EN' : 'عربي'; ?>
                    </a>

                    <!-- Cart -->
                    <a href="cart.php" class="relative w-10 h-10 flex items-center justify-center rounded-full text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-accent transition">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                        <?php 
                        $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                        if($cart_count > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white dark:border-slate-900 shadow-sm animate-bounce">
                            <?php echo $cart_count; ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- User -->
                    <?php if (isLoggedIn()): ?>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center border-2 border-transparent hover:border-accent transition overflow-hidden">
                                     <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['user_name']; ?>&background=0f172a&color=fff" class="w-full h-full object-cover">
                                </div>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl py-2 z-50 border border-gray-100 dark:border-slate-700 overflow-hidden" style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-50 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/50">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate"><?php echo $_SESSION['user_name']; ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo $_SESSION['user_email']; ?></p>
                                </div>
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 hover:text-accent transition"><i class="fa-regular fa-user mr-2"></i> <?php echo __('profile'); ?></a>
                                <?php if (isAdmin()): ?>
                                    <a href="admin/index.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 hover:text-accent transition"><i class="fa-solid fa-gauge mr-2"></i> Admin Panel</a>
                                <?php endif; ?>
                                <div class="border-t border-gray-100 dark:border-slate-700 my-1"></div>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> <?php echo __('logout'); ?></a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="hidden md:inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-full text-white bg-primary hover:bg-gray-800 dark:hover:bg-slate-800 shadow-lg hover:shadow-primary/30 transition transform hover:-translate-y-0.5">
                            <?php echo __('login'); ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" class="md:hidden fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition transform ease-out duration-300" x-transition:enter-start="translate-x-full rtl:-translate-x-full" x-transition:enter-end="translate-x-0" class="fixed top-0 right-0 h-full w-80 bg-white dark:bg-slate-900 z-50 shadow-2xl p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <span class="text-2xl font-bold text-primary dark:text-white">مُزن</span>
                <button @click="mobileMenuOpen = false" class="text-gray-500 hover:text-red-500 transition"><i class="fa-solid fa-times text-2xl"></i></button>
            </div>
            <div class="space-y-4">
                <a href="index.php" class="block px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white font-bold hover:bg-accent hover:text-white transition flex items-center justify-between group">
                    <?php echo __('home'); ?>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:text-white rtl:rotate-180"></i>
                </a>
                <a href="shop.php" class="block px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white font-bold hover:bg-accent hover:text-white transition flex items-center justify-between group">
                    <?php echo __('shop'); ?>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:text-white rtl:rotate-180"></i>
                </a>
                <a href="about.php" class="block px-4 py-3 rounded-xl bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white font-bold hover:bg-accent hover:text-white transition flex items-center justify-between group">
                    <?php echo __('about'); ?>
                    <i class="fa-solid fa-chevron-right text-xs opacity-50 group-hover:text-white rtl:rotate-180"></i>
                </a>
                <?php if (!isLoggedIn()): ?>
                    <a href="login.php" class="block mt-8 w-full text-center px-4 py-3 rounded-xl bg-primary text-white font-bold shadow-lg hover:shadow-xl transition">
                        <?php echo __('login'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Main Content Wrapper -->
    <main class="flex-grow">
