<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        h2 {
            color: #6D5BBA;
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            font-size: 24px;
        }
        
        .nav-links {
            margin-bottom: 30px;
            display: flex;
            gap: 20px;
        }
        
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #8D58BF;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s;
            font-weight: bold;
        }
        
        .button:hover {
            background: #6D5BBA;
        }
        
        .post {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .post:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .post h3 {
            color: #333;
            margin-top: 0;
            font-size: 20px;
        }
        
        .post-content {
            margin-bottom: 15px;
        }
        
        .post-meta {
            color: #777;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }
        
        .action-links {
            margin-top: 10px;
        }
        
        .action-links a {
            color: #8D58BF;
            text-decoration: none;
            margin-right: 10px;
            font-size: 14px;
            transition: color 0.2s;
        }
        
        .action-links a:hover {
            color: #6D5BBA;
            text-decoration: underline;
        }
        
        .delete-link {
            color: #ff5252 !important;
        }
        
        .delete-link:hover {
            color: #ff0000 !important;
        }
        
        .no-posts {
            text-align: center;
            padding: 40px 0;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        
        <div class="nav-links">
            <a href="create_post.php" class="button">Create New Post</a>
            <a href="logout.php" class="button" style="background-color: #f44336;">Logout</a>
        </div>
        
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <div class="post-content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></div>
                    <span class="post-meta">Posted on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></span>
                    <div class="action-links">
                        <a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="delete-link">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>No posts found. Create your first post!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>