<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Blog'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #16423C;
            --text-primary: #000000;
            --text-secondary: #6A9C89;
            --background-light: #E9EFEC;
            --background-white: #C4DAD2;
            --spacing-unit: 1rem;
        }

        body {
            font-family: 'Roboto', 'Arial', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background-color: var(--background-white);
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #16423C;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-unit);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            padding: 0 2rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--background-light);
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.2s ease;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        nav ul li a:hover {
            color: #16423C;
            background-color: var(--background-light);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--background-light);
            text-decoration: none;
        }

        .auth-buttons {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .auth-buttons a {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            color: var(--background-light);
        }

        .auth-buttons a:hover {
            color: #16423C;
            background-color: var(--background-light);
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                height: auto;
                padding: var(--spacing-unit) 0;
            }

            nav ul {
                flex-direction: column;
                align-items: center;
                gap: calc(var(--spacing-unit) * 0.5);
            }

            .auth-buttons {
                margin-top: var(--spacing-unit);
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="/index.php" class="logo">Builders/Pandayan</a>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_role'] == 'admin'): ?>
                            <li><a href="/admin/dashboard.php">Admin Dashboard</a></li>
                        <?php elseif ($_SESSION['user_role'] == 'writer'): ?>
                            <li><a href="/writer/dashboard.php">Writer Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/user/profile.php">Profile</a></li>
                        <li><a href="/logout.php">Logout</a></li>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="/login.php">Login</a>
                            <a href="/register.php">Register</a>
                        </div>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
        <!-- Main content will be inserted here -->
    </main>


</body>
</html>

