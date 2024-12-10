<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$latest_posts = get_latest_posts($conn, 5);
$new_posts = get_new_posts($conn, 5);
$categories = get_categories($conn);

$page_title = 'Home';
include 'includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --secondary-color: #6A9C89;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-light: #E9EFEC;
        --background-white: #FFFFFF;
        --spacing-unit: 1rem;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 var(--spacing-unit);
    }

    .hero-section {
        background-color: var(--primary-color);
        color: white;
        padding: calc(var(--spacing-unit) * 3) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/uploads/whitebg.png') center/cover no-repeat;
        opacity: 0.2;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-logo {
        width: 100px;
        height: 100px;
        margin-bottom: 1.5rem;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .hero-section h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        font-weight: 300;
    }

    .hero-tagline {
        font-size: 1.2rem;
        font-weight: 500;
        margin-top: 1rem;
        padding: 0.5rem 1rem;
        background-color: rgba(255, 255, 255, 0.1);
        display: inline-block;
        border-radius: 4px;
    }

    .section-title {
        font-size: 2rem;
        color: var(--primary-color);
        margin: 2rem 0 1.5rem;
        text-align: center;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: var(--secondary-color);
    }

    .categories {
        margin: 3rem 0;
    }

    .categories ul {
        list-style: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
    }

    .categories li a {
        display: inline-block;
        padding: 0.7rem 1.5rem;
        background-color: var(--background-white);
        color: var(--primary-color);
        text-decoration: none;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .categories li a:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .post-preview {
        background-color: var(--background-white);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .post-preview:hover {
        transform: translateY(-5px);
    }

    .post-preview img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .post-content {
        padding: 1.5rem;
    }

    .post-preview h3 {
        margin: 0 0 1rem;
        font-size: 1.3rem;
        line-height: 1.4;
    }

    .post-preview h3 a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .post-preview h3 a:hover {
        color: var(--secondary-color);
    }

    .post-meta {
        font-size: 0.9rem;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .post-meta span {
        display: flex;
        align-items: center;
    }

    .post-meta svg {
        width: 16px;
        height: 16px;
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: calc(var(--spacing-unit) * 2) 0;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .hero-tagline {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .posts-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="hero-section">
    <div class="container hero-content">
        <img src="/uploads/logobp.jpg" alt="Builders/Pandayan Logo" class="hero-logo">
        <h1>WELCOME TO OUR BLOG</h1>
        <div class="hero-subtitle">Official Student Publication of Mindoro State University - Calapan City Campus</div>
        <p class="hero-tagline">YOUR VOICE. OUR LEGACY</p>
    </div>
</div>

<div class="container">
    <div class="categories">
        <h2 class="section-title">Explore Categories</h2>
        <ul>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <li><a href="category.php?id=<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="new-posts">
        <h2 class="section-title">Latest Stories</h2>
        <div class="posts-grid">
            <?php while ($post = $new_posts->fetch_assoc()): ?>
                <article class="post-preview">
                    <?php if ($post['image']): ?>
                        <img src="<?php echo $post['image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <?php endif; ?>
                    <div class="post-content">
                        <h3><a href="view_post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                        <div class="post-meta">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <?php echo htmlspecialchars($post['username']); ?>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <?php echo $post['views']; ?> views
                            </span>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

