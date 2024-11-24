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
    echo "You do not have permission to edit this comment.";
    exit;
}

// Handle form submission to update the comment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_comment = $_POST['comment'];

    $sql = "UPDATE comments SET comment='$updated_comment' WHERE id=$comment_id";
    if ($conn->query($sql)) {
        header("Location: view_blog.php?post_id=" . $comment['post_id']);
    } else {
        echo "Error updating comment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
            color: #333;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
        }
        h1 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-control {
            padding: 15px;
            font-size: 1rem;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 1rem;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Comment</h1>
    <form method="POST">
        <div class="mb-3">
            <textarea name="comment" class="form-control" required><?= htmlspecialchars($comment['comment']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Comment</button>
        <a href="view_blog.php?post_id=<?= $comment['post_id'] ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
