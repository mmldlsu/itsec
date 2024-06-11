<?php
session_start();
include "connect.php";

if (isset($_POST['loginBtn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $upass = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($email)) {
        header("Location: index.php?error=Email is required");
        exit();
    } else if (empty($upass)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    $lowercaseEmail = strtolower(trim($email));
    $regExPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    if (!preg_match($regExPattern, $lowercaseEmail)) {
        header("Location: index.php?error=Invalid email");
        exit();
    }

    // Check if the user is locked out
    $stmt = $conn->prepare("SELECT failed_attempts, locked_until FROM failed_logins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $failedLogin = $result->fetch_assoc();

    if ($failedLogin) {
        $currentTimestamp = time();
        $lockedUntilTimestamp = strtotime($failedLogin['locked_until']);
        if ($currentTimestamp < $lockedUntilTimestamp) {
            $lockoutMinutes = ceil(($lockedUntilTimestamp - $currentTimestamp) / 60);
            header("Location: index.php?error=Account locked. Try again in $lockoutMinutes minutes.");
            exit();
        }
    }

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User found, check password
        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];

        if (password_verify($upass, $hashedPassword)) {
            // Authentication successful
            session_unset();
            session_start();
            $_SESSION['id']   = $user['user_id'];
            // $_SESSION['username']   = $user['username'];
             $_SESSION['email']   = $user['email'];
             $_SESSION['full_name']  = $user['full_name'];
           //  $_SESSION['firstname']  = $user['firstName'];
           //  $_SESSION['lastname']   = $user['lastName'];
            // $_SESSION['role']       = $user['role'];
            $_SESSION['profile_image']   = $user['profile_image'];

            // Reset failed login attempts
            $stmt = $conn->prepare("DELETE FROM failed_logins WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // Redirect to the home page after successful login
            header("Location: home.php");

            /*
            switch ($_SESSION['role']) {
                case "Admin":
                    header("Location: Owner/notification.php"); // Subject to change
                    break;
                case "Inventory":
                    header("Location: Controller/manstockcount.php"); // Subject to change
                    break;
                case "Chef":
                    header("Location: Chef/viewRecipe.php"); // Subject to change
                    break;
                case "Cashier":
                    header("Location: Cashier/cashier.php");
                    break;
            } */
        } else {
            // Incorrect password
            recordFailedLogin($conn, $email);
            header("Location: index.php?error=Invalid password");
            exit();
        }
    } else {
        // User not found
        header("Location: index.php?error=Email not found");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

function recordFailedLogin($conn, $email) {
    $stmt = $conn->prepare("SELECT failed_attempts FROM failed_logins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $failedLogin = $result->fetch_assoc();

    if ($failedLogin) {
        $failedAttempts = $failedLogin['failed_attempts'] + 1;
        if ($failedAttempts >= 5) {
            $lockoutDuration = 15; // Lockout duration in minutes
            $lockedUntil = date("Y-m-d H:i:s", strtotime("+$lockoutDuration minutes"));
            $stmt = $conn->prepare("UPDATE failed_logins SET failed_attempts = ?, locked_until = ? WHERE email = ?");
            $stmt->bind_param("iss", $failedAttempts, $lockedUntil, $email);
        } else {
            $stmt = $conn->prepare("UPDATE failed_logins SET failed_attempts = ? WHERE email = ?");
            $stmt->bind_param("is", $failedAttempts, $email);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO failed_logins (email, failed_attempts) VALUES (?, 1)");
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
}
?>
