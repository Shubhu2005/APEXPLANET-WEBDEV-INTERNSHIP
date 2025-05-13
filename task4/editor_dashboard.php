<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.html");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Editor Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>Editor Workspace</h1>
        <ul>
          <li><a href="dashboard.php">Main Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="dashboard">
      <div class="dashboard-header">
        <h2 class="user-welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <span class="role-badge role-editor">Editor</span>
      </div>
      
      <div class="dashboard-content">
        <h3>Content Management</h3>
        <p>As an editor, you have access to create, edit, and publish content on the platform.</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Create Content</h4>
            <p>Create new articles, pages, or resources.</p>
            <a href="create_content.php" class="btn" style="margin-top: 10px;">New Content</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Manage Content</h4>
            <p>Edit, update, or archive existing content.</p>
            <a href="manage_content.php" class="btn" style="margin-top: 10px;">Content Library</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Media Library</h4>
            <p>Upload and manage images and other media files.</p>
            <a href="media_library.php" class="btn" style="margin-top: 10px;">Media Files</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Comments</h4>
            <p>Moderate and respond to user comments.</p>
            <a href="comments.php" class="btn" style="margin-top: 10px;">Manage Comments</a>
          </div>
        </div>
        
        <div style="margin-top: 40px;">
          <h3>Content Analytics</h3>
          <div style="background-color: white; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <div style="display: flex; justify-content: space-between;">
              <div style="text-align: center; width: 23%; padding: 15px; background-color: #f8f9fa; border-radius: var(--border-radius);">
                <h4 style="color: var(--primary-color); margin-bottom: 5px;">Published</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary-color);">27</p>
              </div>
              <div style="text-align: center; width: 23%; padding: 15px; background-color: #f8f9fa; border-radius: var(--border-radius);">
                <h4 style="color: var(--secondary-color); margin-bottom: 5px;">Drafts</h4>
                <p style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">8</p>
              </div>
              <div style="text-align: center; width: 23%; padding: 15px; background-color: #f8f9fa; border-radius: var(--border-radius);">
                <h4 style="color: #6c757d; margin-bottom: 5px;">Comments</h4>
                <p style="font-size: 2rem; font-weight: bold; color: #6c757d;">42</p>
              </div>
              <div style="text-align: center; width: 23%; padding: 15px; background-color: #f8f9fa; border-radius: var(--border-radius);">
                <h4 style="color: #17a2b8; margin-bottom: 5px;">Views</h4>
                <p style="font-size: 2rem; font-weight: bold; color: #17a2b8;">1.2k</p>
              </div>
            </div>
          </div>
        </div>
        
        <div style="margin-top: 40px;">
          <h3>Recent Content Activity</h3>
          <div style="background-color: white; padding: 15px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <table style="width: 100%; border-collapse: collapse;">
              <thead>
                <tr>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Title</th>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Last Modified</th>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Status</th>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <!-- This would typically be populated from a database -->
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">Getting Started Guide</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 7200); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="background-color: var(--success-color); color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">Published</span></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Edit</a>
                    <a href="#" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">Delete</a>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">User Manual Updates</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 36000); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="background-color: #f0ad4e; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">Draft</span></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Edit</a>
                    <a href="#" class="btn btn-success" style="padding: 5px 10px; font-size: 0.8rem;">Publish</a>
                  </td>
                </tr>
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">Advanced Features</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 86400); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">Review</span></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <a href="#" class="btn" style="padding: 5px 10px; font-size: 0.8rem;">Edit</a>
                    <a href="#" class="btn btn-success" style="padding: 5px 10px; font-size: 0.8rem;">Approve</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
    <div style="margin-top: 20px; text-align: center; color: #666; font-size: 0.8rem;">
      &copy; <?php echo date('Y'); ?> User Authentication System. All rights reserved.
    </div>
  </div>
</body>
</html>