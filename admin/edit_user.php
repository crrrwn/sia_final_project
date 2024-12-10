<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$edit_user_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if (update_user_profile($conn, $edit_user_id, $username, $email, $password, $role)) {
        $_SESSION['success_message'] = "User updated successfully.";
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Failed to update user. Please try again.";
    }
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $edit_user_id);
$stmt->execute();
$result = $stmt->get_result();
$edit_user = $result->fetch_assoc();

if (!$edit_user) {
    header("Location: manage_users.php");
    exit();
}

$page_title = 'Edit User';
include '../includes/header.php';
?>

<style>
    :root {
        --color-black: #000000;
        --color-dark-green: #16423C;
        --color-medium-green: #6A9C89;
        --color-light-green: #C4DAD2;
        --color-off-white: #E9EFEC;
    }

    body {
        background-color: var(--color-off-white);
        color: var(--color-black);
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
    }

    .edit-user-container {
        max-width: 600px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .edit-user-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .edit-user-title {
        font-size: 2.5rem;
        color: var(--color-dark-green);
        margin-bottom: 0.5rem;
        font-weight: bold;
    }

    .edit-user-subtitle {
        font-size: 1.1rem;
        color: var(--color-medium-green);
    }

    .edit-user-form {
        display: grid;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        color: var(--color-dark-green);
        font-size: 1rem;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        padding: 0.75rem;
        border: 2px solid var(--color-light-green);
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--color-medium-green);
        box-shadow: 0 0 0 3px rgba(106, 156, 137, 0.2);
    }

    .submit-btn {
        background-color: var(--color-dark-green);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.1s ease;
    }

    .submit-btn:hover {
        background-color: var(--color-medium-green);
    }

    .submit-btn:active {
        transform: translateY(1px);
    }

    .back-link {
        display: inline-block;
        margin-top: 1.5rem;
        color: var(--color-medium-green);
        text-decoration: none;
        font-size: 1rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: var(--color-dark-green);
    }

    .message {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .error-message {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid #dc3545;
    }

    .success-message {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid #28a745;
    }

    @media (max-width: 768px) {
        .edit-user-container {
            padding: 1.5rem;
            margin: 1rem;
        }

        .edit-user-title {
            font-size: 2rem;
        }
    }
</style>

<div class="edit-user-container">
    <div class="edit-user-header">
        <h1 class="edit-user-title">Edit User</h1>
        <p class="edit-user-subtitle">Update user information</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="message error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="message success-message"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <form method="post" class="edit-user-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($edit_user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($edit_user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="user" <?php if ($edit_user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="writer" <?php if ($edit_user['role'] == 'writer') echo 'selected'; ?>>Writer</option>
                <option value="admin" <?php if ($edit_user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit" class="submit-btn">Update User</button>
    </form>

    <a href="manage_users.php" class="back-link">← Back to Manage Users</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.edit-user-form');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        if (password && password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?>