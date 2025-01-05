<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.html");
  exit();
}

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'portfolio');
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Handle logout
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: login.html");
  exit();
}

// Upload image (if form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
  $image = $_FILES['image']['name'];
  $image_tmp = $_FILES['image']['tmp_name'];

  // Set target path for the uploaded image
  $target_path = "uploads/" . basename($image);

  // Move the uploaded image to the target path
  if (move_uploaded_file($image_tmp, $target_path)) {
    // Save image information to the database
    $sql = "INSERT INTO images (image_name) VALUES ('$image')";
    if (mysqli_query($conn, $sql)) {
      echo "Image uploaded successfully!";
    } else {
      echo "Error uploading image!";
    }
  } else {
    echo "Failed to upload image!";
  }
}

// Delete image (if delete button is clicked)
if (isset($_GET['delete_id'])) {
  $image_id = $_GET['delete_id'];

  // Get the image name from the database to delete the file
  $sql = "SELECT * FROM images WHERE id=$image_id";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $image_name = $row['image_name'];

  // Delete the image from the folder and the database
  if (unlink("uploads/" . $image_name)) {
    $sql = "DELETE FROM images WHERE id=$image_id";
    if (mysqli_query($conn, $sql)) {
      echo "Image deleted successfully!";
    } else {
      echo "Error deleting image from database!";
    }
  } else {
    echo "Error deleting the image file!";
  }
}

// Fetch all images from the database
$sql = "SELECT * FROM images";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
</head>

<body>
  <!-- Welcome message -->
  <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

  <!-- Logout Form -->
  <form method="POST" style="margin-bottom: 20px;">
    <button type="submit" name="logout">Logout</button>
  </form>

  <!-- Upload Form -->
  <h2>Upload Image</h2>
  <form action="dashboard.php" method="POST" enctype="multipart/form-data">
    <label for="image">Choose image to upload:</label>
    <input type="file" name="image" id="image" required><br><br>
    <button type="submit">Upload</button>
  </form>

  <!-- Display Images and Delete Option -->
  <h2>Uploaded Images</h2>
  <table border="1">
    <tr>
      <th>Image</th>
      <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><img src="uploads/<?php echo $row['image_name']; ?>" alt="Image" width="100"></td>
        <td>
          <a href="dashboard.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>