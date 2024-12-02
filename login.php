<?php
require_once 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Blog System</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f9f4; /* Light green background */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header, .footer {
            background: #006400; /* Dark Green Background */
            color: #fff;
            padding: 20px 30px;
            text-align: center;
        }

        .header {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .footer {
            margin-top: auto;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .social-link {
            margin: 0 5px;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
        }

        .footer .social-link:hover {
            color: #dddddd;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 500px;
        }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.15);
            width: 100%;
        }

        .login-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #006400; /* Dark Green */
        }

        .login-subtitle {
            font-size: 14px;
            margin-bottom: 20px;
            color: #777;
        }

        .login-form .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        input:focus {
            border: 1px solid #006400; /* Dark Green Border on Focus */
            outline: none;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 14px;
        }

        .btn-primary {
            background: #006400; /* Dark Green */
            color: #ffffff;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #004d00; /* Darker Green on Hover */
        }

        .forgot-password {
            display: block;
            margin-top: 12px;
            text-align: center;
            font-size: 14px;
            color: #006400;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <main class="main-content">
        <div class="login-container">
            <div class="login-card">
                <h1 class="login-title">Welcome!</h1>
                <p class="login-subtitle">Login to continue exploring the Blog System.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="post" class="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Login</button>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                </form>
            </div>
        </div>
    </main>

    <script>
        const togglePassword = document.querySelector('.toggle-password');
        const passwordField = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

<?php require_once 'includes/footer.php'; ?>
