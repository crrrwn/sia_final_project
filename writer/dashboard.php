<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

require_login();
$user_data = check_login($conn);

if ($user_data['role'] != 'writer') {
    header("Location: ../index.php");
    exit();
}

$posts = get_user_posts($conn, $user_data['id']);

$page_title = 'Writer Dashboard';
include '../includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --primary-light: #1D5248;
        --secondary-color: #6A9C89;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-light: #F5F7FA;
        --background-white: #FFFFFF;
        --accent-color: #E9EFEC;
        --success-color: #28A745;
        --warning-color: #FFC107;
        --danger-color: #DC3545;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--accent-color);
    }

    .dashboard-title {
        font-size: 2.3rem;
        color: var(--primary-color);
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
    }

    .action-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-button:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .posts-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1rem;
    }

    .posts-table th,
    .posts-table td {
        padding: 1rem;
        text-align: left;
    }

    .posts-table th {
        background-color: var(--accent-color);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .posts-table tr {
        background-color: var(--background-white);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .posts-table tr:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .post-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .post-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-published {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .status-draft {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
    }

    .post-actions {
        display: flex;
        gap: 0.5rem;
    }

    .post-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .post-action-edit {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }

    .post-action-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .post-action:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .posts-table {
            font-size: 0.875rem;
        }

        .posts-table th,
        .posts-table td {
            padding: 0.75rem 0.5rem;
        }

        .post-image {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Writer Dashboard</h1>
        <div class="action-buttons">
            <a href="create_post.php" class="action-button">Create New Post</a>
            <a href="manage_categories.php" class="action-button">Manage Categories</a>
        </div>
    </div>

    <table class="posts-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Comments</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">
                        <?php else: ?>
                            <img src="/placeholder.svg?height=80&width=80" alt="No image" class="post-image">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                    <td>
                        <span class="post-status <?php echo $post['status'] === 'published' ? 'status-published' : 'status-draft'; ?>">
                            <?php echo ucfirst($post['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $post['views']; ?></td>
                    <td><?php echo $post['comment_count']; ?></td>
                    <td>
                        <div class="post-actions">
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="post-action post-action-edit">Edit</a>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="post-action post-action-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

