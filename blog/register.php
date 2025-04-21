<?php
require 'db_connection.php';
session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if users table exists and has proper columns
    // First, let's check if the table exists without using prepared statements
    $check_username = false;
    
    // Skip the username check if there's an issue with the database
    try {
        $result = $conn->query("SHOW TABLES LIKE 'users'");
        if ($result->num_rows > 0) {
            // Now check if username already exists
            $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
            if ($check === false) {
                $errors[] = "Database error: " . $conn->error;
            } else {
                $check->bind_param('s', $username);
                $check->execute();
                $result = $check->get_result();
                if ($result->num_rows > 0) {
                    $errors[] = "Username already exists";
                }
            }
        }
    } catch (Exception $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // First, let's check if the users table has an email column
        $result = $conn->query("SHOW COLUMNS FROM users LIKE 'email'");
        $has_email = $result && $result->num_rows > 0;
        
        if ($has_email) {
            // If email column exists
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            if ($stmt === false) {
                $errors[] = "Database error: " . $conn->error;
            } else {
                $stmt->bind_param('sss', $username, $hashed_password, $email);
                
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $errors[] = "Registration error: " . $stmt->error;
                }
            }
        } else {
            // If no email column
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt === false) {
                $errors[] = "Database error: " . $conn->error;
            } else {
                $stmt->bind_param('ss', $username, $hashed_password);
                
                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $errors[] = "Registration error: " . $stmt->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #6D5BBA, #8D58BF);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }
        
        h1 {
            text-align: center;
            color: #8D58BF;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #8D58BF;
            outline: none;
            box-shadow: 0 0 5px rgba(141, 88, 191, 0.3);
        }
        
        input[type="submit"] {
            background-color: #8D58BF;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        input[type="submit"]:hover {
            background-color: #6D5BBA;
        }
        
        p {
            text-align: center;
            margin-top: 20px;
        }
        
        a {
            color: #8D58BF;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <?php if ($success): ?>
            <div class="success-message">
                Registration successful! <a href="login.php">Login here</a>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form action="register.php" method="post">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                
                <input type="submit" value="Register">
                
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>