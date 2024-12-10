<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit();
}

$category_id = $_GET['id'];

$query = "DELETE FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);

if ($stmt->execute()) {
    header("Location: manage_categories.php?success=1");
} else {
    header("Location: manage_categories.php?error=1");
}
exit();
?>