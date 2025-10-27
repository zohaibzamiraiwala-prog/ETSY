<?php
// search.php
include 'db.php';

$query = isset($_GET['q']) ? $_GET['q'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

$sql = "SELECT * FROM products WHERE 1=1";
if ($query) $sql .= " AND name LIKE '%$query%'";
if ($category) $sql .= " AND category_id = $category";
if ($min_price) $sql .= " AND price >= $min_price";
if ($max_price) $sql .= " AND price <= $max_price";

$results = $conn->query($sql);
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 1200px; margin: auto; }
        form { background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input, select { padding: 10px; margin: 5px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #ff9900; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        .products { display: flex; flex-wrap: wrap; justify-content: space-around; }
        .product { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin: 10px; width: 200px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .product:hover { transform: scale(1.05); }
        .product img { max-width: 100%; height: auto; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <form method="GET">
            <input type="text" name="q" placeholder="Search by name" value="<?php echo $query; ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $category) echo 'selected'; ?>><?php echo $cat['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $min_price; ?>">
            <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $max_price; ?>">
            <button type="submit">Search</button>
        </form>
        <div class="products">
            <?php while ($row = $results->fetch_assoc()): ?>
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
