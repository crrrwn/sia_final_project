<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $comment_id = $_POST['id'];
    $content = $_POST['content'];
    
    if (update_comment($conn, $comment_id, $_SESSION['user_id'], $content)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update comment']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}

