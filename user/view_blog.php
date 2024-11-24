<?php
include('../includes/db.php');
include('../includes/functions.php');

// Start session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if post_id is set in the URL
if (!isset($_GET['post_id'])) {
    echo "No post selected!";
    exit;
}

$post_id = intval($_GET['post_id']);  // Sanitize the post_id to avoid SQL injection

// Increment the views count for the post
$conn->query("UPDATE posts SET views = views + 1 WHERE id = $post_id");

// Fetch the post details from the database
try {
    $post = $conn->query("SELECT * FROM posts WHERE id=$post_id")->fetch_assoc();
    
    if (!$post) {
        echo "Post not found!";
        exit;
    }
} catch (mysqli_sql_exception $e) {
    echo "Error fetching post: " . $e->getMessage();
    exit;
}

// Fetch comments for the post
$comments = $conn->query("SELECT * FROM comments WHERE post_id=$post_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .post-title {
            color: #4CAF50;
            font-weight: 600;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .post-image {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            margin-bottom: 20px;
            border-radius: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .post-content {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }
        .views-count {
            font-size: 1rem;
            color: #777;
            margin-bottom: 20px;
        }
        .comment-section {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .comment-section h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .comment {
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
        }
        .comment small {
            color: #777;
        }
        .comment-actions {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .comment-actions a {
            margin-left: 10px;
            color: #4CAF50;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }
        .comment-actions a:hover {
            color: #45a049;
        }
        .comment-form textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        footer {
            margin-top: 50px;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>

    <!-- Display the image if it exists -->
    <?php if (!empty($post['image'])): ?>
        <img src="../uploads/images/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
    <?php else: ?>
        <p>No image uploaded for this post.</p>
    <?php endif; ?>

    <div class="views-count">Views: <?= htmlspecialchars($post['views']) ?></div>
    <div class="post-content">
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    </div>

    <div class="comment-section">
        <h2>Comments</h2>
        <?php while ($comment = $comments->fetch_assoc()): ?>
            <div class="comment">
                <p><?= htmlspecialchars($comment['comment']) ?></p>
                <small>Posted on <?= htmlspecialchars($comment['created_at']) ?></small>
                <!-- Edit and Delete buttons -->
                <div class="comment-actions">
                    <a href="edit_comment.php?comment_id=<?= $comment['id'] ?>">Edit</a>
                    <a href="delete_comment.php?comment_id=<?= $comment['id'] ?>" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Comment form that submits to comment.php -->
            <form method="POST" action="comment.php" class="comment-form">
                <textarea name="comment" placeholder="Leave a comment" required></textarea>
                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        <?php else: ?>
            <p>You need to <a href="../user/user_login.php">login</a> to comment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Wellness Blog. All Rights Reserved.</p>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
