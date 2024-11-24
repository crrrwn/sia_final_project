<?php
include('../includes/db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/user_login.php");
    exit;
}

// Check if comment_id is provided
if (!isset($_GET['comment_id'])) {
    echo "No comment selected!";
    exit;
}

$comment_id = intval($_GET['comment_id']); // Sanitize comment_id

// Fetch the comment from the database
$sql = "SELECT * FROM comments WHERE id=$comment_id";
$result = $conn->query($sql);
$comment = $result->fetch_assoc();

if (!$comment) {
    echo "Comment not found!";
    exit;
}

// Check if the user is the comment owner or an admin
if ($comment['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
    echo "You do not have permission to delete this comment.";
    exit;
}

// Delete the comment
$sql = "DELETE FROM comments WHERE id=$comment_id";
if ($conn->query($sql)) {
    header("Location: view_blog.php?post_id=" . $comment['post_id']);
} else {
    echo "Error deleting comment: " . $conn->error;
}
?>
