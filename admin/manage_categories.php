<?php
require_once '../includes/header.php';
check_admin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

if ($action == 'create' || $action == 'edit') {
    if ($action == 'edit') {
        $category_id = sanitize_input($_GET['id']);
        $category = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories WHERE id = $category_id"));
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = sanitize_input($_POST['name']);
        
        if ($action == 'create') {
            $sql = "INSERT INTO categories (name) VALUES (?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $name);
        } else {
            $sql = "UPDATE categories SET name = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $name, $category_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: manage_categories.php");
            exit();
        }
    }
} elseif ($action == 'delete') {
    $category_id = sanitize_input($_GET['id']);
    mysqli_query($conn, "DELETE FROM categories WHERE id = $category_id");
    header("Location: manage_categories.php");
    exit();
}

$categories = get_categories();
?>

<h1>Manage Categories</h1>

<?php if ($action == 'list'): ?>
    <a href="?action=create" class="btn btn-primary mb-3">Create New Category</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                <tr>
                    <td><?php echo $category['name']; ?></td>
                    <td>
                        <a href="?action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="?action=delete&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <form method="post">
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($category) ? $category['name'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?php echo $action == 'create' ? 'Create' : 'Update'; ?> Category</button>
    </form>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>