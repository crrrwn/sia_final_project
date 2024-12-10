<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_posts.php");
    exit();
}

$post_id = $_GET['id'];

// Fetch the post to ensure it exists and is pending
$query = "SELECT * FROM posts WHERE id = ? AND status = 'pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Invalid post or post is not pending approval.";
    header("Location: manage_posts.php");
    exit();
}

// Update the post status to 'published'
$update_query = "UPDATE posts SET status = 'published' WHERE id = ?";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("i", $post_id);

if ($update_stmt->execute()) {
    $_SESSION['success_message'] = "Post approved successfully.";
} else {
    $_SESSION['error_message'] = "Failed to approve post. Please try again.";
}

header("Location: manage_posts.php");
exit();

