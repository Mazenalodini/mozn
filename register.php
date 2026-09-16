<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$error = '';

// Handle Register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCSRFToken();
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Insert User
            try {
                $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$full_name, $email, $hashed_password]);
                
                // Login immediately
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_role'] = 'user';
                $_SESSION['user_email'] = $email;

                echo "<script>window.location.href='index.php';</script>";
                exit;
            } catch (PDOException $e) {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 relative overflow-hidden">
    <!-- Background Decor (Same as Login) -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10 p-10 bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/50">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 font-arabic"><?php echo __('create_account'); ?></h2>
        </div>
        
        <?php if($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm text-center border border-red-100">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <?php echo csrfField(); ?>
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo __('full_name'); ?></label>
                    <input id="full_name" name="full_name" type="text" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm bg-white/50 transition" placeholder="<?php echo __('full_name'); ?>">
                </div>
                <div>
                    <label for="email-address" class="sr-only"><?php echo __('email'); ?></label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm bg-white/50 transition" placeholder="<?php echo __('email'); ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?php echo __('password'); ?></label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm bg-white/50 transition" placeholder="<?php echo __('password'); ?>">
                </div>
                <div>
                    <label for="confirm_password" class="sr-only"><?php echo __('confirm_password'); ?></label>
                    <input id="confirm_password" name="confirm_password" type="password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm bg-white/50 transition" placeholder="<?php echo __('confirm_password'); ?>">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-primary hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5 shadow-lg">
                    <?php echo __('register'); ?>
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                <?php echo __('already_have_account'); ?> 
                <a href="login.php" class="font-medium text-accent hover:text-blue-700 hover:underline transition"><?php echo __('login'); ?></a>
            </p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
