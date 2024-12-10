<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'writer') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = $_POST['category_name'];
        if (add_category($conn, $name)) {
            $success = "Category added successfully.";
        } else {
            $error = "Failed to add category. Please try again.";
        }
    } elseif (isset($_POST['edit_category'])) {
        $id = $_POST['category_id'];
        $name = $_POST['category_name'];
        if (update_category($conn, $id, $name)) {
            $success = "Category updated successfully.";
        } else {
            $error = "Failed to update category. Please try again.";
        }
    } elseif (isset($_POST['delete_category'])) {
        $id = $_POST['category_id'];
        if (delete_category($conn, $id)) {
            $success = "Category deleted successfully.";
        } else {
            $error = "Failed to delete category. Please try again.";
        }
    }
}

$categories = get_categories($conn);

$page_title = 'Manage Categories';
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
        --error-color: #DC3545;
        --success-color: #28A745;
        --warning-color: #FFC107;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .categories-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .categories-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .categories-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .categories-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    .message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border: 1px solid var(--success-color);
    }

    .error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        border: 1px solid var(--error-color);
    }

    .add-category-form {
        background-color: var(--accent-color);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .add-category-form h2 {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .form-group {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .form-group input[type="text"] {
        flex-grow: 1;
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input[type="text"]:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary-light);
    }

    .categories-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
    }

    .categories-table th {
        text-align: left;
        padding: 1rem;
        background-color: var(--accent-color);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .categories-table td {
        padding: 1rem;
        background-color: var(--background-white);
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
    }

    .categories-table tr td:first-child {
        border-left: 1px solid #e0e0e0;
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }

    .categories-table tr td:last-child {
        border-right: 1px solid #e0e0e0;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }

    .categories-table tr:hover td {
        background-color: var(--background-light);
    }

    .category-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-edit {
        background-color: #6A9C89;
        color: #FFFFFF;
    }

    .btn-edit:hover {
        background-color: #C4DAD2;
    }

    .btn-delete {
        background-color: #16423C;
        color: #FFFFFF;
    }

    .btn-delete:hover {
        background-color: #000000;
    }

    .back-link {
        display: inline-block;
        margin-top: 2rem;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-light);
    }

    @media (max-width: 768px) {
        .categories-container {
            padding: 1.5rem;
        }

        .categories-title {
            font-size: 2rem;
        }

        .form-group {
            flex-direction: column;
        }

        .form-group input[type="text"] {
            width: 100%;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="categories-container">
    <div class="categories-header">
        <h1 class="categories-title">Manage Categories</h1>
        <p class="categories-subtitle">Add, edit, or delete categories for your blog posts</p>
    </div>

    <?php if (isset($success)): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="add-category-form">
        <h2>Add New Category</h2>
        <form method="post" class="form-group">
            <input type="text" name="category_name" required placeholder="Enter category name">
            <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
        </form>
    </div>

    <h2>Existing Categories</h2>
    <table class="categories-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td>
                        <div class="category-actions">
                            <form method="post" class="form-group">
                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                <input type="text" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                <button type="submit" name="edit_category" class="btn btn-edit">Edit</button>
                            </form>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                <button type="submit" name="delete_category" class="btn btn-delete">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>

