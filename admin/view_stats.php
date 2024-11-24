<?php
include('../includes/db.php');
include('../includes/functions.php');
check_login($conn);

if (!is_admin()) {
    header("Location: ../index.php");
    exit;
}

// Count total posts
$total_posts = $conn->query("SELECT COUNT(*) AS count FROM posts")->fetch_assoc()['count'];

// Count total comments
$total_comments = $conn->query("SELECT COUNT(*) AS count FROM comments")->fetch_assoc()['count'];

// Count total users
$total_users = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Stats</title>

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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .stats-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }
        .stats-container h1 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        .stat-item {
            font-size: 1.5rem;
            margin: 20px 0;
        }
        .stat-item span {
            font-weight: 700;
            color: #2d9cdb;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        ul li {
            font-size: 1.25rem;
            margin: 15px 0;
        }
        ul li span {
            color: #4CAF50;
            font-weight: bold;
        }
        .btn-dashboard {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-dashboard:hover {
            background-color: #45a049;
            color: white;
        }
    </style>
</head>
<body>

<div class="stats-container">
    <h1>Blog Statistics</h1>
    <ul>
        <li>Total Posts: <span><?= $total_posts ?></span></li>
        <li>Total Comments: <span><?= $total_comments ?></span></li>
        <li>Total Users: <span><?= $total_users ?></span></li>
    </ul>
    
    <a href="dashboard.php" class="btn-dashboard">Go to Dashboard</a>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

