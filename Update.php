<form action="update.php" method="POST">
  <input type="text" name="title" placeholder="Title" value="Current Title">
  <textarea name="description" placeholder="Description">Current Description</textarea>
  <input type="hidden" name="image_id" value="Image ID">
  <input type="submit" value="Update Image">
</form>
<?php
if (isset($_POST['submit'])) {
  $imageId = $_POST['image_id'];
  $title = $_POST['title'];
  $description = $_POST['description'];

  // Update the image details in the database
  $sql = "UPDATE images SET title='$title', description='$description' WHERE id='$imageId'";
  mysqli_query($conn, $sql);
  echo "Image updated successfully!";
}
?>