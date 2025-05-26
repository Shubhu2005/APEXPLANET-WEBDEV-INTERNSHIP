<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

$role = $_SESSION["role"];
$username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>User Dashboard</h1>
        <ul>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="dashboard">
      <div class="dashboard-header">
        <h2 class="user-welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <span class="role-badge role-<?php echo $role; ?>"><?php echo ucfirst($role); ?></span>
      </div>
      
      <?php if ($role === 'admin'): ?>
        <div class="dashboard-content">
          <h3>Admin Controls</h3>
          <p>As an admin, you have access to all system features.</p>
          <div style="margin-top: 20px;">
            <a href="manage_users.php" class="btn">Manage Users</a>
            <a href="system_settings.php" class="btn">System Settings</a>
            <a href="view_logs.php" class="btn">View Logs</a>
          </div>
        </div>
      <?php elseif ($role === 'editor'): ?>
        <div class="dashboard-content">
          <h3>Editor Tools</h3>
          <p>As an editor, you can manage content but have limited administrative access.</p>
          <div style="margin-top: 20px;">
            <a href="manage_content.php" class="btn">Manage Content</a>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
          </div>
        </div>
      <?php else: ?>
        <div class="dashboard-content">
          <h3>User Options</h3>
          <p>Welcome to your user dashboard. Here you can manage your account settings.</p>
          <div style="margin-top: 20px;">
            <a href="edit_profile.php" class="btn">Edit Profile</a>
            <a href="view_content.php" class="btn">View Content</a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>