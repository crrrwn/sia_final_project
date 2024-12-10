<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit();
}

$category_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    
    $query = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $name, $category_id);
    
    if ($stmt->execute()) {
        header("Location: manage_categories.php?success=2");
        exit();
    } else {
        $error = "Failed to update category. Please try again.";
    }
}

$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header("Location: manage_categories.php");
    exit();
}

$page_title = 'Edit Category';
include '../includes/header.php';
?>

<h1>Edit Category</h1>

<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

<form method="post">
    <div>
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
    </div>
    <div>
        <input type="submit" value="Update Category">
    </div>
</form>

<a href="manage_categories.php">Back to Manage Categories</a>

<?php include '../includes/footer.php'; ?>