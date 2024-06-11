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
    $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
    $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    // Validate image file type
    if(isset($_POST["profile_picture"]) && !empty($_FILES["profile_picture"]["tmp_name"])) {
      $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        // Proceed with uploading the image
      } else {
        echo "File is not an image.";
        $error = "Only image files are allowed.";  // Set error message
      }
    }

    // Check if image file is a real image
    if (isset($error)) {
      // Display error message (if any)
    } else {
      // Check if file already exists (optional)
      if (file_exists($targetFile)) {
        $error = "Sorry, file already exists.";  // Set error message
      } else {
        // Allow certain file formats
        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
        && $fileType != "gif" ) {
          $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  // Set error message
        } else {
          // Upload the image if no errors
          if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profilePicture = $targetDir . basename( $_FILES["profile_picture"]["name"]);
          } else {
            $error = "Sorry, there was an error uploading your file.";  // Set error message
          }
        }
      }
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
    } else {
      // Registration failed, display error message
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } else {
    // Display any errors encountered during upload or validation
    echo $error;
  }
}

// **Important Note:** Consider adding closing the database connection after processing 
// (optional depending on your application structure). You can use `mysqli_close($conn);`

?>
