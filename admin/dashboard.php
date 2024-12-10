<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch statistics
$total_posts = $conn->query("SELECT COUNT(*) FROM posts")->fetch_row()[0];
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_comments = $conn->query("SELECT COUNT(*) FROM comments")->fetch_row()[0];
$total_views = $conn->query("SELECT SUM(views) FROM posts")->fetch_row()[0];
$pending_posts = $conn->query("SELECT COUNT(*) FROM posts WHERE status = 'pending'")->fetch_row()[0];

// Fetch most viewed posts
$most_viewed_posts = $conn->query("
    SELECT title, views 
    FROM posts 
    ORDER BY views DESC 
    LIMIT 5
");

// Fetch most commented posts
$most_commented_posts = $conn->query("
    SELECT p.title, COUNT(c.id) as comment_count 
    FROM posts p 
    LEFT JOIN comments c ON p.id = c.post_id 
    GROUP BY p.id 
    ORDER BY comment_count DESC 
    LIMIT 5
");

// Fetch posts per category
$posts_per_category = $conn->query("
    SELECT c.name, COUNT(p.id) as post_count 
    FROM categories c 
    LEFT JOIN posts p ON c.id = p.category_id 
    GROUP BY c.id
");

$page_title = 'Admin Dashboard';
include '../includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --secondary-color: #6A9C89;
        --text-primary: #000000;
        --text-secondary: #333333;
        --background-light: #E9EFEC;
        --background-white: #FFFFFF;
        --accent-color: #C4DAD2;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .dashboard-container {
        display: flex;
        min-height: calc(100vh - 80px);
    }

    .sidebar {
        width: 250px;
        background-color: var(--primary-color);
        padding: 2rem 0;
        position: fixed;
        height: calc(100vh - 80px);
        overflow-y: auto;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 0.5rem;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: var(--background-white);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background-color: var(--secondary-color);
        border-left-color: var(--accent-color);
    }

    .sidebar-menu i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }

    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 2rem;
        background-color: var(--background-light);
    }

    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--background-white);
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-title {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background-color: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background-color: var(--background-white);
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .chart-title {
        font-size: 1.25rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .donut-chart-container {
        height: 400px;
        position: relative;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .main-content {
            margin-left: 0;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="manage_posts.php">
                    <i class="fas fa-file-alt"></i>
                    Manage Posts
                </a>
            </li>
            <li>
                <a href="add_post.php">
                    <i class="fas fa-plus"></i>
                    Add New Post
                </a>
            </li>
            <li>
                <a href="manage_categories.php">
                    <i class="fas fa-folder"></i>
                    Manage Categories
                </a>
            </li>
            <li>
                <a href="manage_users.php">
                    <i class="fas fa-users"></i>
                    Manage Users
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Admin Dashboard</h1>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-title">Total Posts</div>
                <div class="stat-value"><?php echo number_format($total_posts); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-title">Total Users</div>
                <div class="stat-value"><?php echo number_format($total_users); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-title">Total Comments</div>
                <div class="stat-value"><?php echo number_format($total_comments); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-title">Total Views</div>
                <div class="stat-value"><?php echo number_format($total_views); ?></div>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <h2 class="chart-title">Most Viewed Posts</h2>
                <div class="chart-container">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2 class="chart-title">Most Commented Posts</h2>
                <div class="chart-container">
                    <canvas id="commentsChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h2 class="chart-title">Posts per Category</h2>
                <div class="donut-chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Most Viewed Posts Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'bar',
        data: {
            labels: [<?php 
                while($post = $most_viewed_posts->fetch_assoc()) {
                    echo "'" . addslashes($post['title']) . "',";
                }
            ?>],
            datasets: [{
                label: 'Views',
                data: [<?php 
                    $most_viewed_posts->data_seek(0);
                    while($post = $most_viewed_posts->fetch_assoc()) {
                        echo $post['views'] . ",";
                    }
                ?>],
                backgroundColor: '#6A9C89',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Most Commented Posts Chart
    const commentsCtx = document.getElementById('commentsChart').getContext('2d');
    new Chart(commentsCtx, {
        type: 'bar',
        data: {
            labels: [<?php 
                while($post = $most_commented_posts->fetch_assoc()) {
                    echo "'" . addslashes($post['title']) . "',";
                }
            ?>],
            datasets: [{
                label: 'Comments',
                data: [<?php 
                    $most_commented_posts->data_seek(0);
                    while($post = $most_commented_posts->fetch_assoc()) {
                        echo $post['comment_count'] . ",";
                    }
                ?>],
                backgroundColor: '#6A9C89',
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Posts per Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [<?php 
                while($category = $posts_per_category->fetch_assoc()) {
                    echo "'" . addslashes($category['name']) . "',";
                }
            ?>],
            datasets: [{
                data: [<?php 
                    $posts_per_category->data_seek(0);
                    while($category = $posts_per_category->fetch_assoc()) {
                        echo $category['post_count'] . ",";
                    }
                ?>],
                backgroundColor: [
                    '#16423C',
                    '#6A9C89',
                    '#C4DAD2',
                    '#E9EFEC',
                    '#000000'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
</script>

<?php include '../includes/footer.php'; ?>

