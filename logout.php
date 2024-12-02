<?php  
session_start();

if (isset($_POST['confirm_logout']) && $_POST['confirm_logout'] === 'yes') {
    session_destroy();
    $logged_out = true; // User has logged out
} else {
    $logged_out = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f9f4; /* Light green background */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .logout-container {
            text-align: center;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.15);
            color: #333;
            max-width: 400px;
            width: 90%;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #006400; /* Dark Green */
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .spinner {
            margin: 20px auto;
            border: 4px solid #ddd;
            border-top: 4px solid #006400; /* Dark Green Spinner */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .confirmation-buttons {
            margin-top: 20px;
        }

        .confirmation-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin: 5px;
        }

        .yes-button {
            background-color: #006400; /* Dark Green */
            color: white;
        }

        .yes-button:hover {
            background-color: #004d00; /* Darker Green on Hover */
        }

        .no-button {
            background-color: #f44336; /* Red */
            color: white;
        }

        .no-button:hover {
            background-color: #d32f2f; /* Darker Red on Hover */
        }
    </style>
</head>
<body>

<?php if ($logged_out): ?>
    <!-- "Logging Out" message and spinner after log-out confirmation -->
    <div class="logout-container">
        <h1>Logging Out</h1>
        <p>You have been successfully logged out. Redirecting to the homepage...</p>
        <div class="spinner"></div>
    </div>
    <script>
        // Redirect after 3 seconds
        setTimeout(() => {
            window.location.href = "index.php";
        }, 1000); // Redirect after 1 second
    </script>
<?php else: ?>
    <!-- Log-out confirmation page -->
    <div class="logout-container">
        <h1>Do you want to log out?</h1>
        <p>If yes, proceed to log out, if not, click cancel.</p>
        <div class="confirmation-buttons">
            <form method="post">
                <button type="submit" name="confirm_logout" value="yes" class="yes-button">Yes, Log Out</button>
                <button type="button" onclick="window.location.href='index.php'" class="no-button">Cancel</button>
            </form>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
