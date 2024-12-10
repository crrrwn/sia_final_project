<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$category_id = $_GET['id'];

// Get category details
$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header("Location: index.php");
    exit();
}

// Get posts for this category
$query = "SELECT p.*, u.username 
          FROM posts p 
          JOIN users u ON p.author_id = u.id 
          WHERE p.category_id = ? AND p.status = 'published' 
          ORDER BY p.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$posts = $stmt->get_result();

$page_title = 'Category: ' . $category['name'];
include 'includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --secondary-color: #6A9C89;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-light: #F5F7FA;
        --background-white: #FFFFFF;
        --accent-color: #E9EFEC;
        --spacing-unit: 1rem;
    }

    body {
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .category-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .category-header {
        background-color: var(--primary-color);
        color: var(--background-white);
        padding: 2rem 2rem;
        border-radius: 10px;
        margin-bottom: 3rem;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .category-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(106, 156, 137, 0.3), rgba(22, 66, 60, 0.3));
        z-index: 1;
    }

    .category-header-content {
        position: relative;
        z-index: 2;
    }

    .category-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .category-description {
        font-size: 1.2rem;
        max-width: 700px;
        margin: 0 auto;
        opacity: 0.9;
    }

    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2.5rem;
    }

    .post-preview {
        background-color: var(--background-white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .post-preview:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .post-image-container {
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden;
    }

    .post-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .post-preview:hover .post-image {
        transform: scale(1.05);
    }

    .post-content {
        padding: 1.5rem;
    }

    .post-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    .post-title a {
        text-decoration: none;
        color: var(--text-primary);
        transition: color 0.2s ease;
    }

    .post-title a:hover {
        color: var(--primary-color);
    }

    .post-meta {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .post-author, .post-views {
        display: flex;
        align-items: center;
    }

    .post-author svg, .post-views svg {
        width: 18px;
        height: 18px;
        margin-right: 0.5rem;
    }

    .post-excerpt {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .read-more {
        display: inline-block;
        background-color: var(--accent-color);
        color: var(--primary-color);
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .read-more:hover {
        background-color: var(--primary-color);
        color: var(--background-white);
    }

    .no-posts {
        text-align: center;
        font-size: 1.2rem;
        color: var(--text-secondary);
        padding: 4rem 2rem;
        background-color: var(--background-white);
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .back-link {
        display: inline-block;
        margin-top: 3rem;
        font-size: 1.1rem;
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.2s ease;
        font-weight: 600;
    }

    .back-link:hover {
        color: var(--secondary-color);
    }

    @media (max-width: 768px) {
        .category-header {
            padding: 3rem 1.5rem;
        }

        .category-title {
            font-size: 2.5rem;
        }

        .category-description {
            font-size: 1.1rem;
        }

        .posts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="category-container">
    <header class="category-header">
        <div class="category-header-content">
            <h1 class="category-title"><?php echo htmlspecialchars($category['name']); ?></h1>
            <?php if (!empty($category['description'])): ?>
                <p class="category-description"><?php echo htmlspecialchars($category['description']); ?></p>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($posts->num_rows > 0): ?>
        <div class="posts-grid">
            <?php while ($post = $posts->fetch_assoc()): ?>
                <article class="post-preview">
                    <div class="post-image-container">
                        <?php if ($post['image']): ?>
                            <img class="post-image" src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        <?php else: ?>
                            <img class="post-image" src="/path/to/default/image.jpg" alt="Default post image">
                        <?php endif; ?>
                    </div>
                    <div class="post-content">
                        <h2 class="post-title"><a href="view_post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                        <div class="post-meta">
                            <span class="post-author">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <?php echo htmlspecialchars($post['username']); ?>
                            </span>
                            <span class="post-views">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <?php echo $post['views']; ?> views
                            </span>
                        </div>
                        <p class="post-excerpt"><?php echo substr(strip_tags($post['content']), 0, 150); ?>...</p>
                        <a class="read-more" href="view_post.php?id=<?php echo $post['id']; ?>">Read More</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-posts">No posts found in this category.</p>
    <?php endif; ?>

    <a class="back-link" href="index.php">← Back to Home</a>
</div>

<?php include 'includes/footer.php'; ?>

