<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if (update_user_profile($conn, $user_data['id'], $username, $email, $password)) {
        $success = "Profile updated successfully.";
        $user_data = check_login($conn); // Refresh user data
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}

$page_title = 'User Profile';
include '../includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --primary-light: #1D5248;
        --secondary-color: #6A9C89;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-light: #F5F7FA;
        --background-white: #FFFFFF;
        --accent-color: #E9EFEC;
        --error-color: #DC3545;
        --success-color: #28A745;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .profile-container {
        max-width: 600px;
        margin: 3rem auto;
        padding: 2.5rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .profile-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        font-weight: 700;
    }

    .profile-subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    .profile-form {
        display: grid;
        gap: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .form-group input {
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(22, 66, 60, 0.1);
    }

    .submit-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        background-color: var(--primary-light);
        transform: translateY(-2px);
    }

    .message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
    }

    .success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border: 1px solid var(--success-color);
    }

    .error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error-color);
        border: 1px solid var(--error-color);
    }

    @media (max-width: 768px) {
        .profile-container {
            margin: 2rem 1rem;
            padding: 2rem;
        }

        .profile-title {
            font-size: 2rem;
        }
    }
</style>

<div class="profile-container">
    <div class="profile-header">
        <h1 class="profile-title">User Profile</h1>
        <p class="profile-subtitle">Manage your account information</p>
    </div>

    <?php if (isset($success)): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post" class="profile-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">New Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit" class="submit-btn">Update Profile</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>

