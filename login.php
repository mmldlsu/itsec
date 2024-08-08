<?php
date_default_timezone_set('Asia/Manila');
session_start();
include "connect.php";
include "logfunctions.php";
include 'config.php';

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
    $stmt = $conn->prepare("SELECT failed_attempts, last_attempt, locked_until FROM failed_logins WHERE email = ? ORDER BY last_attempt DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $failedLogin = $result->fetch_assoc();

    if ($failedLogin) {
        $currentTimestamp = time();
        $lockedUntilTimestamp = strtotime($failedLogin['locked_until']);
        $lastAttemptTimestamp = strtotime($failedLogin['last_attempt']);
        
        // Check lockout status
        if ($currentTimestamp < $lockedUntilTimestamp) {
            $lockoutMinutes = ceil(($lockedUntilTimestamp - $currentTimestamp) / 60);
            header("Location: index.php?error=Account locked. Try again in $lockoutMinutes minutes.");
            exit();
        }

        // Reset failed attempts if the last attempt was more than 5 minutes ago
        if ($currentTimestamp - $lastAttemptTimestamp > 300) {
            $failedLogin['failed_attempts'] = 0;
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
            $_SESSION['email']   = $user['email'];
            $_SESSION['first_name']  = $user['first_name'];
            $_SESSION['last_name']   = $user['last_name'];
            $_SESSION['profile_image']   = $user['profile_image'];
            $_SESSION['role']   = $user['role'];

            logMessage(
                'INFO', 
                'Authorization successful for user: ' . $email, 
                $userId, 
                'authentication', 
                'success', 
                $_SERVER['REMOTE_ADDR']
            );
            

            // Redirect to the home page after successful login
            if($_SESSION['email'] == "admin@gmail.com"){
                header("Location: adminhome.php");
            }
            else{
                header("Location: home.php");
            }
        } else {
            // Incorrect password
            recordFailedLogin($conn, $email, $failedLogin['failed_attempts'] + 1);
            logMessage(
                'WARNING', 
                'Authorization failed for user: ' . $email, 
                $userId, 
                'authentication', 
                'fail', 
                $_SERVER['REMOTE_ADDR']
            );
            
            header("Location: index.php?error=Invalid Credentials");
            exit();
        }
    } else {
        // User not found
        logMessage(
            'WARNING', 
            'Authorization attempt for non-existent user: ' . $email, 
            $userId, 
            'authentication', 
            'fail', 
            $_SERVER['REMOTE_ADDR']
        );
        header("Location: index.php?error=Invalid Credentials");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

function recordFailedLogin($conn, $email, $failedAttempts) {
    $currentTimestamp = date('Y-m-d H:i:s'); // Get the current timestamp

    // Retrieve the last attempt timestamp
    $stmt = $conn->prepare("SELECT last_attempt FROM failed_logins WHERE email = ? ORDER BY last_attempt DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $lastLogin = $result->fetch_assoc();
    $lastAttemptTimestamp = isset($lastLogin) ? strtotime($lastLogin['last_attempt']) : 0;

    // Check if the last attempt was more than 5 minutes ago
    if (time() - $lastAttemptTimestamp > 300) {
        // Insert a new row if the last attempt was more than 5 minutes ago
        $stmt = $conn->prepare("INSERT INTO failed_logins (email, failed_attempts, last_attempt, locked_until) VALUES (?, 1, ?, NULL)");
        $stmt->bind_param("ss", $email, $currentTimestamp);
    } else {
        // Update the existing row
        $lockoutDuration = 0;
        if ($failedAttempts >= 5) {
            $lockoutDuration = 15; // Lockout duration in minutes
            $lockedUntil = date("Y-m-d H:i:s", strtotime("+$lockoutDuration minutes"));
            // Find the most recent record for update
            $stmt = $conn->prepare("UPDATE failed_logins SET failed_attempts = ?, locked_until = ?, last_attempt = ? WHERE email = ? AND last_attempt = ?");
            $stmt->bind_param("issss", $failedAttempts, $lockedUntil, $currentTimestamp, $email, $lastLogin['last_attempt']);
        } else {
            // Find the most recent record for update
            $stmt = $conn->prepare("UPDATE failed_logins SET failed_attempts = ?, last_attempt = ? WHERE email = ? AND last_attempt = ?");
            $stmt->bind_param("ssss", $failedAttempts, $currentTimestamp, $email, $lastLogin['last_attempt']);
        }
    }
    $stmt->execute();
}


?>
