<?php
include('../includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $sql = "SELECT * FROM users WHERE username='$username' AND role='user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the homepage after successful login
            header("Location: ../index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Invalid password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>User not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f0f4f7;
            color: #333;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 0 15px;
        }
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h1 {
            color: #27AE60;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #27AE60;
            border-color: #27AE60;
            padding: 10px;
            font-weight: 500;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #219150;
            border-color: #219150;
        }
        .form-control {
            border-radius: 30px;
            padding: 15px;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #27AE60;
            box-shadow: none;
        }
        .alert {
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .register-link {
            margin-top: 20px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            padding: 10px;
            font-weight: 500;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>User Login</h1>
    <form method="POST" id="loginForm">
        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <?php
    // Display error messages if present
    if (isset($error_message)) {
        echo "<div class='alert alert-danger'>$error_message</div>";
    }
    ?>
    
    <!-- Register button below the login form -->
    <div class="register-link">
        <a href="user_register.php" class="btn btn-secondary w-100">Register</a>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Simple form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!username || !password) {
            alert('Please fill in both fields.');
            e.preventDefault();
        }
    });
</script>

</body>
</html>
