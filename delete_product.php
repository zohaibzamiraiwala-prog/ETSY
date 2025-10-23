<?php
// delete_product.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo "<script>window.location.href = 'profile.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
