<?php
// add_product.php
// Add new product.
 
include 'db.php';
 
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
 
$categories = $conn->query("SELECT * FROM categories");
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $user_id = $_SESSION['user_id'];
 
    // Handle image upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = $target_file;
    } else {
        echo "<script>alert('Image upload failed.');</script>";
        $image = '';
    }
 
    $sql = "INSERT INTO products (user_id, category_id, name, description, price, image, stock) 
            VALUES ($user_id, $category_id, '$name', '$description', $price, '$image', $stock)";
 
    if ($conn->query($sql) === TRUE) {
        echo "<script>window.location.href = 'profile.php';</script>";
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
    <title>Add Product</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        form { background-color: #fafafa; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        input, textarea, select { display: block; width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #f1641d; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #d85418; }
        @media (max-width: 768px) { form { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Add Product</h2>
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
            <?php endwhile; ?>
        </select>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
