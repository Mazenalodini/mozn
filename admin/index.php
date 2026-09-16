<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Auth Check
if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Stats
$order_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$product_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$user_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status = 'completed'")->fetchColumn() ?: 0;
$recent_orders = $pdo->query("SELECT o.*, u.full_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mozn Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white flex flex-col transition-all duration-300">
        <div class="p-6">
            <h1 class="text-2xl font-bold flex items-center gap-2 tracking-wider">
                <i class="fa-solid fa-cloud text-accent"></i> Mozn
            </h1>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="index.php" class="flex items-center gap-3 py-3 px-4 rounded-xl bg-gray-800 text-white font-medium shadow-lg shadow-gray-900/50">
                <i class="fa-solid fa-chart-line w-5"></i> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-3 py-3 px-4 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium transition">
                <i class="fa-solid fa-box w-5"></i> Products
            </a>
            <a href="#" class="flex items-center gap-3 py-3 px-4 rounded-xl text-gray-400 hover:bg-gray-800 hover:text-white font-medium transition">
                <i class="fa-solid fa-users w-5"></i> Customers
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <a href="../index.php" class="flex items-center gap-3 py-2 px-4 text-gray-400 hover:text-white transition text-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Store
            </a>
            <a href="../logout.php" class="flex items-center gap-3 py-2 px-4 text-red-400 hover:text-red-300 transition text-sm mt-1">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 p-8 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Overview</h2>
                <p class="text-gray-500">Welcome back, Admin!</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-white p-2 rounded-full shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" class="w-10 h-10 rounded-full">
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-gray-500 text-sm font-medium">Total Revenue</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">$<?php echo number_format($revenue, 2); ?></div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-xl text-green-600">
                        <i class="fa-solid fa-dollar-sign text-xl"></i>
                    </div>
                </div>
                <div class="text-green-500 text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> +12% from last month
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-gray-500 text-sm font-medium">Total Orders</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1"><?php echo $order_count; ?></div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                        <i class="fa-solid fa-bag-shopping text-xl"></i>
                    </div>
                </div>
                 <div class="text-blue-500 text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-caret-up"></i> New orders today
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-gray-500 text-sm font-medium">Products</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1"><?php echo $product_count; ?></div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-xl text-purple-600">
                        <i class="fa-solid fa-box-open text-xl"></i>
                    </div>
                </div>
                 <div class="text-gray-400 text-xs text-center">In inventory</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-gray-500 text-sm font-medium">Customers</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1"><?php echo $user_count; ?></div>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-xl text-orange-600">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                </div>
                <div class="text-orange-500 text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Active users
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chart Section -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6">Revenue Analytics</h3>
                <canvas id="revenueChart" height="150"></canvas>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6">Recent Orders</h3>
                <div class="space-y-4">
                    <?php if (empty($recent_orders)): ?>
                        <p class="text-gray-500 text-center py-4">No recent orders.</p>
                    <?php else: ?>
                        <?php foreach($recent_orders as $order): ?>
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition border border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs">
                                    <?php echo strtoupper(substr($order['full_name'], 0, 2)); ?>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm"><?php echo $order['full_name']; ?></div>
                                    <div class="text-xs text-gray-500">Order #<?php echo $order['id']; ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900 text-sm">$<?php echo $order['total_amount']; ?></div>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                                    <?php echo $order['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="w-full mt-6 py-2 text-primary font-bold text-sm hover:underline">View All Orders</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [1200, 1900, 3000, 5000, 2000, <?php echo $revenue > 5000 ? $revenue : 5500; ?>],
                borderColor: '#000',
                backgroundColor: 'rgba(0,0,0,0.05)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#000',
                    secondary: '#fff',
                    accent: '#3B82F6',
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                }
            }
        }
    }
</script>

</body>
</html>
