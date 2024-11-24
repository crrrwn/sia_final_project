<?php
include('../includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "You need to be logged in to comment.";
        exit;
    }

    $post_id = intval($_POST['post_id']);  // Sanitize post_id
    $comment_text = $_POST['comment'];
    $user_id = $_SESSION['user_id'];  // Get user ID from session

    // Insert the comment into the database
    $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment_text')";
    if ($conn->query($sql)) {
        // Comment added successfully, redirect back to the blog post
        header("Location: view_blog.php?post_id=$post_id");
    } else {
        echo "Error adding comment: " . $conn->error;
    }
}
?>
