<?php
    session_start();
    include "connect.php";

    if (isset($_POST['loginBtn'])) {
        $email = mysqli_real_escape_string($DBConnect, $_POST['email']);
        $upass = mysqli_real_escape_string($DBConnect, $_POST['password']);
    
        if (empty($email)) {
            header("Location: index.php?error=Email is required");
            exit();
        }
        else if (empty($upass)) {
            header("Location: index.php?error=Password is required");
            exit();
        }

        $lowercaseEmail = strtolower(trim($email));
        $regExPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if(preg_match($regExPattern, $lowercaseEmail)){
            // Prepared statement to prevent SQL injection
            $stmt   =   $DBConnect -> prepare("SELECT employeeID, username, email, password, firstName, lastName, role FROM user WHERE email = ? LIMIT 1");
            $stmt   ->  bind_param("s", $email);
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
                    $_SESSION['email']   = $user['email'];
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
                header("Location: index.php?error=Email not found");
                exit();
            }
        } else {
            // Email was invalid
            header("Location: index.php?error=Invalid email");
            exit();
        }

    } 
    else {
        header("Location: index.php");
        exit();
    }
?>