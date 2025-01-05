<?php
// Database connection
include('database.php');

$sql = "SELECT * FROM images";  // Query to fetch all images
$result = mysqli_query($conn, $sql);

echo "<h1>Portfolio Images</h1>";
echo "<div>";

while ($row = mysqli_fetch_assoc($result)) {
  echo "<div>";
  echo "<img src='" . $row['image_path'] . "' alt='" . $row['image_name'] . "' style='width: 300px; height: 200px;'>";
  echo "<p>" . $row['image_name'] . "</p>";
  echo "<a href='delete.php?id=" . $row['id'] . "'>Delete</a>"; // Link to delete the image
  echo "</div>";
}

echo "</div>";
