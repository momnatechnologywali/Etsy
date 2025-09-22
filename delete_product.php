<?php
// delete_product.php
// Delete product.
 
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
 
$sql = "DELETE FROM products WHERE id = $id AND user_id = $user_id";
 
if ($conn->query($sql) === TRUE) {
    echo "<script>window.location.href = 'profile.php';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "');</script>";
}
?>
