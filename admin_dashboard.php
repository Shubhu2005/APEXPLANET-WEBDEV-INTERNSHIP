<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>Administration Panel</h1>
        <ul>
          <li><a href="dashboard.php">Main Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="dashboard">
      <div class="dashboard-header">
        <h2 class="user-welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <span class="role-badge role-admin">Admin</span>
      </div>
      
      <div class="dashboard-content">
        <h3>Administrative Controls</h3>
        <p>As an administrator, you have full access to all system features and settings.</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">User Management</h4>
            <p>Add, edit, or remove users from the system.</p>
            <a href="manage_users.php" class="btn" style="margin-top: 10px;">Manage Users</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">System Settings</h4>
            <p>Configure system-wide settings and preferences.</p>
            <a href="system_settings.php" class="btn" style="margin-top: 10px;">System Settings</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Security Logs</h4>
            <p>View login attempts and security events.</p>
            <a href="security_logs.php" class="btn" style="margin-top: 10px;">View Logs</a>
          </div>
          
          <div style="background-color: #f8f9fa; padding: 20px; border-radius: var(--border-radius); box-shadow: var(--box-shadow);">
            <h4 style="color: var(--primary-color); margin-bottom: 10px;">Database Backup</h4>
            <p>Create and manage database backups.</p>
            <a href="database_backup.php" class="btn" style="margin-top: 10px;">Backup Tools</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>