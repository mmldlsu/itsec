<?php
    session_start();
    include '../connect.php';
        if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {  
                $nDishID = $_POST['nDishID'];
                $price = $_POST['price'];

                $pendingDish = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT dishName, img FROM pending_dish WHERE nDishID = $nDishID;"));
                $hasDish = mysqli_query($DBConnect, "SELECT dishID FROM dish WHERE dishName = '" . $pendingDish['dishName'] . "' AND Active = 'Yes';");
                if (mysqli_num_rows($hasDish) != 0) {
                    mysqli_query($DBConnect, "  UPDATE dish SET Active = 'No' 
                                                WHERE dishName = '".$pendingDish['dishName']."';");
                    $oldDish = mysqli_fetch_array($hasDish)['dishID'];
                }

                mysqli_query($DBConnect, "UPDATE pending_dish SET approved = 'Yes' WHERE nDishID = $nDishID;");
                mysqli_query($DBConnect, "INSERT INTO dish(dishName, dateCreated, price, img) VALUES('" . $pendingDish['dishName'] . "', NOW(), $price, '" . $pendingDish['img'] . "');");

                $approvedDish = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT dishID FROM dish WHERE dishName = '" . $pendingDish['dishName'] . "' AND Active = 'Yes';"))[0];

                mysqli_query($DBConnect, "  INSERT INTO recipe(dishID, ingredientID, quantity)
                                            SELECT $approvedDish, ingredientID, quantity 
                                            FROM pending_recipe
                                            WHERE nDishID = $nDishID;");
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