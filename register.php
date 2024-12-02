<?php 
require_once 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = sanitize_input($_POST['role']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashed_password, $role);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blog System</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Reuse the same styles */
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f9f4; /* Light green background */
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header, .footer {
            background: #006400; /* Dark Green */
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

        .register-container {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.15);
        }

        h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #006400; /* Dark Green */
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #555;
            display: block;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        input:focus, select:focus {
            border: 1px solid #006400; /* Dark Green */
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(to right, #006400, #3F3D56); /* Green gradient */
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
            background: linear-gradient(to left, #006400, #3F3D56); /* Darker green gradient on hover */
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
        <div class="register-container">
            <h2>Register</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
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
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Register</button>
            </form>
        </div>
    </main>

</body>
</html>

<?php require_once 'includes/footer.php'; ?>
