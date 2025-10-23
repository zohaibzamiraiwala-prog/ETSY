<?php
// add_product.php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image']; // Assume URL for simplicity, in real use upload
    $category_id = $_POST['category_id'];
    $inventory = $_POST['inventory'];
    $seller_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id, seller_id, inventory) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsiii", $name, $description, $price, $image, $category_id, $seller_id, $inventory);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error adding product: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #f4f4f4, #e0e0e0);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #ff9900;
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background: #ff9900;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #e68a00;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Product</h2>
        <form method="POST">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" placeholder="Enter product name" required>

            <label for="description">Description</label>
            <textarea name="description" id="description" placeholder="Enter product description" required></textarea>

            <label for="price">Price ($)</label>
            <input type="number" step="0.01" name="price" id="price" placeholder="Enter price" required>

            <label for="image">Image URL</label>
            <input type="text" name="image" id="image" placeholder="Enter image URL" required>

            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" required>
                <option value="">Select Category</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="inventory">Inventory</label>
            <input type="number" name="inventory" id="inventory" placeholder="Enter inventory count" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
