<?php
// profile.php
// User profile management.
 
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$user_id = $_SESSION['user_id'];
 
// Get user's products
$products = $conn->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; color: #333; margin: 0; padding: 20px; }
        h2 { color: #f1641d; }
        .products { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product { background-color: #fafafa; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .product img { max-width: 100%; height: auto; }
        a { text-decoration: none; color: #f1641d; }
        @media (max-width: 768px) { .products { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <a href="add_product.php">Add New Product</a>
    <h3>Your Products</h3>
    <section class="products">
        <?php if ($products->num_rows > 0): ?>
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h4><?php echo $row['name']; ?></h4>
                    <p>$<?php echo $row['price']; ?></p>
                    <p>Stock: <?php echo $row['stock']; ?></p>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products listed yet.</p>
        <?php endif; ?>
    </section>
    <a href="index.php">Back to Home</a>
</body>
</html>
