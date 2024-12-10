<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }
}

$page_title = 'Register';
include 'includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --primary-hover: #1D5248;
        --text-primary: #333333;
        --text-secondary: #666666;
        --background-light: #E9EFEC;
        --background-white: #FFFFFF;
        --error-color: #E74C3C;
        --success-color: #2ECC71;
    }

    body {
        background-color: var(--background-light);
    }

    .register-container {
        max-width: 450px;
        margin: 4rem auto;
        padding: 2.5rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .register-title {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 700;
    }

    .register-form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.95rem;
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .submit-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 6px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .submit-btn:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
    }

    .error-message {
        color: var(--error-color);
        text-align: center;
        margin-bottom: 1.5rem;
        padding: 0.75rem;
        background-color: rgba(231, 76, 60, 0.1);
        border-radius: 6px;
        font-weight: 500;
    }

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-secondary);
    }

    .login-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login-link a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
</style>

<div class="register-container">
    <h2 class="register-title">Create an Account</h2>

    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" class="register-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="writer">Writer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="submit-btn">Create Account</button>
    </form>

    <p class="login-link">
        Already have an account? <a href="login.php">Log in here</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>

