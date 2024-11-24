<?php
include('../includes/db.php');
include('../includes/functions.php');

// Check if a session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

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
        .navbar {
            background-color: #4CAF50;
            padding: 15px;
            margin-bottom: 30px;
        }
        .navbar a {
            color: white;
            font-weight: 500;
            text-decoration: none;
            margin-right: 20px;
        }
        .navbar a:hover {
            color: #e0e0e0;
        }
        h1 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .table thead {
            background-color: #4CAF50;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary, .btn-green {
            background-color: #4CAF50;
            border-color: #4CAF50;
            border-radius: 50px;
            padding: 6px 12px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover, .btn-green:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
            border-radius: 50px;
            padding: 6px 12px;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div>
            <a href="add_post.php" class="btn btn-green">Add New Post</a>
            <a href="view_stats.php" class="btn btn-green">Blog Status</a>
            <a href="admin_logout.php" class="btn btn-green">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <h1>Welcome, 
        <?php 
        if (isset($_SESSION['username'])) {
            echo htmlspecialchars($_SESSION['username']);
        } else {
            echo "Admin";
        }
        ?>! Below are the blog posts you can manage:
    </h1>

    <!-- Table to display all blog posts -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Image</th>
                <th>Views</th>
                <th>Comments</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch all the posts and the number of comments for each post
            $sql = "
                SELECT posts.*, 
                       (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count 
                FROM posts 
                ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
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
                        <td><?= htmlspecialchars($row['views']); ?></td> <!-- Display the number of views -->
                        <td><?= htmlspecialchars($row['comment_count']); ?></td> <!-- Display the number of comments -->
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                        <td>
                            <a href="edit_post.php?post_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_post.php?post_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                        </td>
                    </tr>
            <?php
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="8" class="text-center">No blog posts found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
