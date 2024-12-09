<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Builders/Pandayan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font for clean typography -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #000; 
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid #fff;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 500;
            color: var(--brand-green) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .navbar-brand img {
                height: 30px;
            }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-nav .nav-link {
            color: #ddd !important;
            font-size: 1.1rem;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--brand-green) !important;
            border-bottom: 2px solid var(--brand-green);
        }

        .navbar-toggler-icon {
            background-color: #fff;
        }

        .container {
            margin-top: 30px;
        }

        .navbar-nav .nav-item.active .nav-link {
            font-weight: 600;
            color: var(--brand-green) !important;
            border-bottom: 2px solid var(--brand-green);
        }

        /* Adjusting for smaller screens */
        @media (max-width: 767px) {
            .navbar-nav .nav-link {
                margin-right: 10px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="/index.php"><img src="/uploads/logobp.jpg" alt="Logo">Builders/Pandayan </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/index.php">Home</a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (is_admin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard.php">Admin Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/manage_posts.php">Manage Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/manage_categories.php">Manage Categories</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container">
    <!-- Content goes here -->
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
