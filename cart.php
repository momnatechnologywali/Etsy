<?php
// cart.php
// Cart page.
 
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    // Check if already in cart
    $check = $conn->query("SELECT * FROM cart_items WHERE user_id = $user_id AND product_id = $product_id");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        $conn->query("INSERT INTO cart_items (user_id, product_id) VALUES ($user_id, $product_id)");
    }
    echo "<script>window.location.href = 'cart.php';</script>";
}
 
// Remove from cart
if (isset($_GET['remove'])) {
    $item_id = $_GET['remove'];
    $conn->query("DELETE FROM cart_items WHERE id = $item_id AND user_id = $user_id");
    echo "<script>window.location.href = 'cart.php';</script>";
}
 
// Get cart items
$cart = $conn->query("SELECT ci.*, p.name, p.price, p.image FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = $user_id");
 
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; color: #333; margin: 0; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f1641d; color: white; }
        img { max-width: 100px; height: auto; }
        a { color: #f1641d; text-decoration: none; }
        button { background-color: #f1641d; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
        @media (max-width: 768px) { table { font-size: 0.8em; } img { max-width: 50px; } }
    </style>
</head>
<body>
    <h2>Your Cart</h2>
    <?php if ($cart->num_rows > 0): ?>
        <table>
            <tr><th>Image</th><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>
            <?php while ($item = $cart->fetch_assoc()): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"></td>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo $subtotal; ?></td>
                    <td><a href="cart.php?remove=<?php echo $item['id']; ?>">Remove</a></td>
                </tr>
            <?php endwhile; ?>
            <tr><td colspan="4">Total</td><td colspan="2">$<?php echo $total; ?></td></tr>
        </table>
        <a href="checkout.php"><button>Proceed to Checkout</button></a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
    <a href="index.php">Continue Shopping</a>
</body>
</html>
