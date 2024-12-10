<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$user_data = check_login($conn);

if ($user_data['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($query);

$page_title = 'Manage Users';
include '../includes/header.php';
?>

<style>
    :root {
        --primary-color: #16423C;
        --secondary-color: #6A9C89;
        --accent-color: #C4DAD2;
        --background-light: #E9EFEC;
        --background-white: #FFFFFF;
        --text-primary: #000000;
        --text-secondary: #333333;
        --border-color: #C4DAD2;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
    }

    body {
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background-color: var(--background-light);
    }

    .manage-users-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: var(--background-white);
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .manage-users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--accent-color);
    }

    .manage-users-title {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin: 0;
        font-weight: 700;
    }

    .add-user-btn {
        background-color: var(--secondary-color);
        color: var(--background-white);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .add-user-btn:hover {
        background-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .users-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
    }

    .users-table th,
    .users-table td {
        padding: 1rem;
        text-align: left;
    }

    .users-table th {
        background-color: var(--accent-color);
        color: var(--primary-color);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.05em;
    }

    .users-table tr {
        background-color: var(--background-white);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .users-table tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .user-role {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .role-admin {
        background-color: var(--primary-color);
        color: var(--background-white);
    }

    .role-writer {
        background-color: var(--secondary-color);
        color: var(--background-white);
    }

    .role-user {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }

    .user-actions {
        display: flex;
        gap: 0.5rem;
    }

    .user-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .user-action:hover {
        transform: translateY(-2px);
    }

    .user-action-edit {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }

    .user-action-delete {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .manage-users-container {
            padding: 1rem;
        }

        .manage-users-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .manage-users-title {
            font-size: 2rem;
        }

        .add-user-btn {
            width: 100%;
            text-align: center;
        }

        .users-table {
            font-size: 0.875rem;
        }

        .users-table th,
        .users-table td {
            padding: 0.75rem 0.5rem;
        }

        .user-actions {
            flex-direction: column;
        }

        .user-action {
            text-align: center;
        }
    }
</style>

<div class="manage-users-container">
    <div class="manage-users-header">
        <h1 class="manage-users-title">Manage Users</h1>
    </div>

    <table class="users-table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="user-role role-<?php echo strtolower($user['role']); ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                    <td>
                        <div class="user-actions">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="user-action user-action-edit">Edit</a>
                            <?php if ($user['id'] != $user_data['id']): ?>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="user-action user-action-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>

