<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        else if ($_SESSION['role'] === 'Chef' || $_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Recipe</title>
    <link rel="stylesheet" type="text/css" href="../Owner/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="processRecipe.js"></script>
</head>
<body>
    <?php @include 'navbar.php' ?>
    <div class ="chefview">
		<div id="title">
			<h2>Create a New Recipe</h2>
		</div>
        <form action="addPendingDish.php" method="POST" enctype="multipart/form-data">
            <label for="image">Select an image:</label>
            <input type="file" name="image" id="image" required />
            <br />

			<label for="recipename">Dish Name:</label>
            <input type="text" id="recipename" name="dishname" required><br><br>

            <div id="ingredientlist">
                <div class = "ingredients">
                    
					<label for="ingredientname">Ingredients:</label>
                    <input type="text" id="ingredientname" name="ingredientname[]" required />

                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quanity" name="quanity[]" style="width: 60px;" required />

                    <label>Unit:</label>
                    <select name="unit[]" class="unit" style="color: black;" required>
                    <option value="" disabled selected hidden></option>
					<?php
                        $query = mysqli_query($DBConnect, "SELECT unitID, unitName FROM unit;");

                        foreach ($query as $unit) echo '<option value="' . $unit['unitID'] . '" style="color: black;">' . $unit['unitName'] . '</option>';
                    ?>
					</select>
                    
					<button class="remove" type="button"> <span>&#x1F5D1;
                    </span> </button>
                </div>
            </div>

            <button id="addbutton" type="button">+ Add Ingredient</button><br><br>
            <input type="Submit" name="stocksubmit"  class="inputbutton" value="CONFIRM" />
            <br>
        </form>

        <div id="ingredientsHidden">
            <div class = "ingredients" hidden>
                <label for="ingredientname">Ingredients:</label>
                <input type="text" id="ingredientname" list="ingredients" name="ingredientname[]" required />

                <label for="quantity">Quantity:</label>
                <input type="text" id="quanity" name="quanity[]" style="width: 60px;" required />

                <label>Unit:</label>
                <select name="unit[]" style="color: black;">
                <option value="" disabled selected hidden></option>
                <?php
                    $query = mysqli_query($DBConnect, "SELECT unitID, unitName FROM unit;");
                    foreach ($query as $unit) echo '<option value="' . $unit['unitID'] . '" style="color: black;">' . $unit['unitName'] . '</option>';
                ?>
                
                </select>
                <button class="remove" type="button"> <span>&#x1F5D1;
                </span> </button>
            </div>
        </div>  
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