<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT p.*, u.username, c.name AS category_name FROM posts p 
          JOIN users u ON p.author_id = u.id 
          JOIN categories c ON p.category_id = c.id 
          ORDER BY p.created_at DESC";
$result = $conn->query($query);

$page_title = 'Manage Posts';
include '../includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --secondary-color: #6A9C89;
        --accent-color: #C4DAD2;
        --background-light: #E9EFEC;
        --background-white: #FFFFFF;
        --text-primary: #000000;
        --text-secondary: #333333;
        --border-color: #C4DAD2;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .manage-posts-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .manage-posts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--accent-color);
    }

    .manage-posts-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin: 0;
        font-weight: 700;
    }

    .add-post-btn {
        background-color: var(--secondary-color);
        color: var(--background-white);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .add-post-btn:hover {
        background-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }

    .success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border: 1px solid var(--success-color);
    }

    .error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border: 1px solid var(--danger-color);
    }

    .posts-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
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
        font-size: 0.875rem;
        letter-spacing: 0.05em;
    }

    .posts-table tr {
        background-color: var(--background-white);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .posts-table tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .post-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .post-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-published {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .status-pending {
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
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .post-action-edit {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }

    .post-action-delete {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }
    .post-action-approve {
        background-color: var(--success-color);
        color: var(--background-white);
    }

    .post-action:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .manage-posts-container {
            padding: 1rem;
        }

        .manage-posts-title {
            font-size: 2rem;
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

        .post-action {
            padding: 0.4rem 0.8rem;
        }
    }
</style>

<div class="manage-posts-container">
    <div class="manage-posts-header">
        <h1 class="manage-posts-title">Manage Posts</h1>
        <a href="add_post.php" class="add-post-btn">Add New Post</a>
    </div>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<div class='message success'>" . htmlspecialchars($_SESSION['success_message']) . "</div>";
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo "<div class='message error'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
        unset($_SESSION['error_message']);
    }
    ?>

    <table class="posts-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($post = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">
                        <?php else: ?>
                            <img src="/placeholder.svg?height=80&width=80" alt="No image" class="post-image">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                    <td><?php echo htmlspecialchars($post['username']); ?></td>
                    <td><?php echo htmlspecialchars($post['category_name']); ?></td>
                    <td>
                        <span class="post-status <?php echo $post['status'] === 'published' ? 'status-published' : 'status-pending'; ?>">
                            <?php echo ucfirst($post['status']); ?>
                        </span>
                    </td>
                    <td><?php echo $post['views']; ?></td>
                    <td>
                        <div class="post-actions">
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="post-action post-action-edit">Edit</a>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="post-action post-action-delete" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            <?php if ($post['status'] == 'pending'): ?>
                                <a href="approve_post.php?id=<?php echo $post['id']; ?>" class="post-action post-action-approve">Approve</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.posts-table');
    const headers = table.querySelectorAll('th');
    const tableBody = table.querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr');

    headers.forEach((header, index) => {
        header.addEventListener('click', () => {
            const direction = header.classList.contains('sort-asc') ? 'desc' : 'asc';
            headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
            header.classList.add(`sort-${direction}`);

            const sortedRows = Array.from(rows).sort((a, b) => {
                const aColText = a.querySelector(`td:nth-child(${index + 1})`).textContent.trim();
                const bColText = b.querySelector(`td:nth-child(${index + 1})`).textContent.trim();

                if (direction === 'asc') {
                    return aColText.localeCompare(bColText);
                } else {
                    return bColText.localeCompare(aColText);
                }
            });

            tableBody.append(...sortedRows);
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>

