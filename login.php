<?php
// login.php
// User login page.
 
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
 
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('No user found.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #fff; color: #333; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        form { background-color: #fafafa; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        input { display: block; width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #f1641d; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #d85418; }
        @media (max-width: 768px) { form { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="signup.php">Signup</a></p>
    </form>
</body>
</html>
