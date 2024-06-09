<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Inventory' || $_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") { 
                $ingredientID   = $_POST['ingredient'];
                $qty            = $_POST['manual'];
                $id             = $_SESSION['id'];

                for($i=0; $i < count($qty); $i++ ){
                    if (inputValidation($qty[$i])) {
                        $insertQuery = mysqli_query($DBConnect, "   INSERT INTO disparity(ingredientID, sQuantity, mQuantity, createdAt, createdBy) VALUES (
                                                                    " . $ingredientID[$i] . ", 
                                                                    (SELECT quantity 
                                                                    FROM ingredient 
                                                                    WHERE ingredientID = " . $ingredientID[$i] . "), 
                                                                    " . $qty[$i] . ", 
                                                                    NOW(), 
                                                                    $id);");
        
                        $updateQuery = mysqli_query($DBConnect, "   UPDATE ingredient 
                                                                    SET quantity = " . $qty[$i] . "
                                                                    WHERE ingredientID = '" . $ingredientID[$i] . "';");
                    }
                }
                header("Location: manstockcount.php");
                exit;
            }
            else {
                header("Location:  manstockcount.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }
?>