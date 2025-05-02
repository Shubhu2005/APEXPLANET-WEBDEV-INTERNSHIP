<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid post ID.";
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']); // Convert to integer for additional security

// Check if confirmation is set
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    // Display confirmation page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Delete</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h2>Confirm Delete</h2>
            
            <div class="alert-error">
                <p>Are you sure you want to delete this post? This action cannot be undone.</p>
            </div>
            
            <div class="nav-links">
                <a href="delete_post.php?id=<?php echo $id; ?>&confirm=yes" class="button" style="background-color: #e74c3c;">Yes, Delete</a>
                <a href="dashboard.php" class="button">Cancel</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// If confirmed, delete the post
$stmt = $conn->prepare("DELETE FROM posts WHERE id=?");
$stmt->bind_param('i', $id);
$result = $stmt->execute();

if ($result) {
    $_SESSION['success_message'] = "Post deleted successfully.";
} else {
    $_SESSION['error_message'] = "Error deleting post: " . $conn->error;
}

header("Location: dashboard.php");
exit();
?>