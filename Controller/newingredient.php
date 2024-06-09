<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Inventory' || $_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Ingredient</title>
    <link rel="stylesheet" type="text/css" href="../Owner/style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
<?php @include 'navbar.php' ?>
<div class="stockpurchcard">
    <h2>Add New Ingredient</h2>
    <form action="addNewIngredient.php" method="POST">
        <ul>
            <li>
                <label>Ingredient Name:</label>
                <input type="text" name="ingredientName" class="inputarea" placeholder="Input Ingredient">
            </li>
            <li>
                <label>Ingredient Type:</label>
                <select name='unitType'>
                    <option value='Mass' style="color: black;">Mass</option>
                    <option value='Volume' style="color: black;">Volume</option>
                    <option value='Count' style="color: black;">Count</option>
				</select>
            </li>
        </ul>
        <input type="Submit" name="stocksubmit"  class="inputbutton" value="Submit Ingredient"><br>
    </form>
</div>
</body>
</html>
<?php
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }
?>