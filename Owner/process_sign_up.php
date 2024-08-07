<?php
    date_default_timezone_set('Asia/Manila');
    session_start();
    include '../connect.php';
        if(isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {  
                if ($_POST['password'] === $_POST['confirmpassword']) {
                    $email   = $_POST['email'];
                    $cost = 12; 
                    $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID, ['cost' => $cost]);
                    $firstName  = $_POST['firstName'];
                    $lastName   = $_POST['lastName'];
                    $role       = $_POST['role'];
                    // $hireDate   = $_POST['hireDate']; // Set as NOW() if no input
                    mysqli_query($conn, "  INSERT INTO users (first_name, last_name, email, password, role) 
                                                VALUES ('$firstName', '$lastName', '$email', '$hashed_password', '$role')");

                    header("Location: role_management.php");
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