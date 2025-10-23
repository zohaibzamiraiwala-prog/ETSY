<?php
// checkout.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$cart_items = $conn->query("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");

$total = 0;
while ($item = $cart_items->fetch_assoc()) {
    $total += $item['price'] * $item['quantity'];
}
$cart_items->data_seek(0); // Reset pointer

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Add order items
    while ($item = $cart_items->fetch_assoc()) {
        $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $stmt_item->execute();

        // Update sales and inventory
        $conn->query("UPDATE products SET sales = sales + {$item['quantity']}, inventory = inventory - {$item['quantity']} WHERE id = {$item['product_id']}");
    }

    // Clear cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    // Dummy payment
    $conn->query("UPDATE orders SET status = 'paid' WHERE id = $order_id");

    echo "<script>alert('Payment successful (dummy)!'); window.location.href = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h3 { color: #ff9900; }
        button { background: #ff9900; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; width: 100%; }
        button:hover { background: #e68a00; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <p>Total Amount: $<?php echo $total; ?></p>
        <form method="POST">
            <button type="submit">Pay Now (Dummy)</button>
        </form>
    </div>
</body>
</html>
