<?php
    session_start();
    include '../connect.php';
        if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {  
                if ($_POST['password'] === $_POST['confirmpassword']) {
                    $username   = $_POST['username'];
                    $hasedpass  = md5($_POST['password']);
                    $firstName  = $_POST['firstName'];
                    $lastName   = $_POST['lastName'];
                    $role       = $_POST['role'];
                    // $hireDate   = $_POST['hireDate']; // Set as NOW() if no input
                    mysqli_query($DBConnect, "  INSERT INTO user (username, password, firstName, lastName, role, hireDate) 
                                                VALUES ('$username', '$hasedpass', '$firstName', '$lastName', '$role', NOW())");

                    header("Location: role_management.php");
                }
            }
            else {
                header("Location: notification.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }    
?>