<?php
function check_login($conn)
{
    if(isset($_SESSION['user_id']))
    {
        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result && $result->num_rows > 0)
        {
            $user_data = $result->fetch_assoc();
            return $user_data;
        }
    }

    header("Location: login.php");
    die;
}

function get_posts($conn, $limit = 10)
{
    $query = "SELECT p.*, u.username, c.name AS category_name, 
              (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count 
              FROM posts p 
              JOIN users u ON p.author_id = u.id 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'published' 
              ORDER BY p.created_at DESC LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}

function get_post($conn, $post_id) {
    $query = "SELECT p.*, u.username, c.name AS category_name 
              FROM posts p 
              JOIN users u ON p.author_id = u.id 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function increment_views($conn, $post_id)
{
    $query = "UPDATE posts SET views = views + 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
}

function get_comments($conn, $post_id)
{
    $query = "SELECT c.*, u.username FROM comments c 
              JOIN users u ON c.user_id = u.id 
              WHERE c.post_id = ? 
              ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    return $stmt->get_result();
}

function add_comment($conn, $post_id, $user_id, $content)
{
    $query = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    return $stmt->execute();
}

function update_comment($conn, $comment_id, $user_id, $content)
{
    $query = "UPDATE comments SET content = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $content, $comment_id, $user_id);
    return $stmt->execute();
}

function delete_comment($conn, $comment_id, $user_id)
{
    $query = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $comment_id, $user_id);
    return $stmt->execute();
}

function get_categories($conn)
{
    $query = "SELECT * FROM categories ORDER BY name";
    $result = $conn->query($query);
    return $result;
}

function add_post($conn, $title, $content, $author_id, $category_id, $status)
{
    $query = "INSERT INTO posts (title, content, author_id, category_id, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiis", $title, $content, $author_id, $category_id, $status);
    return $stmt->execute();
}

function update_post($conn, $post_id, $title, $content, $category_id, $status, $image_path = null) {
    $query = "UPDATE posts SET title = ?, content = ?, category_id = ?, status = ?";
    $params = array($title, $content, $category_id, $status);
    $types = "ssss";

    if ($image_path !== null) {
        $query .= ", image = ?";
        $params[] = $image_path;
        $types .= "s";
    }

    $query .= " WHERE id = ?";
    $params[] = $post_id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Failed to update post: " . $stmt->error);
        return false;
    }
}

function delete_post($conn, $post_id) {
    $conn->begin_transaction();

    try {
        // First, delete all comments associated with the post
        $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        // Then, delete the post
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error deleting post: " . $e->getMessage());
        return false;
    }
}
function get_user_posts($conn, $user_id)
{
    $query = "SELECT p.*, c.name AS category_name, 
              (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count 
              FROM posts p 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.author_id = ? 
              ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function update_user_profile($conn, $user_id, $username, $email, $password = null)
{
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
    } else {
        $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $username, $email, $user_id);
    }
    return $stmt->execute();
}

function add_event($conn, $title, $description, $start_date, $end_date, $created_by)
{
    $query = "INSERT INTO events (title, description, start_date, end_date, created_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $created_by);
    return $stmt->execute();
}

function get_events($conn, $start_date, $end_date)
{
    $query = "SELECT * FROM events WHERE start_date >= ? AND end_date <= ? ORDER BY start_date";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    return $stmt->get_result();
}

function upload_image($file) {
    $target_dir = __DIR__ . "/../uploads/";
    
    // Create the uploads directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_file_name = md5(time() . $file["name"]) . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;
    
    // Check if image file is an actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return false;
    }
    
    // Check file size (limit to 5MB)
    if ($file["size"] > 5000000) {
        return false;
    }
    
    // Allow certain file formats
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    if(!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    // Try to move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "/uploads/" . $new_file_name;
    } else {
        error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
        return false;
    }
}

function add_category($conn, $name)
{
    $query = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $name);
    return $stmt->execute();
}

function update_category($conn, $id, $name)
{
    $query = "UPDATE categories SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $name, $id);
    return $stmt->execute();
}

function delete_category($conn, $id)
{
    $query = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function get_latest_posts($conn, $limit = 5)
{
    $query = "SELECT p.*, u.username, c.name AS category_name 
              FROM posts p 
              JOIN users u ON p.author_id = u.id 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'published' 
              ORDER BY p.created_at DESC LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}

function get_new_posts($conn, $limit = 5)
{
    $query = "SELECT p.*, u.username, c.name AS category_name 
              FROM posts p 
              JOIN users u ON p.author_id = u.id 
              JOIN categories c ON p.category_id = c.id 
              WHERE p.status = 'published' 
              ORDER BY p.created_at DESC LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    return $stmt->get_result();
}

function generate_verification_token() {
    return bin2hex(random_bytes(32));
}

function send_verification_email($email, $token) {
    $to = $email;
    $subject = "Verify your email address";
    $message = "Click the following link to verify your email address: ";
    $message .= "http://yourdomain.com/verify.php?token=" . $token;
    $headers = "From: noreply@yourdomain.com";

    return mail($to, $subject, $message, $headers);
}

function verify_email($conn, $token) {
    $query = "UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    return $stmt->execute() && $stmt->affected_rows > 0;
}

function is_email_verified($conn, $user_id) {
    $query = "SELECT is_verified FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['is_verified'] == 1;
}

// Update the register_user function
function register_user($conn, $username, $email, $password, $role)
{
    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User already exists
        return false;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $verification_token = bin2hex(random_bytes(16));
    $is_verified = 0;

    $query = "INSERT INTO users (username, email, password, role, verification_token, is_verified) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $username, $email, $hashed_password, $role, $verification_token, $is_verified);
    
    if ($stmt->execute()) {
        // Uncomment the following line when email verification is set up
        // send_verification_email($email, $verification_token);
        return true;
    }
    return false;
}

function delete_user($conn, $user_id) {
    $conn->begin_transaction();

    try {
        // Delete user's comments
        $stmt = $conn->prepare("DELETE FROM comments WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Delete user's posts
        $stmt = $conn->prepare("DELETE FROM posts WHERE author_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
}


// Update the login_user function
function login_user($conn, $username, $password)
{
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            } else {
                $_SESSION['verification_required'] = $user['email'];
                return false;
            }
        }
    }
    return false;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: /login.php");
        exit();
    }
}


?>