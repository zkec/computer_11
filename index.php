<?php
session_start();
require_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hooman Hoseini - Personal Website</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <header>
    <h1>Hooman Hoseini</h1>
    <p>a person who hates web developer</p>
  </header>

  <?php
  if (isset($_SESSION['register_success']) && $_SESSION['register_success'] == true) {
    echo "<p>Registration successful, now you can login!</p>";
    // Clear the session variable to avoid repeated messages
    unset($_SESSION['register_success']);
  }
  ?>

  <nav style="display: flex; justify-content: space-between;">
    <ul>
      <li><a href="about.html">About Me</a></li>
      <li><a href="resume.html">Resume</a></li>
    </ul>
    <ul>
      <li>
        <?php if (!isset($_SESSION['username'])) { ?>
          <a href="login.html">Login</a>
        <?php } else { ?>
          Welcome, <?php echo $_SESSION['username']; ?>
        <?php } ?>
      </li>
      <li>
        <?php if (!isset($_SESSION['username'])) { ?>
          <a href="register.html">Register Here</a>
        <?php } else { ?>
          <a href="create-post.php">Create Post</a>
          <a href="profile.php">Profile</a>
          <a href="logout.php">Logout</a>
        <?php } ?>
      </li>
    </ul>
  </nav>


  <main>
    <section id="posts">
      <h2>Blog Posts</h2>

      <?php
      // Database connection established 
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }

      // Fetch posts from database with formatted creation date
      $sql = "SELECT *, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') AS formatted_date FROM posts ORDER BY created_at DESC";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          // Display each post with title, excerpt, creation time, and link
          echo "<article>";
          echo "<h2>" . $row['title'] . "</h2>";
          echo "<p>" . substr($row['content'], 0, 200) . "...</p>"; // Display excerpt
          echo "<p>Posted on: " . $row['formatted_date'] . "</p>";  // Display formatted date
          echo "</article>";
        }
      } else {
        echo "No posts found.";
      }

      // Close database connection
      mysqli_close($conn);
      ?>
    </section>
  </main>

  <footer>
    <p>&copy; 2024 Hooman Hoseini</p>
  </footer>

</body>
</html>
