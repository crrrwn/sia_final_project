<?php
require_once 'includes/header.php';

// Function to get posts based on search query
function search_posts($query) {
    global $conn;
    $query = mysqli_real_escape_string($conn, $query);
    $sql = "SELECT p.id, p.title, p.content, p.image, p.created_at, u.username, c.name as category
            FROM posts p
            JOIN users u ON p.user_id = u.id
            JOIN categories c ON p.category_id = c.id
            WHERE p.title LIKE '%$query%' OR p.content LIKE '%$query%'
            ORDER BY p.created_at DESC";
    return mysqli_query($conn, $sql);
}

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$posts = $search_query ? search_posts($search_query) : get_posts();
$categories = get_categories();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Search form -->
            <form action="index.php" method="GET" class="mb-4">
                <div class="input-group shadow">
                    <input type="text" class="form-control" placeholder="Search posts..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            <?php if ($search_query): ?>
                <h2 class="mb-4 text-primary">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
            <?php else: ?>
                <h2 class="mb-4 text-primary">Recent Posts</h2>
            <?php endif; ?>

            <?php if (mysqli_num_rows($posts) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($posts)): ?>
                    <div class="card mb-4 shadow-lg border-0">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top rounded-top" alt="<?php echo htmlspecialchars($post['title']); ?>" style="height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="card-text text-muted"><?php echo htmlspecialchars(substr($post['content'], 0, 200)) . '...'; ?></p>
                            <p class="card-text">
                                <small class="text-muted">
                                    By <strong><?php echo htmlspecialchars($post['username']); ?></strong> 
                                    in <span class="badge bg-secondary"><?php echo htmlspecialchars($post['category']); ?></span> 
                                    on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </small>
                            </p>
                            <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline-primary">Read More</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-warning">No posts found.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <div class="p-4 shadow-lg rounded border">
                <h3 class="mb-4 text-primary">Categories</h3>
                <ul class="list-group">
                    <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                        <li class="list-group-item border-0">
                            <a href="category.php?id=<?php echo $category['id']; ?>" class="text-decoration-none text-primary"><?php echo htmlspecialchars($category['name']); ?></a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
