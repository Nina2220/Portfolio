<?php
if (isset($_GET['id'])) {
  $imageId = $_GET['id'];

  // Get the image file path from the database before deleting
  $sql = "SELECT image_path FROM images WHERE id='$imageId'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $imagePath = $row['image_path'];

  // Delete the image file from the server
  if (unlink($imagePath)) {
    // Delete the record from the database
    $sql = "DELETE FROM images WHERE id='$imageId'";
    mysqli_query($conn, $sql);
    echo "Image deleted successfully!";
  } else {
    echo "Error deleting image.";
  }
}
