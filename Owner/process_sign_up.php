<?php
    date_default_timezone_set('Asia/Manila');
    session_start();
    include '../connect.php';

    // Include the logMessage function
    include '../logfunctions.php';

    if(isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
            logMessage('INFO', 'Attempt by user ' . $_SESSION['email'] . 'Reading of Data from Database Tables Users' . $email, $usr_id, 'Account Creation', 'Attempted', $client_ip, 'readReqs.log');
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $email = $_POST['email'];
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $role = $_POST['role'];
                $usr_id = $_SESSION['id']; // Assuming admin's ID is stored in session
                $client_ip = $_SERVER['REMOTE_ADDR'];

                if ($_POST['password'] === $_POST['confirmpassword']) {
                    $cost = 12; 
                    $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID, ['cost' => $cost]);

                    // Log the attempt to create a new account
                    logMessage('INFO', 'Attempt by user ' . $_SESSION['email'] . ' to create new account for ' . $email, $usr_id, 'Account Creation', 'Attempted', $client_ip, 'account_creation.log');

                    $query = "INSERT INTO users (first_name, last_name, email, password, role) 
                              VALUES ('$firstName', '$lastName', '$email', '$hashed_password', '$role')";
                    
                    if (mysqli_query($conn, $query)) {
                        // Log successful account creation
                        logMessage('INFO', 'User ' . $_SESSION['email'] . ' successfully created new account with details: Email=' . $email . ', First Name=' . $firstName . ', Last Name=' . $lastName . ', Role=' . $role, $usr_id, 'Account Creation', 'Success', $client_ip, 'account_creation.log');
                        logMessage('INFO', 'User ' . $_SESSION['email'] . 'Writing/Adding of Data on Database Tables Users (Appending)'  . ' successfully created new account with details: Email=' . $email . ', First Name=' . $firstName . ', Last Name=' . $lastName . ', Role=' . $role, $usr_id, 'Account Creation', 'Success', $client_ip, 'writeReqs.log');
                        header("Location: role_management.php");
                    } else {
                        // Log error if account creation failed
                        logMessage('ERROR', 'User ' . $_SESSION['email'] . ' Failed to create new account for ' . $email . '. MySQL error: ' . mysqli_error($conn), $usr_id, 'Account Creation', 'Failure', $client_ip, 'account_creation.log');

                        header("Location: adminhome.php?error=Account creation failed");
                    }
                } else {
                    // Log password mismatch error
                    logMessage('ERROR', 'User ' . $_SESSION['email'] . ' Password mismatch for new account creation attempt for  ' .$email, $usr_id, 'Account Creation', 'Failure - Password Mismatch', $client_ip, 'account_creation.log');

                    header("Location: adminhome.php?error=Passwords do not match");
                }
            }
            else {
                header("Location: adminhome.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../home.php");
        exit();
    }    
?>
