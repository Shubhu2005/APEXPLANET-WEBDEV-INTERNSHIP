<link rel="stylesheet" href="styles.css">
<div class="container">
  <h1>All Posts</h1>

  <?php foreach($posts as $post): ?>
    <div class="post">
      <h3><?php echo htmlspecialchars($post['title']); ?></h3>
      <p><?php echo htmlspecialchars($post['content']); ?></p>
      <small>Created at: <?php echo $post['created_at']; ?></small><br><br>
      <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn">Edit</a>
      <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn" style="background: crimson;">Delete</a>
    </div>
  <?php endforeach; ?>

</div>
