<?php
session_start();
require_once("db_config.php");

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user information
$username = $_SESSION['username'];
$sql = "SELECT username, email, created_at FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Your Website - Profile</title>";
    echo "<link rel='stylesheet' href='styles.css'>"; 
    echo "</head>";
    echo "<body>";


    echo "<main>";
    echo "<section class='profile-container'>";
    echo "<h2>Profile</h2>";
    echo "<div class='profile-info'>";
    echo "<p>Username: <span>" . $row['username'] . "</span></p>";
    echo "<p>Email: <span>" . $row['email'] . "</span></p>";
    echo "<p>Account Created: <span>" . $row['created_at'] . "</span></p>";
    echo "</div>";
    echo "</section>";
    echo "</main>";


    echo "</body>";
    echo "</html>";
} else {
    echo "Error: User not found";
}

mysqli_close($conn);

// استاد میدونم چه گندیه اما چیز دیگه ای به ذهنم نمیرسید
//از قدیم هم میگن چیزی که کار میکنه رو دستش نزن
?>


