<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_posts.php");
    exit();
}

$post_id = $_GET['id'];
$post = get_post($conn, $post_id);

if (!$post) {
    header("Location: manage_posts.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $status = $_POST['status'];
    
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
    
    if (update_post($conn, $post_id, $title, $content, $category_id, $status, $image_path)) {
        $_SESSION['success_message'] = "Post updated successfully.";
        header("Location: manage_posts.php");
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
        --color-black: #000000;
        --color-dark-green: #16423C;
        --color-medium-green: #6A9C89;
        --color-light-green: #C4DAD2;
        --color-off-white: #E9EFEC;
    }

    body {
        background-color: var(--color-off-white);
        color: var(--color-black);
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
    }

    .edit-post-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .edit-post-title {
        font-size: 2.5rem;
        color: var(--color-dark-green);
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: bold;
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
        color: var(--color-dark-green);
        font-size: 1rem;
        font-weight: 600;
    }

    .form-group input[type="text"],
    .form-group select,
    .form-group textarea {
        padding: 0.75rem;
        border: 2px solid var(--color-light-green);
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--color-medium-green);
        box-shadow: 0 0 0 3px rgba(106, 156, 137, 0.2);
    }

    .form-group textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-group input[type="file"] {
        font-size: 0.9rem;
        padding: 0.5rem;
        border: 2px dashed var(--color-light-green);
        border-radius: 4px;
        cursor: pointer;
    }

    .current-image {
        margin-top: 1rem;
    }

    .current-image img {
        max-width: 200px;
        height: auto;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .submit-btn {
        background-color: var(--color-dark-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.1s ease;
    }

    .submit-btn:hover {
        background-color: var(--color-medium-green);
    }

    .submit-btn:active {
        transform: translateY(1px);
    }

    .back-link {
        display: inline-block;
        margin-top: 1rem;
        color: var(--color-medium-green);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--color-dark-green);
    }

    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        padding: 0.75rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
</style>

<div class="edit-post-container">
    <h1 class="edit-post-title">Edit Post</h1>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="edit-post-form">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $post['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="draft" <?php echo ($post['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                <option value="pending" <?php echo ($post['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="published" <?php echo ($post['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
        </div>
        <?php if (!empty($post['image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $post['image'])): ?>
            <div class="current-image">
                <p>Current Image:</p>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current post image">
            </div>
        <?php endif; ?>
        <div>
            <button type="submit" class="submit-btn">Update Post</button>
        </div>
    </form>

    <a href="manage_posts.php" class="back-link">← Back to Manage Posts</a>
</div>

<?php include '../includes/footer.php'; ?>