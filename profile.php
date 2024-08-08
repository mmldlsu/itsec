<?php
session_start();
include 'connect.php';


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_first_name = $_POST['first_name'];
    $new_last_name = $_POST['last_name'];
    $new_email = $_POST['email'];
    $profile_image = $_FILES['profile_image']['name'];
    
    // Handle file upload
    if ($profile_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        // Check if file is uploaded
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // File uploaded successfully
            $profile_image = $target_file; // Use the full path
        } else {
            $error_message = "Error uploading file.";
            $profile_image = $user['profile_image']; // Use old image in case of error
        }
    } else {
        $profile_image = $user['profile_image']; // Keep old image if no new file
    }

    // Update user information
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, profile_image = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssss", $new_first_name, $new_last_name, $new_email, $profile_image, $email);
    
    if ($update_stmt->execute()) {
        $_SESSION['email'] = $new_email; // Update session email if changed
        $_SESSION['profile_image'] = $profile_image; // Update session profile image
        $_SESSION['first_name'] = $new_first_name;
        $_SESSION['last_name'] = $new_last_name;
        header("Location: profile.php");
        exit();
    } else {
        $error_message = "Error updating profile";
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-view {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #131827;
        }

        .profile-card {
            background-color: var(--btn-info);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            width: 100%;
            max-width: 500px;
        }

        .profile-card img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-card form {
            display: flex;
            flex-direction: column;
        }

        .profile-card form label {
            margin: 10px 0 5px;
        }

        .profile-card form input[type="text"],
        .profile-card form input[type="email"]{
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color:black;
        }
        .profile-card form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .profile-card form button {
            background-color: #131827;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .profile-card form button:hover {
            background-color: #0f1521;
        }

        .profile-card form .error {
            color: red;
            margin-top: 10px;
        }
        .img-thumbnail {
                width: 200px;
                height: 200px;
                border-radius: 50%;
                object-fit: cover;
            }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="profile-view">
        <div class="profile-card">
            <h1>Your Profile</h1>
            <div style="text-align: center;">
            <img src="<?= htmlspecialchars($_SESSION['profile_image'], ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail">
            </div>
            <form method="POST" enctype="multipart/form-data">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image">

                <button type="submit" class="update-profile-btn">Update Profile</button>
            </form>
            <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        </div>
    </div>
</body>
</html>
}