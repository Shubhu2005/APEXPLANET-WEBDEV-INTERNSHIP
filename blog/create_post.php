<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $errorMessage = "Both title and content are required!";
    } else {
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param('ss', $title, $content);
        
        if ($stmt->execute()) {
            $successMessage = "Post created successfully!";
            // Clear form data after successful submission
            $title = $content = '';
        } else {
            $errorMessage = "Error creating post: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Create New Post</h2>
        
        <div class="nav-links">
            <a href="dashboard.php" class="button">Back to Dashboard</a>
        </div>
        
        <?php if (!empty($successMessage)): ?>
            <div class="alert-success">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="alert-error">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        
        <form action="create_post.php" method="post">
            <div class="form-group">
                <label for="title">Post Title</label>
                <input type="text" id="title" name="title" placeholder="Enter post title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="content">Post Content</label>
                <textarea id="content" name="content" placeholder="Write your post content here..." rows="10" required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit">Create Post</button>
                <a href="dashboard.php" class="button" style="background-color: #ccc; color: #333;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>