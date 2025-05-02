<?php
require 'db_connection.php';

// Initialize query and search parameters
$search = "";
$query = "SELECT * FROM posts";
$params = [];
$paramTypes = "";

// Handle search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?";
    $searchParam = "%" . $search . "%";
    $params = [$searchParam, $searchParam];
    $paramTypes = "ss";
}

// Add pagination
$postsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $postsPerPage;

// Count total posts for pagination
$countQuery = str_replace("SELECT *", "SELECT COUNT(*)", $query);
$stmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$totalPostsResult = $stmt->get_result();
$totalPosts = $totalPostsResult->fetch_row()[0];
$totalPages = ceil($totalPosts / $postsPerPage);

// Add ORDER BY and LIMIT to the main query
$query .= " ORDER BY created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $postsPerPage;
$paramTypes .= "ii";

// Execute main query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>All Blog Posts</h2>
        
        <div class="nav-links">
            <a href="login.php" class="button">Login</a>
            <a href="register.php" class="button">Register</a>
        </div>
        
        <!-- Search Form -->
        <div class="search-container">
            <form action="index.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by title or content..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="search-button">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="reset-search">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Search Results Info -->
        <?php if (!empty($search)): ?>
            <div class="search-results-info">
                <p>Found <?php echo $totalPosts; ?> result(s) for: "<?php echo htmlspecialchars($search); ?>"</p>
            </div>
        <?php endif; ?>
        
        <?php if($result->num_rows > 0): ?>
            <div class="posts-container">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <div class="post-content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></div>
                        <span class="post-meta">Posted on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="pagination-link active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo ($page + 1); ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link">Next &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="no-posts">
                <?php if (!empty($search)): ?>
                    <p>No posts found matching your search. <a href="index.php">View all posts</a></p>
                <?php else: ?>
                    <p>No posts available yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>