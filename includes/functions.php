<?php
session_start();

function check_login($conn) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /user/user_login.php");
        exit;
    }
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function fetch_category_posts($conn, $category) {
    $sql = "SELECT * FROM posts WHERE category='$category' ORDER BY created_at DESC";
    return $conn->query($sql);
}
?>
