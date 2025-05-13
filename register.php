<?php
session_start();
require 'db.php'; // your PDO connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Server-side validation
    if (strlen($password) < 6) {
        $_SESSION['reg_error'] = "Password must be at least 6 characters.";
        header("Location: register.html");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword, $role]);
        
        $_SESSION['reg_success'] = "Registration successful!";
        header("Location: login.html");
        exit;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $_SESSION['reg_error'] = "Username or email already exists.";
        } else {
            $_SESSION['reg_error'] = "Error: " . $e->getMessage();
        }
        header("Location: register.html");
        exit;
    }
}

// If there's an error message to display, we need a template for registration page
if (isset($_SESSION['reg_error'])) {
    $error_message = $_SESSION['reg_error'];
    unset($_SESSION['reg_error']); // Clear the error after displaying
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header>
      <nav>
        <h1>User Authentication System</h1>
        <ul>
          <li><a href="login.html">Login</a></li>
        </ul>
      </nav>
    </header>
    
    <div class="form-container">
      <h2>User Registration</h2>
      <?php if (isset($error_message)): ?>
        <div class="message message-error"><?php echo $error_message; ?></div>
      <?php endif; ?>
      <form action="register.php" method="post" onsubmit="return validateForm()">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" required>
          <small style="color: #666; font-size: 0.8rem;">Password must be at least 6 characters</small>
        </div>

        <div class="form-group">
          <label for="role">Role:</label>
          <select name="role" id="role">
            <option value="user">User</option>
            <option value="editor">Editor</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <input type="submit" value="Register">
      </form>
      <p style="margin-top: 20px; text-align: center;">
        Already have an account? <a href="login.html">Login here</a>
      </p>
    </div>
  </div>

  <script>
    function validateForm() {
      const password = document.querySelector('input[name="password"]').value;
      if (password.length < 6) {
        alert("Password must be at least 6 characters.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
<?php
    exit;
}
?>