<?php
require_once '../includes/header.php';
check_admin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'create' || $action == 'edit') {
    $categories = get_categories();
    
    if ($action == 'edit') {
        $post_id = sanitize_input($_GET['id']);
        $post = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM posts WHERE id = $post_id"));
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = sanitize_input($_POST['title']);
        $content = sanitize_input($_POST['content']);
        $category_id = sanitize_input($_POST['category_id']);
        
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $filename = $_FILES['image']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), $allowed)) {
                $image_path = 'uploads/' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image_path);
            }
        }
        
        if ($action == 'create') {
            $user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO posts (title, content, user_id, category_id, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssiis", $title, $content, $user_id, $category_id, $image_path);
        } else {
            if ($image_path) {
                $sql = "UPDATE posts SET title = ?, content = ?, category_id = ?, image = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssisi", $title, $content, $category_id, $image_path, $post_id);
            } else {
                $sql = "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssii", $title, $content, $category_id, $post_id);
            }
        }
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: manage_posts.php");
            exit();
        }
    }
} elseif ($action == 'delete') {
    $post_id = sanitize_input($_GET['id']);
    mysqli_query($conn, "DELETE FROM posts WHERE id = $post_id");
    header("Location: manage_posts.php");
    exit();
}

$posts = mysqli_query($conn, "SELECT p.*, c.name as category FROM posts p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
?>

<h1>Manage Posts</h1>

<?php if ($action == 'list'): ?>
    <a href="?action=create" class="btn btn-primary mb-3">Create New Post</a>
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($post = mysqli_fetch_assoc($posts)): ?>
                <tr>
                    <td>
                        <?php if ($post['image']): ?>
                            <img src="../<?php echo $post['image']; ?>" alt="Post thumbnail" class="img-thumbnail" style="max-width: 100px; height: auto;">
                        <?php else: ?>
                            <div class="text-muted">No image</div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $post['title']; ?></td>
                    <td><?php echo $post['category']; ?></td>
                    <td><?php echo $post['created_at']; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="?action=delete&id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required value="<?php echo isset($post) ? $post['title'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="10" required><?php echo isset($post) ? $post['content'] : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($post) && $post['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                        <?php echo $category['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <?php if (isset($post) && $post['image']): ?>
                <div class="mt-2">
                    <img src="../<?php echo $post['image']; ?>" alt="Current post image" class="img-thumbnail" style="max-width: 200px;">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">
            <?php echo ($action == 'create') ? 'Create' : 'Update'; ?> Post
        </button>
    </form>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
