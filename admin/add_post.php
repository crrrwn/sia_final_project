<?php
include('../includes/db.php');
include('../includes/functions.php');
check_login($conn);

if (!is_admin()) {
    header("Location: ../index.php");
    exit;
}

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escape user inputs to prevent SQL injection and syntax errors
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $image = $_FILES['image']['name'];
    $target = "../uploads/images/" . basename($image);

    $sql = "INSERT INTO posts (title, content, image, category) VALUES ('$title', '$content', '$image', '$category')";

    if ($conn->query($sql)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $message = "<div class='alert alert-success'>Post uploaded successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Failed to add post: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>

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
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 550px;
            margin: auto;
        }
        .post-form h1 {
            color: #4CAF50;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }
        .btn-primary, .btn-secondary {
            border-radius: 50px;
            font-size: 16px;
            padding: 10px 20px;
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
        .alert {
            margin-top: 20px;
        }
        textarea {
            height: 150px;
        }
    </style>
</head>
<body>

<div class="post-form">
    <h1>Add New Post</h1>

    <!-- Display Success/Error Message -->
    <?= $message ?>

    <form method="POST" enctype="multipart/form-data" id="postForm">
        <input type="text" class="form-control" name="title" id="title" placeholder="Post Title" required>
        <textarea name="content" class="form-control" id="content" placeholder="Write your content here..." required></textarea>
        <input type="file" class="form-control" name="image" id="image" required>
        <select name="category" class="form-control" id="category" required>
            <option value="" disabled selected>Select Category</option>
            <option value="Wellness Practices">Wellness Practices</option>
            <option value="Healthy Eating">Healthy Eating</option>
            <option value="Self Care">Self Care</option>
            <option value="Lifestyle Tips">Lifestyle Tips</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Add Post</button>
        <a href="dashboard.php" class="btn btn-secondary w-100">Dashboard</a> <!-- Dashboard Button -->
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Simple form validation
    document.getElementById('postForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const category = document.getElementById('category').value;
        const image = document.getElementById('image').files.length;

        if (!title || !content || !category || image === 0) {
            alert('Please fill out all fields and upload an image.');
            e.preventDefault();
        }
    });
</script>

</body>
</html>
