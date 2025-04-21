<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param('ss', $title, $content);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>

<link rel="stylesheet" href="styles.css">
<div class="container">
  <h1>Create New Post</h1>
  <form action="create_post.php" method="post">
    <input type="text" name="title" placeholder="Post Title" required>
    <textarea name="content" placeholder="Post Content" rows="5" required></textarea>
    <input type="submit" value="Create Post">
  </form>
</div>

