<?php
include('includes/db.php');
include('includes/functions.php');

// Check if the session is not started yet, then start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Blog</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 70px;
            background-color: #F2F2F2;
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #4CAF50; /* Main green color */
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }
        .navbar-nav .nav-link:hover {
            background-color: #45a049;
            border-radius: 5px;
        }
        .navbar-nav .nav-link {
            margin-left: 10px;
            padding: 5px 20px;
            transition: background-color 0.3s ease;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke="rgba%28255,255,255,1%29" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }
        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .card-title {
            color: #4CAF50; /* Green color for card titles */
            font-weight: 600;
        }
        .card-text {
            color: #666666;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        footer {
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: auto;
        }
        .pagination .page-link {
            color: #4CAF50;
        }
        .pagination .page-item.active .page-link {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }
        .pagination .page-link:hover {
            background-color: #E0E0E0;
        }
        .container {
            flex: 1;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">LeafPub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <!-- Custom toggler icon -->
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Wellness Practices</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?category=Healthy Eating">Healthy Eating</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?category=Self Care">Self Care</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?category=Lifestyle Tips">Lifestyle Tips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?category=all">New Posts</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                        <span class="nav-link">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></span>
                        <a class="nav-link" href="/user/user_logout.php" id="logoutBtn">Logout</a>
                    <?php else: ?>
                        <a class="nav-link" href="/user/user_login.php">Login</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Form -->
<div class="container mt-4">
    <form class="d-flex search-form" method="GET" action="index.php" id="searchForm">
        <input class="form-control me-2" type="search" name="search" id="searchInput" placeholder="Search for blog posts..." required>
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
</div>

<!-- Blog Posts Section -->
<section id="content" class="container mt-4">
    <div class="row">
        <?php
        if (isset($_GET['search'])) {
            $search_query = mysqli_real_escape_string($conn, $_GET['search']);
            $sql = "SELECT * FROM posts WHERE title LIKE '%$search_query%' OR content LIKE '%$search_query%'";
        } elseif (isset($_GET['category'])) {
            $category = mysqli_real_escape_string($conn, $_GET['category']);
            if ($category === 'all') {
                $sql = "SELECT * FROM posts ORDER BY created_at DESC";
            } else {
                $sql = "SELECT * FROM posts WHERE category='$category' ORDER BY created_at DESC";
            }
        } else {
            // Default query upang ipakita lahat ng posts sa "Wellness Practices" lamang kapag walang filter
            $sql = "SELECT * FROM posts WHERE category='Wellness Practices' ORDER BY created_at DESC";
        }        

        $results_per_page = 6;
        $result = $conn->query($sql);
        $number_of_results = $result->num_rows;

        if ($number_of_results > 0) {
            $number_of_pages = ceil($number_of_results / $results_per_page);

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $start_from = ($page - 1) * $results_per_page;

            $sql .= " LIMIT $start_from, $results_per_page";
            $posts = $conn->query($sql);

            while ($row = $posts->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card'>";
                echo "<img src='/uploads/images/" . htmlspecialchars($row['image']) . "' class='card-img-top' alt='Post Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                echo "<p class='card-text'>" . substr(htmlspecialchars($row['content']), 0, 150) . "...</p>";
                echo "<a href='/user/view_blog.php?post_id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary'>Read More</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php
            if ($number_of_results > 0) {
                for ($i = 1; $i <= $number_of_pages; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    $category_param = isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : '';
                    echo "<li class='page-item $active'><a class='page-link' href='index.php?page=$i$category_param'>" . $i . "</a></li>";
                }
            }
            ?>
        </ul>
    </nav>
</div>

</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Wellness Blog. All Rights Reserved.</p>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Form validation to ensure search input is not empty
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        const searchInput = document.getElementById('searchInput').value.trim();
        if (!searchInput) {
            alert('Please enter a search term.');
            e.preventDefault();
        }
    });

    // Logout confirmation dialog
    document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to log out?')) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
