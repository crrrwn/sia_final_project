<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $status = 'published'; // Admin posts are automatically published
    
    $image_path = null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = upload_image($_FILES['image']);
        if($image_path === false) {
            $error = "Failed to upload image. Please try again.";
        }
    }
    
    if (!isset($error)) {
        $query = "INSERT INTO posts (title, content, author_id, category_id, status, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssiiss", $title, $content, $user_data['id'], $category_id, $status, $image_path);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Post added successfully.";
            header("Location: manage_posts.php");
            exit();
        } else {
            $error = "Failed to add post. Please try again.";
        }
    }
}

$categories = get_categories($conn);

$page_title = 'Add New Post';
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
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .add-post-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .add-post-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 700;
    }

    .add-post-form {
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
        font-size: 1rem;
        font-weight: 600;
    }

    .form-group input[type="text"],
    .form-group select,
    .form-group textarea {
        padding: 0.75rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(106, 156, 137, 0.2);
    }

    .form-group textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-group input[type="file"] {
        font-size: 1rem;
        padding: 0.5rem;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        cursor: pointer;
    }

    .submit-btn {
        background-color: var(--secondary-color);
        color: var(--background-white);
        border: none;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .submit-btn:hover {
        background-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--primary-color);
    }

    .error-message {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
    }

    #imagePreview {
        margin-top: 1rem;
    }

    #preview {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .add-post-container {
            padding: 1.5rem;
        }

        .add-post-title {
            font-size: 2rem;
        }
    }
</style>

<div class="add-post-container">
    <h1 class="add-post-title">Add New Post</h1>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="add-post-form">
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
            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
        </div>
        <div id="imagePreview" style="display: none;">
            <img id="preview" src="#" alt="Image Preview">
        </div>
        <button type="submit" class="submit-btn">Add Post</button>
    </form>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview');
        output.src = reader.result;
        document.getElementById('imagePreview').style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include '../includes/footer.php'; ?>

