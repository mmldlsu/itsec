<?php
    session_start();
    include "connect.php";

    if (isset($_POST['loginBtn'])) {
        $uname = mysqli_real_escape_string($DBConnect, $_POST['username']);
        $upass = mysqli_real_escape_string($DBConnect, $_POST['password']);
    
        if (empty($uname)) {
            header("Location: index.php?error=User Name is required");
            exit();
        }
        else if (empty($upass)) {
            header("Location: index.php?error=Password is required");
            exit();
        }

        // Prepared statement to prevent SQL injection
        $stmt   =   $DBConnect -> prepare("SELECT employeeID, username, password, firstName, lastName, role FROM user WHERE username = ? LIMIT 1");
        $stmt   ->  bind_param("s", $uname);
        $stmt   ->  execute();
        $result =   $stmt -> get_result();

        if ($result -> num_rows === 1) {
            // User found, check password
            $user = $result -> fetch_assoc();
            $hashedPassword = $user['password'];
        
            if ($hashedPassword === md5($upass)) {
                // Authentication successful
                session_unset();
                session_start();
                $_SESSION['id']   = $user['employeeID'];
                $_SESSION['username']   = $user['username'];
                $_SESSION['firstname']  = $user['firstName'];
                $_SESSION['lastname']   = $user['lastName'];
                $_SESSION['role']       = $user['role'];
                
                // header("Location: test.php");
                // Redirect to the home pages after successful login
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
                }
            } else {
                // Incorrect password
                header("Location: index.php?error=Invalid password");
                exit();
            }
        } else {
            // User not found
            header("Location: index.php?error=Invalid username");
            exit();
        }
    } 
    else {
        header("Location: index.php");
        exit();
    }
?>