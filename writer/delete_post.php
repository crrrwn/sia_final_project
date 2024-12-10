<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'writer') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET['id'];

// Check if the post belongs to the current user
$query = "SELECT * FROM posts WHERE id = ? AND author_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $post_id, $user_data['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: dashboard.php?error=1");
    exit();
}

if (delete_post($conn, $post_id)) {
    header("Location: dashboard.php?success=1");
} else {
    header("Location: dashboard.php?error=2");
}
exit();
?>