<?php
// cart.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$cart_items = $conn->query("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = $user_id");

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        img { max-width: 50px; height: auto; }
        button { background: #ff9900; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #e68a00; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <table>
            <tr><th>Image</th><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>
            <?php while ($item = $cart_items->fetch_assoc()): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><img src="<?php echo $item['image']; ?>" alt=""></td>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo $subtotal; ?></td>
                    <td><button onclick="window.location.href='remove_from_cart.php?id=<?php echo $item['id']; ?>'">Remove</button></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <h3>Total: $<?php echo $total; ?></h3>
        <button onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
    </div>
</body>
</html>
