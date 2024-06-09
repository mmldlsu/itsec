<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Inventory' || $_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") { 
                $newUnit       = $_POST['unitName'];
                $unitName        = $_POST['unit'];
                $conversion     = $_POST['conversion'];

                mysqli_query($DBConnect, "  INSERT INTO unit(unitName, type, conversion) 
                                            SELECT      '$newUnit', type, ($conversion * conversion) AS conversion
                                            FROM        unit 
                                            WHERE       unitID  = '$unitName';");
                        
                header("Location: manstockcount.php");
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