<?php 
require_once 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$category_id = sanitize_input($_GET['id']);

$sql = "SELECT p.id, p.title, p.content, p.image, p.created_at, u.username, c.name as category
        FROM posts p
        JOIN users u ON p.user_id = u.id
        JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        ORDER BY p.created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $category_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$category_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM categories WHERE id = $category_id"))['name'];
?>

<div class="container mt-5 p-4" style="background-color: #f0f1f6; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white rounded-top">
            <h2 class="mb-0">Posts in <?php echo htmlspecialchars($category_name); ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <?php while ($post = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                            <?php if (!empty($post['image'])): ?>
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">No image available</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column" style="background-color: #ffffff;">
                                <h3 class="card-title h5 text-dark"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="card-text flex-grow-1 text-muted"><?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?></p>
                                <div class="mt-auto">
                                    <p class="card-text"><small class="text-muted">
                                        By <?php echo htmlspecialchars($post['username']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                    </small></p>
                                    <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-lg">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info" role="alert">
                    No posts found in this category.
                </div>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary btn-lg mt-3">Back to Home</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

