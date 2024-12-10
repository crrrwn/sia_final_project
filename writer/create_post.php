<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'writer') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = upload_image($_FILES['image']);
        if (!$image_path) {
            $error = "Failed to upload image. Please try again.";
        }
    }
    
    if (!isset($error)) {
        $query = "INSERT INTO posts (title, content, author_id, category_id, status, image) VALUES (?, ?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssiis", $title, $content, $user_data['id'], $category_id, $image_path);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php?success=1");
            exit();
        } else {
            $error = "Failed to create post. Please try again.";
        }
    }
}

$categories = get_categories($conn);

$page_title = 'Create New Post';
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
    }

    body {
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .create-post-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .create-post-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .create-post-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .create-post-form {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .form-group input[type="text"],
    .form-group select,
    .form-group textarea {
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .form-group textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-group input[type="file"] {
        padding: 0.5rem;
        border: 2px dashed #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
    }

    .submit-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .submit-btn:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .error-message {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-light);
    }

    @media (max-width: 768px) {
        .create-post-container {
            padding: 1.5rem;
        }

        .create-post-title {
            font-size: 2rem;
        }
    }
</style>

<div class="create-post-container">
    <div class="create-post-header">
        <h1 class="create-post-title">Create New Post</h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="create-post-form">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <button type="submit" class="submit-btn">Create Post</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>

