<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];
$post = get_post($conn, $post_id);

if (!$post) {
    header("Location: index.php");
    exit();
}

increment_views($conn, $post_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = $_POST['content'];
    add_comment($conn, $post_id, $_SESSION['user_id'], $content);
}

$comments = get_comments($conn, $post_id);

$page_title = $post['title'];
include 'includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-white: #FFFFFF;
        --background-light: #F8F9FA;
    }

    body {
        background-color: var(--background-white);
        font-family: 'Inter', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        margin: 0;
        padding: 0;
    }

    .post-meta-header {
        text-align: center;
        padding: 1rem 0;
        margin: 2rem auto;
        max-width: 800px;
    }

    .post-category {
        display: inline-block;
        background-color: #F0F2F5;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .post-date {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-left: 1rem;
    }

    .post-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 1.5rem 0;
        line-height: 1.2;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .post-subtitle {
        font-size: 1.125rem;
        color: var(--text-secondary);
        margin-bottom: 2rem;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .post-image-container {
        width: 90%;
        max-width: 600px;
        margin: 2rem auto;
        text-align: center;
    }

    .post-image {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: cover;
        border-radius: 8px;
    }

    .post-content {
        max-width: 700px;
        margin: 3rem auto;
        padding: 0 1.5rem;
        font-size: 1.125rem;
        line-height: 1.8;
        text-align: justify;
    }

    .post-content h2 {
        font-size: 1.75rem;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .post-content p {
        margin-bottom: 1.5rem;
        color: var(--text-secondary);
        text-indent: 1.5em;
    }

    .comments-section {
        max-width: 700px;
        margin: 4rem auto;
        padding: 0 1.5rem;
    }

    .comments-title {
        font-size: 1.5rem;
        color: var(--text-primary);
        margin-bottom: 2rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--background-light);
    }

    .comment-form textarea {
        width: 100%;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 1rem;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 1rem;
        background-color: var(--background-light);
    }

    .comment-form button {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .comment {
        padding: 1.5rem 0;
        border-bottom: 1px solid #E5E7EB;
    }

    .comment-content {
        font-size: 1rem;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .comment-meta {
        font-size: 0.875rem;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comment-actions a {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.875rem;
        margin-left: 1rem;
    }

    .edit-form textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #E5E7EB;
        border-radius: 6px;
        font-size: 0.875rem;
        margin: 1rem 0;
    }

    .edit-form button {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 6px;
    }

    @media (max-width: 768px) {
        .post-title {
            font-size: 2rem;
            padding: 0 1rem;
        }

        .post-subtitle {
            padding: 0 1rem;
            font-size: 1rem;
        }

        .post-image {
            border-radius: 0;
        }
    }
</style>

<article>
    <div class="post-meta-header">
        <span class="post-category"><?php echo htmlspecialchars($post['category_name']); ?></span>
        <span class="post-date"><?php echo date('F d, Y', strtotime($post['created_at'])); ?></span>
    </div>

    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
    
    <p class="post-subtitle">
        By <?php echo htmlspecialchars($post['username']); ?> • <?php echo $post['views']; ?> views
    </p>

    <?php if (!empty($post['image'])): ?>
        <div class="post-image-container">
            <img class="post-image" src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        </div>
    <?php endif; ?>

    <div class="post-content">
        <?php echo $post['content']; ?>
    </div>

    <div class="comments-section">
        <h2 class="comments-title">Comments</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" class="comment-form">
                <textarea name="content" required placeholder="Share your thoughts..."></textarea>
                <button type="submit">Post Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to add a comment.</p>
        <?php endif; ?>

        <?php while ($comment = $comments->fetch_assoc()): ?>
            <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                <p class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                <div class="comment-meta">
                    <span><?php echo htmlspecialchars($comment['username']); ?> • <?php echo date('M d, Y', strtotime($comment['created_at'])); ?></span>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                        <div class="comment-actions">
                            <a href="#" class="edit-comment-btn" data-comment-id="<?php echo $comment['id']; ?>">Edit</a>
                            <a href="delete_comment.php?id=<?php echo $comment['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                    <div class="edit-form" id="edit-form-<?php echo $comment['id']; ?>" style="display: none;">
                        <textarea id="edit-content-<?php echo $comment['id']; ?>"><?php echo htmlspecialchars($comment['content']); ?></textarea>
                        <button class="save-btn" data-comment-id="<?php echo $comment['id']; ?>">Save</button>
                        <button class="cancel-btn" data-comment-id="<?php echo $comment['id']; ?>">Cancel</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</article>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-comment-btn');
    const saveButtons = document.querySelectorAll('.save-btn');
    const cancelButtons = document.querySelectorAll('.cancel-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
            const editForm = document.querySelector(`#edit-form-${commentId}`);
            
            commentContent.style.display = 'none';
            editForm.style.display = 'block';
        });
    });

    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const content = document.querySelector(`#edit-content-${commentId}`).value;
            
            fetch('edit_comment_ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${commentId}&content=${encodeURIComponent(content)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
                    commentContent.textContent = content;
                    commentContent.style.display = 'block';
                    document.querySelector(`#edit-form-${commentId}`).style.display = 'none';
                } else {
                    alert('Failed to update comment. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });

    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const commentContent = document.querySelector(`#comment-${commentId} .comment-content`);
            const editForm = document.querySelector(`#edit-form-${commentId}`);
            
            commentContent.style.display = 'block';
            editForm.style.display = 'none';
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>

