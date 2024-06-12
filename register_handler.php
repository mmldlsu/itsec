<?php

// Include database connection details
require_once("connect.php");

// Check if form is submitted
if (isset($_POST['registerBtn'])) {
  $firstName = mysqli_real_escape_string($conn, $_POST['firstname']);
  $lastName = mysqli_real_escape_string($conn, $_POST['lastname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $cost = 12; // Adjust cost as needed (higher = slower but more secure) FOR PASSWORD HASH
  $hashed_password = password_hash($password, PASSWORD_ARGON2ID, ['cost' => $cost]);

  // Handle profile picture upload (if applicable)
  $profilePicture = "";
  if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    $targetDir = "uploads/"; // Adjust upload directory path
    $fileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));

    // Check if image file is a real image
    $imageType = exif_imagetype($_FILES['profile_picture']['tmp_name']);
    $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];

    if (in_array($imageType, $allowedTypes)) {
      // Create a unique file name
      $uniqueName = uniqid('', true) . '.' . $fileType;
      $targetFile = $targetDir . $uniqueName;

      // Proceed with uploading the image
      if (file_exists($targetFile)) {
        $error = "Sorry, file already exists.";  // Set error message
      } else {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
          $profilePicture = $targetFile;
        } else {
          $error = "Sorry, there was an error uploading your file.";  // Set error message
        }
      }
    } else {
      $error = "Only JPG, JPEG & PNG files are allowed.";  // Set error message
    }
  }

  // Prepare and execute SQL statement (check for errors)
  if (!isset($error)) { // If no errors during upload or validation
    $sql = "INSERT INTO users (first_name, last_name, email, password, profile_image)
            VALUES ('$firstName', '$lastName','$email', '$hashed_password', '$profilePicture')";
    if ($conn->query($sql) === TRUE) {
      // Registration successful, redirect or display confirmation message
      echo "Registration Successful!"; // Success message displayed in console
      header("Location: index.php"); // Redirect to index.php
      exit();
    } else {
      // Registration failed, display error message
      $error = "Error: " . $sql . "<br>" . $conn->error;
      header("Location: register.php?error=" . urlencode($error)); // Redirect to register.php with error message
      exit();
    }
  } else {
    // Display any errors encountered during upload or validation
    header("Location: register.php?error=" . urlencode($error)); // Redirect to register.php with error message
    exit();
  }
}

?>
