<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$error = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCSRFToken();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];
        
        echo "<script>window.location.href='index.php';</script>";
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="min-h-[calc(100vh-200px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10 p-10 bg-white/70 backdrop-blur-lg rounded-2xl shadow-xl border border-white/50">
        <div class="text-center">
            <div class="mx-auto w-12 h-12 bg-primary text-white rounded-xl flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-cloud"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 font-arabic"><?php echo __('log_in_to_continue'); ?></h2>
        </div>
        
        <?php if($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm text-center border border-red-100">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="" method="POST">
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div class="mb-4">
                    <label for="email-address" class="sr-only"><?php echo __('email'); ?></label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm transition bg-white/50" placeholder="<?php echo __('email'); ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?php echo __('password'); ?></label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-accent focus:border-accent focus:z-10 sm:text-sm transition bg-white/50" placeholder="<?php echo __('password'); ?>">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-primary hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition transform hover:-translate-y-0.5 shadow-lg">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-lock text-gray-400 group-hover:text-white transition"></i>
                    </span>
                    <?php echo __('login'); ?>
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                <?php echo __('dont_have_account'); ?> 
                <a href="register.php" class="font-medium text-accent hover:text-blue-700 hover:underline transition"><?php echo __('create_account'); ?></a>
            </p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
