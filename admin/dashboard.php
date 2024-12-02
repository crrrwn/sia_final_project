<?php
require_once '../includes/header.php';
check_admin();

// Fetch statistics
$total_posts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts"))['count'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_comments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM comments"))['count'];

// Fetch most viewed posts
$most_viewed = mysqli_query($conn, "SELECT title, views FROM posts ORDER BY views DESC LIMIT 5");

// Fetch most commented posts
$most_commented = mysqli_query($conn, "SELECT p.title, COUNT(c.id) as comment_count 
                                       FROM posts p 
                                       LEFT JOIN comments c ON p.id = c.post_id 
                                       GROUP BY p.id 
                                       ORDER BY comment_count DESC 
                                       LIMIT 5");

// Fetch posts per category
$posts_per_category = mysqli_query($conn, "SELECT c.name, COUNT(p.id) as post_count 
                                           FROM categories c
                                           LEFT JOIN posts p ON c.id = p.category_id
                                           GROUP BY c.id
                                           ORDER BY post_count DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            padding: 2rem;
            background-color: #f8f9fa;
        }
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            transition: transform 0.3s ease-in-out;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            text-align: center;
            padding: 1.5rem;
        }
        .stats-card h5 {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .chart-container {
            position: relative;
            height: 400px;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Admin Dashboard</h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="dashboard-card stats-card">
                    <h5>Total Posts</h5>
                    <div class="stats-number"><?php echo htmlspecialchars($total_posts); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card stats-card">
                    <h5>Total Users</h5>
                    <div class="stats-number"><?php echo htmlspecialchars($total_users); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card stats-card">
                    <h5>Total Comments</h5>
                    <div class="stats-number"><?php echo htmlspecialchars($total_comments); ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h5>Most Viewed Posts</h5>
                    <div class="chart-container">
                        <canvas id="viewsChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h5>Most Commented Posts</h5>
                    <div class="chart-container">
                        <canvas id="commentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="dashboard-card">
                    <h5>Posts per Category</h5>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Shared chart options
    const sharedOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        }
    };

    // Most Viewed Posts Chart
    new Chart(document.getElementById('viewsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [<?php
                $titles = [];
                $views = [];
                while ($row = mysqli_fetch_assoc($most_viewed)) {
                    $titles[] = "'" . addslashes(substr($row['title'], 0, 30)) . "'";
                    $views[] = $row['views'];
                }
                echo implode(',', $titles);
            ?>],
            datasets: [{
                label: 'Views',
                data: [<?php echo implode(',', $views); ?>],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...sharedOptions,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Most Commented Posts Chart
    new Chart(document.getElementById('commentsChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: [<?php
                $titles = [];
                $comments = [];
                while ($row = mysqli_fetch_assoc($most_commented)) {
                    $titles[] = "'" . addslashes(substr($row['title'], 0, 30)) . "'";
                    $comments[] = $row['comment_count'];
                }
                echo implode(',', $titles);
            ?>],
            datasets: [{
                label: 'Comments',
                data: [<?php echo implode(',', $comments); ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.7)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...sharedOptions,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Posts per Category Chart
    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: [<?php
                $categories = [];
                $post_counts = [];
                while ($row = mysqli_fetch_assoc($posts_per_category)) {
                    $categories[] = "'" . addslashes($row['name']) . "'";
                    $post_counts[] = $row['post_count'];
                }
                echo implode(',', $categories);
            ?>],
            datasets: [{
                data: [<?php echo implode(',', $post_counts); ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(201, 203, 207, 0.7)',
                    'rgba(255, 145, 164, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(201, 203, 207, 1)',
                    'rgba(255, 145, 164, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            ...sharedOptions,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php require_once '../includes/footer.php'; ?>
