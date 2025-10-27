<?php
// product_details.php
session_start();
include 'db.php';
 
$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if (!$product) {
    echo "Product not found";
    exit;
}
 
// Increment views
$conn->query("UPDATE products SET views = views + 1 WHERE id = $id");
 
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $quantity = 1; // Default
 
    // Check if already in cart
    $check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $id");
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $id, $quantity);
        $stmt->execute();
    }
    echo "<script>window.location.href = 'cart.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); display: flex; }
        img { max-width: 400px; height: auto; border-radius: 8px; }
        .details { padding-left: 20px; }
        button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #e68a00; }
        @media (max-width: 768px) { .container { flex-direction: column; } img { max-width: 100%; } }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <div class="details">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <h3>$<?php echo $product['price']; ?></h3>
            <p>Inventory: <?php echo $product['inventory']; ?></p>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login to Add to Cart</button>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
