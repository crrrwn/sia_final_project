<?php
// db.php: Database connection setup
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'blog_website';

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
