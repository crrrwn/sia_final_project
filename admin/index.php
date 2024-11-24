<?php
include('../includes/db.php');
include('../includes/functions.php');

// Check if a session is not already started before calling session_start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Fetch all the posts from the database
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check for messages in the URL (like success or error)
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Blog Posts</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7f6;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .navbar {
            margin-bottom: 20px;
            background-color: #4CAF50;
            padding: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }
        .navbar a:hover {
            color: #ddd;
        }
        h1 {
            color: #4CAF50;
        }
        .table thead {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .message {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div>
            <a href="add_post.php">Add New Post</a>
            <a href="admin_logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Message Display Section -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success message">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h1 class="mb-4">Manage Blog Posts</h1>

    <!-- Table to display all blog posts -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="/uploads/images/<?= htmlspecialchars($row['image']); ?>" alt="Post Image" width="100" height="100">
                            <?php else: ?>
                                <p>No image</p>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="edit_post.php?post_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_post.php?post_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No blog posts found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
