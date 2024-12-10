<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'writer') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = $_GET['id'];
$post = get_post($conn, $post_id);

if (!$post || $post['author_id'] != $user_data['id']) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    
    $image_path = $post['image']; // Keep the existing image by default
    
    // Check if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $new_image_path = upload_image($_FILES['image']);
        if ($new_image_path !== false) {
            // If a new image was successfully uploaded, update the image path
            $image_path = $new_image_path;
            
            // Delete the old image if it exists
            if (!empty($post['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $post['image'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $post['image']);
            }
        } else {
            $error = "Failed to upload new image. The post will be updated with the existing image.";
        }
    }

    if (update_post($conn, $post_id, $title, $content, $category_id, 'pending', $image_path)) {
        header("Location: dashboard.php?success=1");
        exit();
    } else {
        $error = "Failed to update post. Please try again.";
    }
}

$categories = get_categories($conn);

$page_title = 'Edit Post';
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
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .edit-post-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .edit-post-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .edit-post-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .edit-post-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    .edit-post-form {
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
    .form-group textarea,
    .form-group input[type="file"] {
        padding: 0.75rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus,
    .form-group textarea:focus,
    .form-group input[type="file"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(22, 66, 60, 0.1);
    }

    .form-group textarea {
        min-height: 200px;
        resize: vertical;
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

    .submit-btn:active {
        transform: translateY(0);
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

    .error-message {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .current-image {
        max-width: 200px;
        height: auto;
        margin-top: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .edit-post-container {
            padding: 1.5rem;
        }

        .edit-post-title {
            font-size: 2rem;
        }
    }
</style>

<div class="edit-post-container">
    <div class="edit-post-header">
        <h1 class="edit-post-title">Edit Post</h1>
        <p class="edit-post-subtitle">Make changes to your post and submit for review</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" class="edit-post-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $post['category_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <?php if (!empty($post['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $post['image'])): ?>
                <p>Current Image:</p>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current post image" class="current-image">
            <?php endif; ?>
        </div>
        <button type="submit" class="submit-btn">Update Post</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<?php include '../includes/footer.php'; ?>

