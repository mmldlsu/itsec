<?php 
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Chef' || $_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {   
                $dishName       = $_POST['dishname'];
                $ingredient     = $_POST['ingredientname'];
                $qty            = $_POST['quanity'];
                $unit           = $_POST['unit'];

                $hasDish = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT dishID FROM dish WHERE dishName = '$dishName' AND Active = 'Yes';"));

                if (($hasDish) == 0) {
                    include 'upload.php';
                    for($i = 0; $i < count($qty); $i++) {
                        if (!inputValidation($qty[$i], $ingredient[$i], $unit[$i])) {
                            header("Location: addDish.php");
                            exit();
                        }
                    }
                    $id = $_SESSION['id'];
                    mysqli_query($DBConnect, "  INSERT INTO Pending_Dish(dishName, type, dateCreated, createdBy, img) 
                                                VALUES ('$dishName', 'New', NOW(), $id, '$filePathInDatabase');");

                    $ndishQuery = mysqli_query($DBConnect, "SELECT ndishID FROM Pending_Dish ORDER BY dateCreated DESC LIMIT 1;");
                    $ndishID = mysqli_fetch_array($ndishQuery)['ndishID'];

                    for($i = 0; $i < count($qty); $i++) {
                        $newQty = $qty[$i];
                        // Conversion
                        $defaultUnit = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT unitID FROM unit WHERE type IN (SELECT type FROM unit WHERE unitID = $unit[$i]) AND conversion = 1;"));
                        $conversion = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT conversion FROM unit WHERE unitID = $unit[$i];"))[0];

                        $newQty = round(floatval($qty) * floatval($conversion), 2);

                        $ingredientQuery = mysqli_query($DBConnect, "SELECT ingredientID FROM Ingredient WHERE ingredientName = '$ingredient[$i]' LIMIT 1;");
                        $ingredientID = mysqli_fetch_array($ingredientQuery)['ingredientID'];

                        mysqli_query($DBConnect, "INSERT INTO Pending_Recipe(nDishID, ingredientID, quantity) 
                        VALUES ($ndishID, $ingredientID, $newQty);");
                    }
                    header("Location: viewRecipe.php");
                    exit();
                }
                else {
                    header("Location: addDish.php");
                    exit();
                }
            }
            else {
                header("Location: viewRecipe.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }
?>