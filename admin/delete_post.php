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

if (delete_post($conn, $post_id)) {
    $_SESSION['success_message'] = "Post deleted successfully.";
} else {
    $_SESSION['error_message'] = "Failed to delete post. Please try again.";
}

header("Location: manage_posts.php");
exit();

