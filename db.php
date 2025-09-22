<?php
// db.php
// Database connection file. Include this in other PHP files.
 
$servername = "localhost"; // Replace with your actual host if not localhost (e.g., for remote DB)
$username = "uws1gwyttyg2r";
$password = "k1tdlhq4qpsf";
$dbname = "dbpjbq0nxhjhzm";
 
$conn = new mysqli($servername, $username, $password, $dbname);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
// Start session for all pages
session_start();
?>
