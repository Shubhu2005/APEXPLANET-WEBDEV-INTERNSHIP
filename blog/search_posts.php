<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize search parameters
$search = "";
$searchBy = "both"; // Default to search in both title and content
$query = "SELECT * FROM posts";
$params = [];
$paramTypes = "";

// Handle search
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchBy = isset($_GET['search_by']) ? $_GET['search_by'] : 'both';
    
    if ($searchBy == 'title') {
        $query = "SELECT * FROM posts WHERE title LIKE ?";
        $searchParam = "%" . $search . "%";
        $params = [$searchParam];
        $paramTypes = "s";
    } elseif ($searchBy == 'content') {
        $query = "SELECT * FROM posts WHERE content LIKE ?";
        $searchParam = "%" . $search . "%";
        $params = [$searchParam];
        $paramTypes = "s";
    } else { // both
        $query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?";
        $searchParam = "%" . $search . "%";
        $params = [$searchParam, $searchParam];
        $paramTypes = "ss";
    }
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
    <title>Search Posts</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Search Posts</h2>
        
        <div class="nav-links">
            <a href="dashboard.php" class="button">Back to Dashboard</a>
            <a href="create_post.php" class="button">Create New Post</a>
        </div>
        
        <!-- Advanced Search Form -->
        <div class="search-container">
            <form action="search_posts.php" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by title or content..." value="<?php echo htmlspecialchars($search); ?>" required>
                
                <select name="search_by">
                    <option value="both" <?php echo $searchBy == 'both' ? 'selected' : ''; ?>>Title & Content</option>
                    <option value="title" <?php echo $searchBy == 'title' ? 'selected' : ''; ?>>Title Only</option>
                    <option value="content" <?php echo $searchBy == 'content' ? 'selected' : ''; ?>>Content Only</option>
                </select>
                
                <button type="submit" class="search-button">Search</button>
                <?php if (!empty($search)): ?>
                    <a href="search_posts.php" class="reset-search">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Search Results Info -->
        <?php if (!empty($search)): ?>
            <div class="search-results-info">
                <p>Found <?php echo $totalPosts; ?> result(s) for: "<?php echo htmlspecialchars($search); ?>" 
                   in <?php echo $searchBy == 'both' ? 'title and content' : ($searchBy == 'title' ? 'titles only' : 'content only'); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if($result->num_rows > 0): ?>
            <div class="posts-container">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <div class="post-content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></div>
                        <span class="post-meta">Posted on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></span>
                        <div class="action-links">
                            <a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                            <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Enhanced Pagination Section (Replace existing pagination section in search_posts.php) -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <!-- First page link -->
                    <?php if ($page > 1): ?>
                        <a href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_by=' . urlencode($searchBy) : ''; ?>" class="pagination-link">&laquo; First</a>
                    <?php endif; ?>
                    
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_by=' . urlencode($searchBy) : ''; ?>" class="pagination-link">&lsaquo; Prev</a>
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
                            <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_by=' . urlencode($searchBy) : ''; ?>" class="pagination-link"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($endPage < $totalPages): ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo ($page + 1); ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_by=' . urlencode($searchBy) : ''; ?>" class="pagination-link">Next &rsaquo;</a>
                    <?php endif; ?>
                    
                    <!-- Last page link -->
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $totalPages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) . '&search_by=' . urlencode($searchBy) : ''; ?>" class="pagination-link">Last &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>No posts found. <?php echo !empty($search) ? 'Try a different search term or search criteria.' : 'No posts available in the system.'; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>