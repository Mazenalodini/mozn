<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login.php");
    exit;
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name_en = $_POST['name_en'];
    $name_ar = $_POST['name_ar'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $desc_en = $_POST['desc_en'];
    $desc_ar = $_POST['desc_ar'];
    
    // Image Upload
    $image = 'placeholder.jpg'; // default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($_FILES["image"]["name"]);
        }
    }

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name_en, name_ar, description_en, description_ar, price, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name_en, $name_ar, $desc_en, $desc_ar, $price, $image]);
    
    header("Location: products.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: products.php");
    exit;
}

$products = $pdo->query("SELECT p.*, c.name_en as cat_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products | Mozn Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white p-6 flex-shrink-0">
        <h1 class="text-2xl font-bold mb-8 flex items-center gap-2">
            <i class="fa-solid fa-cloud text-blue-500"></i> Mozn Admin
        </h1>
        <nav class="space-y-4">
            <a href="index.php" class="block py-2 px-4 rounded hover:bg-gray-800 text-gray-400 font-medium transition">Dashboard</a>
            <a href="products.php" class="block py-2 px-4 rounded bg-gray-800 text-white font-medium transition">Products</a>
            <a href="../index.php" class="block py-2 px-4 rounded hover:bg-gray-800 text-gray-400 font-medium transition mt-8"><i class="fa-solid fa-arrow-left mr-2"></i> Back to Site</a>
        </nav>
    </div>

    <!-- Content -->
    <div class="flex-1 p-10 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Products</h2>
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> Add Product
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Image</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td class="px-6 py-4">#<?php echo $p['id']; ?></td>
                        <td class="px-6 py-4">
                            <img src="<?php echo strpos($p['image'], 'http') === 0 ? $p['image'] : '../assets/images/' . $p['image']; ?>" class="w-10 h-10 rounded object-cover">
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700"><?php echo $p['name_en']; ?></td>
                        <td class="px-6 py-4">$<?php echo $p['price']; ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo $p['cat_name']; ?></td>
                        <td class="px-6 py-4">
                            <a href="?delete=<?php echo $p['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-2xl transform transition-all scale-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Add New Product</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (English)</label>
                    <input type="text" name="name_en" required class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (Arabic)</label>
                    <input type="text" name="name_ar" required class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50 text-right" dir="rtl">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" required class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50">
                        <?php foreach($categories as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo $c['name_en']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (En)</label>
                    <textarea name="desc_en" rows="3" class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Ar)</label>
                    <textarea name="desc_ar" rows="3" class="w-full rounded-lg border-gray-300 px-4 py-2 bg-gray-50 text-right" dir="rtl"></textarea>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <button type="submit" name="add_product" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition mt-4">Save Product</button>
        </form>
    </div>
</div>

</body>
</html>
