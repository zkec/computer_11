<?php
require_once("db_config.php");

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Get form data 
$username = $_POST["username"];
$password = $_POST["password"];

// Check user credentials in the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
  $user = mysqli_fetch_assoc($result);

  // Verify password using password_verify (secure)
  if (password_verify($password, $user['password'])) {
    // Login successful, start a session
    session_start();
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['user_id'];

    // Redirect to index.php after successful login
    header("Location: index.php");
    exit;
  } else {
    echo "Invalid username or password";
  }
} else {
  echo "Invalid username or password";
}

mysqli_close($conn); // Close the database connection
?>
