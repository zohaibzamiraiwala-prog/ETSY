<?php
// remove_from_cart.php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
if ($stmt->execute()) {
    echo "<script>window.location.href = 'cart.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
