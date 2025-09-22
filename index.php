<?php
// index.php
// Homepage showcasing featured and trending products, search, filters.
 
include 'db.php';
 
// Handle search and filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : 999999;
 
$query = "SELECT p.*, u.username, c.name as category_name FROM products p 
          JOIN users u ON p.user_id = u.id 
          JOIN categories c ON p.category_id = c.id WHERE 1=1";
 
if ($search) {
    $query .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
}
if ($category) {
    $query .= " AND c.id = $category";
}
$query .= " AND p.price BETWEEN $min_price AND $max_price";
$query .= " ORDER BY p.created_at DESC LIMIT 20"; // For trending/featured, limit to recent
 
$result = $conn->query($query);
 
// Get categories for filter
$cat_result = $conn->query("SELECT * FROM categories");
 
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etsy Clone - Homepage</title>
    <style>
        /* Amazing CSS: Real-looking Etsy style - clean, modern, responsive */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-color: #fff; color: #333; }
        header { background-color: #f1641d; color: white; padding: 20px; text-align: center; }
        header h1 { margin: 0; font-size: 2.5em; }
        nav { display: flex; justify-content: space-around; background-color: #eee; padding: 10px; }
        nav a { text-decoration: none; color: #333; font-weight: bold; }
        .search-form { margin: 20px auto; width: 80%; max-width: 800px; }
        .search-form input[type="text"] { width: 70%; padding: 10px; font-size: 1em; }
        .search-form button { padding: 10px 20px; background-color: #f1641d; color: white; border: none; cursor: pointer; }
        .filters { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .filters select, .filters input { padding: 10px; }
        .products { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .product { background-color: #fafafa; border: 1px solid #ddd; padding: 15px; text-align: center; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .product:hover { transform: scale(1.05); }
        .product img { max-width: 100%; height: auto; border-radius: 8px; }
        .product h3 { margin: 10px 0; color: #f1641d; }
        .product p { font-size: 0.9em; }
        .product button { background-color: #f1641d; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 5px; }
        footer { text-align: center; padding: 10px; background-color: #eee; }
        @media (max-width: 768px) { .products { grid-template-columns: 1fr; } .search-form { width: 95%; } .search-form input[type="text"] { width: 60%; } }
    </style>
</head>
<body>
    <header>
        <h1>Etsy Clone</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <?php if ($is_logged_in): ?>
            <a href="profile.php">Profile</a>
            <a href="add_product.php">Add Product</a>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Signup</a>
        <?php endif; ?>
    </nav>
    <form class="search-form" method="GET">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>
    <div class="filters">
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($cat = $cat_result->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($category == $cat['id']) echo 'selected'; ?>><?php echo $cat['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo $min_price; ?>">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo $max_price; ?>">
        <button type="submit">Filter</button>
    </div>
    <section class="products">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>$<?php echo $row['price']; ?></p>
                    <p>Category: <?php echo $row['category_name']; ?></p>
                    <p>Seller: <?php echo $row['username']; ?></p>
                    <?php if ($is_logged_in): ?>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </section>
    <footer>
        <p>&copy; 2025 Etsy Clone</p>
    </footer>
    <script>
        // JS for any dynamic, but redirection uses window.location
    </script>
</body>
</html>
