<?php
// edit_product.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id AND seller_id = {$_SESSION['user_id']}")->fetch_assoc();
if (!$product) {
    echo "Product not found";
    exit;
}
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $category_id = $_POST['category_id'];
    $inventory = $_POST['inventory'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category_id=?, inventory=? WHERE id=?");
    $stmt->bind_param("ssdsiii", $name, $description, $price, $image, $category_id, $inventory, $id);
    if ($stmt->execute()) {
        echo "<script>window.location.href = 'profile.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ff9900; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 5px; }
        button:hover { background: #e68a00; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Edit Product</h2>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
        <textarea name="description" required><?php echo $product['description']; ?></textarea>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        <input type="text" name="image" value="<?php echo $product['image']; ?>" required>
        <select name="category_id" required>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $product['category_id']) echo 'selected'; ?>><?php echo $cat['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="number" name="inventory" value="<?php echo $product['inventory']; ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
