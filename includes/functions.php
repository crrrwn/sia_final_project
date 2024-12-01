<?php
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

function is_admin() {
    return isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin';
}

function check_admin() {
    if (!is_admin()) {
        header("Location: ../login.php");
        exit();
    }
}

function get_posts($limit = 10, $offset = 0) {
    global $conn;
    $sql = "SELECT p.*, u.username, c.name as category 
            FROM posts p 
            JOIN users u ON p.user_id = u.id 
            JOIN categories c ON p.category_id = c.id 
            ORDER BY p.created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function get_categories() {
    global $conn;
    return mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
}