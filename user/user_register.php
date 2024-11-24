<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";
    if ($conn->query($sql)) {
        header("Location: user_login.php");  // Redirect to login page after successful registration
    } else {
        echo "<div class='alert alert-danger'>Failed to register: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

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
        .registration-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .registration-container h1 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #4CAF50;
            border-color: #4CAF50;
            padding: 10px;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #45a049;
            border-color: #45a049;
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
    </style>
</head>
<body>

<div class="registration-container">
    <h1>Register as a User</h1>
    <form method="POST" id="registrationForm">
        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Simple form validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!username || !email || !password) {
            alert('Please fill in all fields.');
            e.preventDefault();
        }
    });
</script>

</body>
</html>
