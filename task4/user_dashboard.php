<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.html");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>User Portal</h1>
        <ul>
          <li><a href="dashboard.php">Main Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="dashboard">
      <div class="dashboard-header">
        <h2 class="user-welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <span class="role-badge role-user">User</span>
      </div>
      
      <div class="dashboard-content">
        <h3>Your Account</h3>
        <p>Welcome to your personal dashboard. Here you can manage your account and access platform features.</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Profile Settings</h4>
            <p>Update your personal information and preferences.</p>
            <a href="edit_profile.php" class="btn" style="margin-top: 10px;">Edit Profile</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Messages</h4>
            <p>View your inbox and send messages to other users.</p>
            <a href="messages.php" class="btn" style="margin-top: 10px;">Go to Messages</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Content Library</h4>
            <p>Browse available content and resources.</p>
            <a href="content_library.php" class="btn" style="margin-top: 10px;">View Library</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Security</h4>
            <p>Change your password and review account security.</p>
            <a href="security_settings.php" class="btn" style="margin-top: 10px;">Security Settings</a>
          </div>
        </div>
        
        <div style="margin-top: 40px;">
          <h3>Recent Activity</h3>
          <div style="background-color: white; padding: 15px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <table style="width: 100%; border-collapse: collapse;">
              <thead>
                <tr>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Date</th>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Activity</th>
                  <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">Status</th>
                </tr>
              </thead>
              <tbody>
                <!-- This would typically be populated from a database -->
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 3600); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">Profile Updated</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="color: var(--success-color);">Completed</span></td>
                </tr>
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 86400); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">Content Accessed</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="color: var(--success-color);">Completed</span></td>
                </tr>
                <tr>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><?php echo date('Y-m-d H:i', time() - 172800); ?></td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;">Password Changed</td>
                  <td style="padding: 10px; border-bottom: 1px solid #eee;"><span style="color: var(--success-color);">Completed</span></td>
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