<?php
include('../includes/db.php');
include('../includes/functions.php');
check_login($conn);

if (!is_admin()) {
    header("Location: ../index.php");
}

$post_id = intval($_GET['post_id']); // Sanitize the post_id
$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // First, delete all comments associated with the post
    $delete_comments_sql = "DELETE FROM comments WHERE post_id=$post_id";
    if ($conn->query($delete_comments_sql)) {
        // Now, delete the post itself
        $delete_post_sql = "DELETE FROM posts WHERE id=$post_id";
        if ($conn->query($delete_post_sql)) {
            header("Location: dashboard.php");  // Redirect to the admin dashboard
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Failed to delete post: " . $conn->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Failed to delete comments: " . $conn->error . "</div>";
    }
}

$post = $conn->query("SELECT * FROM posts WHERE id=$post_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f9fafb;
            color: #333;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .delete-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 450px;
            margin: auto;
        }
        .delete-container h1 {
            color: #d9534f;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        .delete-container p {
            font-size: 1rem;
            margin-bottom: 20px;
        }
        .btn-danger {
            background-color: #d9534f;
            border-color: #d9534f;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
        }
        .btn-secondary {
            margin-top: 10px;
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>

<div class="delete-container">
    <!-- Display the message at the top -->
    <?= $message ?>

    <h1>Delete Post</h1>
    <p>Are you sure you want to delete the post titled "<strong><?= htmlspecialchars($post['title']) ?></strong>"?</p>
    <form method="POST">
        <button type="submit" class="btn btn-danger w-100 mb-2">Yes, Delete</button>
        <a href="dashboard.php" class="btn btn-secondary w-100">Cancel</a>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
