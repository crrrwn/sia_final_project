<?php
require_once 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = sanitize_input($_GET['id']);

// Fetch post details
$sql = "SELECT p.*, u.username, c.name as category
        FROM posts p
        JOIN users u ON p.user_id = u.id
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$post = mysqli_fetch_assoc($result);

if (!$post) {
    header("Location: index.php");
    exit();
}

// Increment view count
mysqli_query($conn, "UPDATE posts SET views = views + 1 WHERE id = $post_id");

// Handle comment actions (add, edit, delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    if (isset($_POST['action'])) {
        $comment_id = sanitize_input($_POST['comment_id']);
        
        if ($_POST['action'] == 'edit') {
            $content = sanitize_input($_POST['content']);
            $sql = "UPDATE comments SET content = ? WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $content, $comment_id, $user_id);
            mysqli_stmt_execute($stmt);
        } elseif ($_POST['action'] == 'delete') {
            $sql = "DELETE FROM comments WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $comment_id, $user_id);
            mysqli_stmt_execute($stmt);
        }
    } else {
        // New comment
        $content = sanitize_input($_POST['comment']);
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $content);
        mysqli_stmt_execute($stmt);
    }
    
    header("Location: view_post.php?id=$post_id");
    exit();
}

// Fetch comments
$comments = mysqli_query($conn, "SELECT c.*, u.username 
                                FROM comments c 
                                JOIN users u ON c.user_id = u.id 
                                WHERE c.post_id = $post_id 
                                ORDER BY c.created_at DESC");
?>

<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body">
            <h1 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <p class="text-muted">
                By <?php echo htmlspecialchars($post['username']); ?> in <?php echo htmlspecialchars($post['category']); ?> 
                on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                <span class="ml-2"><i class="bi bi-eye"></i> <?php echo $post['views']; ?> views</span>
            </p>

            <?php if ($post['image']): ?>
                <div class="text-center mb-3">
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="img-fluid" 
                         style="max-height: 100%; width: auto;">
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <h3>Comments</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" class="mb-4">
                    <div class="form-group">
                        <textarea name="comment" class="form-control rounded-lg" rows="3" required placeholder="Write your comment here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-lg">Submit Comment</button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Login</a> to leave a comment.</p>
            <?php endif; ?>

            <div id="comments-section">
                <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
                    <div class="card mb-3 shadow-sm rounded-lg" style="background-color: #f9f9f9;">
                        <div class="card-body">
                            <div class="comment-content-<?php echo $comment['id']; ?>">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                            <p class="text-muted small">
                                By <?php echo htmlspecialchars($comment['username']); ?> 
                                on <?php echo date('F j, Y g:i a', strtotime($comment['created_at'])); ?>
                            </p>
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                                <div class="btn-group">
                                    <button onclick="editComment(<?php echo $comment['id']; ?>)" class="btn btn-sm btn-primary rounded-lg">Edit</button>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger rounded-lg" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                </div>
                                <div id="edit-form-<?php echo $comment['id']; ?>" style="display: none;" class="mt-2">
                                    <form method="post">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <div class="form-group">
                                            <textarea name="content" class="form-control rounded-lg" rows="3" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success rounded-lg">Save</button>
                                        <button type="button" class="btn btn-sm btn-secondary rounded-lg" onclick="cancelEdit(<?php echo $comment['id']; ?>)">Cancel</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
function editComment(commentId) {
    document.querySelector('.comment-content-' + commentId).style.display = 'none';
    document.querySelector('#edit-form-' + commentId).style.display = 'block';
}

function cancelEdit(commentId) {
    document.querySelector('.comment-content-' + commentId).style.display = 'block';
    document.querySelector('#edit-form-' + commentId).style.display = 'none';
}
</script>

<?php require_once 'includes/footer.php'; ?>
