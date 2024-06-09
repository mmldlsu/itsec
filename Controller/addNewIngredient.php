<?php 
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Inventory' || $_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") { 
                $ingredientName  = $_POST['ingredientName'];
                $unitType        = $_POST['unitType'];

                mysqli_query($DBConnect, "  INSERT INTO ingredient(ingredientName, quantity, unitID) 
                                            SELECT      '$ingredientName', 0, unitID
                                            FROM        unit 
                                            WHERE       type  = '$unitType' AND conversion = 1;");
                        
                header("Location: newIngredient.php");
                exit;
            }
            else {
                header("Location: manstockcount.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }
?>