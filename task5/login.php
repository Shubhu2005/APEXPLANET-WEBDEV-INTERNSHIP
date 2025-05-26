<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Redirect by role
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] === 'editor') {
            header("Location: editor_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        // Store error message and redirect back to login page
        $_SESSION['login_error'] = "Invalid email or password. Please try again.";
        header("Location: login.html");
        exit;
    }
}

// If there's an error message to display, we need a template for login page
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear the error after displaying
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>User Authentication System</h1>
        <ul>
          <li><a href="register.html">Register</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="form-container">
      <h2>User Login</h2>
      <?php if (isset($error_message)): ?>
        <div class="message message-error"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <form action="login.php" method="post">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" required>
        </div>

        <input type="submit" value="Login">
      </form>
      <p style="margin-top: 20px; text-align: center;">
        Don't have an account? <a href="register.html">Register here</a>
      </p>
    </div>
  </div>
</body>
</html>
<?php
    exit;
}
?>