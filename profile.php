<?php
// profile.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
 
// For sellers, show their products
if ($_SESSION['role'] == 'seller') {
    $products = $conn->query("SELECT * FROM products WHERE seller_id = $user_id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { color: #ff9900; }
        .products { display: flex; flex-wrap: wrap; }
        .product { margin: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile: <?php echo $user['username']; ?></h2>
        <p>Email: <?php echo $user['email']; ?></p>
        <p>Role: <?php echo $user['role']; ?></p>
        <?php if ($_SESSION['role'] == 'seller'): ?>
            <h3>Your Products</h3>
            <div class="products">
                <?php while ($row = $products->fetch_assoc()): ?>
                    <div class="product">
                        <h4><?php echo $row['name']; ?></h4>
                        <button onclick="window.location.href='edit_product.php?id=<?php echo $row['id']; ?>'">Edit</button>
                        <button onclick="if(confirm('Delete?')) window.location.href='delete_product.php?id=<?php echo $row['id']; ?>'">Delete</button>
                    </div>
                <?php endwhile; ?>
            </div>
            <button onclick="window.location.href='add_product.php'">Add New Product</button>
        <?php endif; ?>
    </div>
</body>
</html>
