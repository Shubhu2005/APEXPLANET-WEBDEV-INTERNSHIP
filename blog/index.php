<?php
require 'db_connection.php';

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<h2>All Blog Posts</h2>
<a href="login.php">Login</a> | <a href="register.php">Register</a>
<br><br>

<?php while($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small>Posted on: <?php echo $row['created_at']; ?></small>
    </div>
<?php endwhile; ?>
