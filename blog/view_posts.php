<?php
require 'db_connection.php';
session_start();

// Initialize query
$query = "SELECT * FROM posts";
$params = [];
$paramTypes = "";

// Add pagination
$postsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $postsPerPage;

// Count total posts for pagination
$countQuery = "SELECT COUNT(*) FROM posts";
$stmt = $conn->prepare($countQuery);
$stmt->execute();
$totalPostsResult = $stmt->get_result();
$totalPosts = $totalPostsResult->fetch_row()[0];
$totalPages = ceil($totalPosts / $postsPerPage);

// Add ORDER BY and LIMIT to the main query
$query .= " ORDER BY created_at DESC LIMIT ?, ?";
$params = [$offset, $postsPerPage];
$paramTypes = "ii";

// Execute main query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch posts
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Posts</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>All Posts</h1>
    
    <div class="nav-links">
      <a href="dashboard.php" class="button">Dashboard</a>
      <a href="create_post.php" class="button">Create New Post</a>
    </div>

    <?php if(count($posts) > 0): ?>
      <div class="posts-container">
        <?php foreach($posts as $post): ?>
          <div class="post">
            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
            <div class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
            <span class="post-meta">Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></span>
            <div class="action-links">
              <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> | 
              <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      
      <!-- Enhanced Pagination -->
      <?php if ($totalPages > 1): ?>
        <div class="pagination">
          <!-- First page link -->
          <?php if ($page > 1): ?>
            <a href="?page=1" class="pagination-link">&laquo; First</a>
          <?php endif; ?>
          
          <!-- Previous page link -->
          <?php if ($page > 1): ?>
            <a href="?page=<?php echo ($page - 1); ?>" class="pagination-link">&lsaquo; Prev</a>
          <?php endif; ?>
          
          <!-- Page numbers -->
          <?php
            // Display limited number of page links
            $maxLinks = 5;
            $startPage = max(1, min($page - floor($maxLinks/2), $totalPages - $maxLinks + 1));
            $endPage = min($totalPages, $startPage + $maxLinks - 1);
            
            if ($startPage > 1) {
              echo '<span class="pagination-ellipsis">...</span>';
            }
            
            for ($i = $startPage; $i <= $endPage; $i++):
          ?>
            <?php if ($i == $page): ?>
              <span class="pagination-link active"><?php echo $i; ?></span>
            <?php else: ?>
              <a href="?page=<?php echo $i; ?>" class="pagination-link"><?php echo $i; ?></a>
            <?php endif; ?>
          <?php endfor; ?>
          
          <?php if ($endPage < $totalPages): ?>
            <span class="pagination-ellipsis">...</span>
          <?php endif; ?>
          
          <!-- Next page link -->
          <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo ($page + 1); ?>" class="pagination-link">Next &rsaquo;</a>
          <?php endif; ?>
          
          <!-- Last page link -->
          <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $totalPages; ?>" class="pagination-link">Last &raquo;</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      
    <?php else: ?>
      <div class="no-posts">
        <p>No posts available. <a href="create_post.php">Create your first post!</a></p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>