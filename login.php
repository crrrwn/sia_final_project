<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            switch ($user['role']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'writer':
                    header("Location: writer/dashboard.php");
                    break;
                case 'user':
                    header("Location: index.php");
                    break;
            }
            exit();
        }
    }

    $error = "Invalid username or password";
}

$page_title = 'Login';
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

    .login-container {
        max-width: 400px;
        margin: 4rem auto;
        padding: 2.5rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .login-title {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        text-align: center;
        font-weight: 700;
    }

    .login-form {
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

    .form-group input {
        padding: 0.75rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
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

    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-secondary);
    }

    .register-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .register-link a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
</style>

<div class="login-container">
    <h2 class="login-title">Welcome Back</h2>

    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" class="login-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Log In</button>
    </form>

    <p class="register-link">
        Don't have an account? <a href="register.php">Sign up here</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?>

