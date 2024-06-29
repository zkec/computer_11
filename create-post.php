<?php
session_start();

if (!isset($_SESSION['username'])) {
  // Redirect to login if not logged in
  header("Location: login.html");
  exit;
}

// Include database connection file (replace with your connection details)
require_once("connect.php");

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim(htmlspecialchars($_POST['title']));  // Sanitize and trim title
  $content = trim(htmlspecialchars($_POST['content']));  // Sanitize and trim content
  $author_id = $_SESSION['user_id'];  // Assuming user_id is stored in session

  // Basic validation (add more as needed)
  $errors = [];
  if (empty($title)) {
    $errors[] = "Title is required.";
  }
  if (strlen($content) < 10) {
    $errors[] = "Content must be at least 10 characters long.";
  }

  if (empty($errors)) {
    $sql = "INSERT INTO posts (title, content, author_id) VALUES ('$title', '$content', $author_id)";

    if (mysqli_query($conn, $sql)) {
      echo "<p>Post created successfully!</p>";
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
  } else {
    // Display validation errors
    echo "<p>Please fix the following errors:</p>";
    echo "<ul>";
    foreach ($errors as $error) {
      echo "<li>$error</li>";
    }
    echo "</ul>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hooman Hoseini - Create Post</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main>
    <section class="container">
      <h2>Create New Post</h2>

      <form action="" method="post">
        <div class="register-input">
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" required value="<?php echo isset($_POST['title']) ? $_POST['title'] : '' ?>"> </div>
        <div class="register-input">
          <label for="content">Content:</label>
          <textarea id="content" name="content" rows="10" required><?php echo isset($_POST['content']) ? $_POST['content'] : '' ?></textarea> </div>
        <button type="submit">Create Post</button>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2024 Hooman Hoseini</p>
  </footer>

</body>
</html>

