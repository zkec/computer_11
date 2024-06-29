<?php

require_once("connect.php"); 

// Function to prevent SQL injection
function escapeString($data) {
  global $conn;
  $data = htmlspecialchars($data);
  return mysqli_real_escape_string($conn, $data);
}

// Get form data 
$username = escapeString(trim($_POST["username"])); // Trim leading/trailing whitespace
$email = escapeString(trim($_POST["email"]));
$password = $_POST["password"];

// Check if username or email already exists in the database
$errors = array(); // Array to store any validation errors

// Check if username is empty
if (empty($username)) {
  $errors[] = "Username is required.";
} else {
  // Check for username length 
  if (strlen($username) < 3 || strlen($username) > 20) {
    $errors[] = "Username must be between 3 and 20 characters.";
  }
  // Check for username existence 
  $sql = "SELECT * FROM users WHERE username = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if (mysqli_num_rows($result) > 0) {
    $errors[] = "Username already exists.";
  }
  mysqli_stmt_close($stmt);
  mysqli_free_result($result);
}

// Check if email is valid format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = "Invalid email format.";
} else {
  // Check for email existence
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if (mysqli_num_rows($result) > 0) {
    $errors[] = "Email already exists.";
  }
  mysqli_stmt_close($stmt);
  mysqli_free_result($result);
}

// Check if password is empty
if (empty($password)) {
  $errors[] = "Password is required.";
}
// If there are no errors, proceed with registration
if (empty($errors)) {

  // Hash the password for secure storage
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user data into the database
  $sql = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";

  // Prepare the statement
  $stmt = mysqli_prepare($conn, $sql);

  // Bind values to the statement
  mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

  // Execute the statement
  if (mysqli_stmt_execute($stmt)) {
    session_start();
    $_SESSION['register_success'] = true;
    header("Location: index.php");
    exit;
  } else {
    $error_message = "Error registering user: " . mysqli_error($conn);
    $_SESSION['register_success'] = false;  // Set to false on failure
    header("Location: index.php");
    exit;
  }

  // Close the statement
  mysqli_stmt_close($stmt);

} else {
  // Display any validation errors
  echo "Registration failed: <br>";
  foreach ($errors as $error) {
    echo "- " . $error . "<br>";
  }
}

// Close the database connection
mysqli_close($conn);

?>
