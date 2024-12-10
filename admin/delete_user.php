<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// Prevent admin from deleting themselves
if ($user_id == $user_data['id']) {
    header("Location: manage_users.php?error=2");
    exit();
}

if (delete_user($conn, $user_id)) {
    header("Location: manage_users.php?success=1");
} else {
    header("Location: manage_users.php?error=1");
}
exit();
?>