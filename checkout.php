<?php
// checkout.php
// Checkout page with dummy payment.
 
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Get cart items
$cart = $conn->query("SELECT ci.*, p.price FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = $user_id");
 
$total = 0;
while ($item = $cart->fetch_assoc()) {
    $total += $item['price'] * $item['quantity'];
}
 
// Process checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Create order
    $sql = "INSERT INTO orders (user_id, total, status) VALUES ($user_id, $total, 'paid')";
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;
        // Move cart to order_items
        $cart->data_seek(0); // Reset result pointer
        while ($item = $cart->fetch_assoc()) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)");
            // Update stock
            $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
        }
        // Clear cart
        $conn->query("DELETE FROM cart_items WHERE user_id = $user_id");
        echo "<script>alert('Payment successful (dummy)! Order placed.'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        form { background-color: #fafafa; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        h2 { color: #f1641d; }
        p { font-size: 1.2em; }
        button { background-color: #f1641d; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; width: 100%; }
        button:hover { background-color: #d85418; }
        @media (max-width: 768px) { form { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Checkout</h2>
        <p>Total Amount: $<?php echo $total; ?></p>
        <p>This is a dummy payment. Clicking Pay will simulate a successful transaction.</p>
        <button type="submit">Pay Now (Dummy)</button>
    </form>
</body>
</html>
