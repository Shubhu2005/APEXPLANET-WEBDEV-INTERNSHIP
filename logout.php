<?php
session_start();
session_destroy();

// Redirect after showing a message
?>
<!DOCTYPE html>
<html>
<head>
  <title>Logged Out</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <meta http-equiv="refresh" content="2;url=login.html">
</head>
<body>
  <div class="container">
    <div class="form-container" style="text-align: center;">
      <h2>You have been logged out</h2>
      <div class="message message-success">
        You have been successfully logged out of your account.
      </div>
      <p style="margin-top: 20px;">
        You will be redirected to the login page in a few seconds.
        If you are not redirected, <a href="login.html">click here</a>.
      </p>
    </div>
  </div>
</body>
</html>