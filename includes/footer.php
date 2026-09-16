    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Column 1: Brand -->
                <div>
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-cloud text-accent"></i>
                        <span>مُزن</span>
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        <?php echo __('footer_desc'); ?>
                    </p>
                </div>

                <!-- Column 2: Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white"><?php echo __('shop'); ?></h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="shop.php" class="hover:text-gold transition">All Products</a></li>
                        <li><a href="#" class="hover:text-gold transition">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-gold transition">Best Sellers</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Social -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white"><?php echo __('contact'); ?></h4>
                    <div class="space-y-2 text-sm text-gray-400">
                        <p><i class="fa-regular fa-envelope me-2"></i> mazenalodini5@gmail.com</p>
                        <p><i class="fa-solid fa-phone me-2"></i> 771394337</p>
                    </div>
                    <div class="flex space-x-4 mt-6 rtl:space-x-reverse">
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-whatsapp text-xl"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
                <p><?php echo __('all_rights'); ?> <?php echo date('Y'); ?> Mozn.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
</body>
</html>
