<?php
require 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is set in the URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if post exists
if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}

$post = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param('ssi', $title, $content, $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        h2 {
            color: #6D5BBA;
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            font-family: inherit;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, textarea:focus {
            border-color: #8D58BF;
            outline: none;
            box-shadow: 0 0 5px rgba(141, 88, 191, 0.3);
        }
        
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
        }
        
        button {
            background-color: #8D58BF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #6D5BBA;
        }
        
        .cancel-button {
            background-color: #ccc;
        }
        
        .cancel-button:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2>
        
        <form method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>
            
            <div class="button-group">
                <button type="submit">Update Post</button>
                <a href="dashboard.php" style="display: inline-block; background-color: #ccc; color: #333; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>