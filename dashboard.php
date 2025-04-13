<?php
session_start();
require_once "db_connection.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$loggedInUsername = $_SESSION['username'];
$sql_user = "SELECT username, email, phone, dob, address FROM users WHERE username=?";
$stmt_user = $conn->prepare($sql_user);

if (!$stmt_user) {
    die("SQL error: " . $conn->error);
}

$stmt_user->bind_param("s", $loggedInUsername);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    die("User not found.");
}

$stmt_user->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            color: #6D5BBA;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
        }
        .highlight {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <p><span class="highlight">Email:</span> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><span class="highlight">Phone:</span> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><span class="highlight">Date of Birth:</span> <?php echo htmlspecialchars($user['dob']); ?></p>
        <p><span class="highlight">Address:</span> <?php echo htmlspecialchars($user['address']); ?></p>
    </div>
</body>
</html>
