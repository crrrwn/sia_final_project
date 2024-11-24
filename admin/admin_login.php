<?php
include('../includes/db.php');
session_start();

$message = ''; // Variable to store the message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND role='admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];  // Set the username in session
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");  // Redirect to admin dashboard
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Invalid password.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Admin not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        .form-control {
            border-radius: 30px;
            padding: 15px;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            padding: 10px;
            border-radius: 30px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            margin-top: 15px;
            padding: 10px;
            border-radius: 30px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .alert {
            margin-top: 15px;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Admin Login</h1>
    
    <!-- Display the message at the top -->
    <?= $message; ?>

    <form method="POST" id="loginForm">
        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    
    <!-- Register button below the login form -->
    <a href="admin_register.php" class="btn btn-secondary w-100">Register</a>
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
