<?php
include('../includes/db.php');
include('../includes/functions.php');

// Check if a session is not started, then start a session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}

// Check if post_id is set in the URL
if (!isset($_GET['post_id'])) {
    echo "No post selected!";
    exit;
}

$post_id = intval($_GET['post_id']);  // Sanitize post_id

// Fetch the existing post details
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

$message = ''; // Initialize message variable

// Handle form submission for editing the post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape the user inputs to prevent SQL injection and syntax errors
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/images/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $_POST['existing_image'];
    }

    $sql = "UPDATE posts SET title='$title', content='$content', image='$image', category='$category' WHERE id=$post_id";
    
    if ($conn->query($sql)) {
        $message = "<div class='alert alert-success'>Post updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error updating post: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7f6;
            color: #333;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        .post-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .post-form h1 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-control {
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: none;
        }
        .btn-primary, .btn-secondary {
            border-radius: 50px;
            padding: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }
        .image-preview img {
            max-width: 120px;
            border-radius: 8px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="post-form">
    <h1>Edit Post</h1>

    <!-- Display Success/Error Message -->
    <?= $message ?>

    <form method="POST" enctype="multipart/form-data" id="editPostForm">
        <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        <textarea name="content" class="form-control" required><?= htmlspecialchars($post['content']) ?></textarea>
        <input type="file" class="form-control" name="image">
        <input type="hidden" name="existing_image" value="<?= htmlspecialchars($post['image']) ?>">
        <div class="image-preview">
            <img src="../uploads/images/<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
        </div>
        <select name="category" class="form-control" required>
            <option value="Wellness Practices" <?= $post['category'] == 'Wellness Practices' ? 'selected' : '' ?>>Wellness Practices</option>
            <option value="Healthy Eating" <?= $post['category'] == 'Healthy Eating' ? 'selected' : '' ?>>Healthy Eating</option>
            <option value="Self Care" <?= $post['category'] == 'Self Care' ? 'selected' : '' ?>>Self Care</option>
            <option value="Lifestyle Tips" <?= $post['category'] == 'Lifestyle Tips' ? 'selected' : '' ?>>Lifestyle Tips</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Update Post</button>
        <a href="dashboard.php" class="btn btn-secondary w-100">Dashboard</a> <!-- Dashboard Button -->
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    document.getElementById('editPostForm').addEventListener('submit', function(e) {
        const title = document.querySelector('input[name="title"]').value.trim();
        const content = document.querySelector('textarea[name="content"]').value.trim();
        const category = document.querySelector('select[name="category"]').value;

        if (!title || !content || !category) {
            alert('Please fill out all required fields.');
            e.preventDefault();
        }
    });
</script>

</body>
</html>
