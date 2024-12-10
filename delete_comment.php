<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$user_data = check_login($conn);

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$comment_id = $_GET['id'];

// Fetch the comment
$query = "SELECT * FROM comments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

// Check if the comment exists and belongs to the current user
if (!$comment || $comment['user_id'] != $user_data['id']) {
    header("Location: index.php");
    exit();
}

if (delete_comment($conn, $comment_id, $user_data['id'])) {
    header("Location: view_post.php?id=" . $comment['post_id']);
} else {
    header("Location: view_post.php?id=" . $comment['post_id'] . "&error=1");
}
exit();
?>