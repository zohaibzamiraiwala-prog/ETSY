<?php
// index.php - Homepage
session_start();
include 'db.php';

$featured = $conn->query("SELECT * FROM products ORDER BY views DESC LIMIT 4"); // Featured by views
$trending = $conn->query("SELECT * FROM products ORDER BY sales DESC LIMIT 4"); // Trending by sales
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etsy Clone Homepage</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; color: #333; }
        header { background: #ff9900; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .products { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .product { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin: 10px; width: 200px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .product:hover { transform: scale(1.05); }
        .product img { max-width: 100%; height: auto; border-radius: 8px; }
        button { background: #ff9900; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        button:hover { background: #e68a00; }
        nav { background: #333; color: white; padding: 10px; }
        nav a { color: white; margin: 0 15px; text-decoration: none; }
        @media (max-width: 768px) { .product { width: 100%; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Etsy Clone</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="cart.php">Cart</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="signup.php">Signup</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <a href="search.php">Search</a>
            <a href="add_product.php">Add Product</a> <!-- If seller -->
        </nav>
    </header>
    <div class="container">
        <h2>Featured Products</h2>
        <div class="products">
            <?php while ($row = $featured->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo $row['price']; ?></p>
                    <button onclick="window.location.href='product_details.php?id=<?php echo $row['id']; ?>'">View</button>
                </div>
            <?php endwhile; ?>
        </div>
        <h2>Trending Products</h2>
        <div class="products">
            <?php while ($row = $trending->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo $row['price']; ?></p>
                    <button onclick="window.location.href='product_details.php?id=<?php echo $row['id']; ?>'">View</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
