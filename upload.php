<form action="upload.php" method="POST" enctype="multipart/form-data">
  <input type="file" name="image" required><br><br>
  <textarea name="description" placeholder="Enter image description" required></textarea><br><br>
  <button type="submit" name="submit">Upload Image</button>
</form>

</html>

<?php
include('database.php'); // Include the database connection file

if (isset($_POST['submit'])) {
  $image = $_FILES['image'];
  $description = $_POST['description'];

  // Get image file details
  $imageName = $image['name'];
  $imageTmpName = $image['tmp_name'];
  $imageSize = $image['size'];
  $imageError = $image['error'];

  // Generate a unique file name
  $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
  $imageNewName = uniqid('', true) . '.' . $imageExt;
  $imageUploadPath = 'uploads/' . $imageNewName;

  // Check if file is an image (you can add more validations here)
  $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
  if (in_array($imageExt, $allowedTypes)) {
    // Move the file to the server
    if (move_uploaded_file($imageTmpName, $imageUploadPath)) {
      // Insert image details into the database
      $sql = "INSERT INTO images (image_path, description) VALUES ('$imageUploadPath', '$description')";
      if (mysqli_query($conn, $sql)) {
        echo "Image uploaded successfully!";
      } else {
        echo "Error uploading image.";
      }
    } else {
      echo "Error moving uploaded file.";
    }
  } else {
    echo "Invalid image type!";
  }
}
