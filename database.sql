-- Database Schema for Mozn E-Commerce
-- Compatible with MySQL / MariaDB (InfinityFree)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `mozn_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`full_name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@mozn.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'user@mozn.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) NOT NULL,
  `name_ar` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`name_en`, `name_ar`, `slug`) VALUES
('Electronics', 'إلكترونيات', 'electronics'),
('Fashion', 'أزياء', 'fashion'),
('Home & Living', 'المنزل والمعيشة', 'home-living'),
('Beauty', 'جمال', 'beauty');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `description_en` text,
  `description_ar` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800&q=80',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`category_id`, `name_en`, `name_ar`, `description_en`, `description_ar`, `price`, `image`) VALUES
(1, 'Premium Noise-Cancelling Headphones', 'سماعات عازلة للضوضاء فاخرة', 'Immersive sound experience with top-tier noise cancellation.', 'تجربة صوتية غامرة مع إلغاء ضوضاء عالي الجودة.', 299.99, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80'),
(1, 'Minimalist Smart Watch', 'ساعة ذكية بتصميم بسيط', 'Track your health in style.', 'تتبع صحتك بأناقة.', 199.50, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80'),
(1, 'Professional DSLR Camera', 'كاميرا احترافية DSLR', 'Capture life in stunning detail.', 'التقط صور الحياة بتفاصيل مذهلة.', 1250.00, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80'),
(1, 'Wireless Gaming Mouse', 'ماوس ألعاب لا سلكي', 'High precision sensor for gamers.', 'حساس عالي الدقة للاعبين.', 59.99, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=800&q=80'),
(2, 'Classic Denim Jacket', 'جاكيت جينز كلاسيكي', 'Timeless style for any season.', 'تصميم كلاسيكي مناسب لكل المواسم.', 89.00, 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80'),
(2, 'Urban Sneakers', 'حذاء رياضي عصري', 'Comfort meets street style.', 'الراحة تلتقي بأسلوب الشارع.', 110.00, 'https://images.unsplash.com/photo-1552346154-21d32810aba3?auto=format&fit=crop&w=800&q=80'),
(2, 'Leather Crossbody Bag', 'حقيبة جلدية كروس', 'Genuine leather, perfect for daily use.', 'جلد طبيعي، مثالية للاستخدام اليومي.', 145.00, 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80'),
(2, 'Silk Scarf', 'وشاح حريري', 'Elegant touch to your outfit.', 'لمسة أنيقة لملابسك.', 45.00, 'https://images.unsplash.com/photo-1584030373081-f37b7bb4fa8e?auto=format&fit=crop&w=800&q=80'),
(2, 'Rolex Style Watch', 'ساعة كلاسيكية فاخرة', 'Gold plated luxury watch.', 'ساعة فاخرة مطلية بالذهب.', 350.00, 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=800&q=80'),
(3, 'Modern Table Lamp', 'مصباح طاولة حديث', 'Warm light for cozy evenings.', 'إضاءة دافئة لأمسيات مريحة.', 45.00, 'https://images.unsplash.com/photo-1507473888900-52e1ad154373?auto=format&fit=crop&w=800&q=80'),
(3, 'Ceramic Plant Pot', 'وعاء نباتات سيراميك', 'Minimalist design for indoor plants.', 'تصميم بسيط للنباتات الداخلية.', 29.00, 'https://images.unsplash.com/photo-1485955900006-10f623a36301?auto=format&fit=crop&w=800&q=80'),
(3, 'Abstract Wall Art', 'لوحة جدارية تجريدية', 'Adds character to any room.', 'تضيف طابعاً لأي غرفة.', 75.00, 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?auto=format&fit=crop&w=800&q=80'),
(3, 'Scented Soy Candle', 'شمعة صويا معطرة', 'Relaxing lavender scent.', 'رائحة اللافندر المريحة.', 18.00, 'https://images.unsplash.com/photo-1603006905003-be475563bc59?auto=format&fit=crop&w=800&q=80'),
(4, 'Organic Face Serum', 'سيروم وجه عضوي', 'Rejuvenating formula.', 'تركيبة تجديد الشباب.', 55.00, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80'),
(4, 'Luxury Lipstick Set', 'مجموعة أحمر شفاه فاخرة', 'Vibrant colors that last.', 'ألوان نابضة بالحياة تدوم طويلاً.', 42.00, 'https://images.unsplash.com/photo-1571781535469-f1d22dc9c22e?auto=format&fit=crop&w=800&q=80'),
(4, 'Herbal Shampoo', 'شامبو بالأعشاب', 'For shiny and healthy hair.', 'لشعر لامع وصحي.', 22.00, 'https://images.unsplash.com/photo-1631729371254-42c2a89ddf17?auto=format&fit=crop&w=800&q=80');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `shipping_address` text,
  `phone` varchar(20),
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_item_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`, `product_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
