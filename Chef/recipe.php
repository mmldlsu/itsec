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
    <style>
        .recipedetails{
            margin: 5vh 3vw 10vh 10vw;
            padding: 2vh;
            width:65%;
            box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
            border-radius: 26px;
            border-width: 100px;
            background:var(--btn-info);
            backdrop-filter: blur(10px);
            min-width: 220px;
            height: auto;
            margin: 40px auto;
            margin-top: 10vh;
        }

        .recipedetails .dishcontent{
            margin-left: 3vw;
        }

        .container {
            text-align: center;
        }

        .dish-image {
            width: 240px;
            height: 190px;
            object-fit: cover;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            margin-left: 4.5%;
        }

        th {
            padding: 10px;
            border: 1px solid #ccc;
        }

        td {
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="../Owner/style.css">
</head>
<body>
    <?php @include 'navbar.php' ?>
    <div class="recipedetails">
        <?php
            if (isset($_POST['dishID'])) {
                $dishIDs        = $_POST['dishID'];
                $dishID         = mysqli_query($DBConnect, "SELECT dishID, dishName, DATE(dateCreated) AS date, price, img  FROM dish WHERE dishID = $dishIDs AND Active = 'Yes';");
                $dish           = mysqli_fetch_assoc($dishID);
                $dishName       = $dish['dishName'];
                $dateCreated    = $dish['date'];
                $price          = $dish['price'];
                $dishImg        = $dish['img'];
                ?>
                <div class="dishcontent">
                    <?php
                echo "<h2>Recipe Details</h2>";
                echo" <hr style='height:1px;color:black;background-color:black'>";
                echo "<tr><td><img src='$dishImg' class='dish-image'></td>";
                echo "<tr><td><h3>$dishName</h3></td>";     
                echo "<tr><td>Date Created: $dateCreated</td></tr><br />";
                echo "<tr><td>Price: $price</td></tr>";
                echo "</div><br />";
                // Convert the dishIDs string into an array
                $dishIDArray = explode(",", $dishIDs);

                // Fetch the recipe details for the specified dishIDs
                $query = mysqli_query($DBConnect, "SELECT * FROM recipe WHERE dishID IN (".implode(",", $dishIDArray).")");

                // Display the recipe details
                echo "<table>";
                echo "<tr><td>Ingredient:</td><td>Quantity: </td><td>Unit: </td></tr>";
                while ($retrieve = mysqli_fetch_assoc($query)) {
                    $ingredientID = $retrieve['ingredientID'];
                    $quantity = $retrieve['quantity'];
                    
                    // Fetch the ingredient details
                    $ingredientQuery    = mysqli_query($DBConnect, "SELECT * FROM ingredient WHERE ingredientID = $ingredientID");
                    $ingredient         = mysqli_fetch_assoc($ingredientQuery);
                    $ingredientName     = $ingredient['ingredientName'];
                    $unitID             = $ingredient['unitID'];
                    $unitQuery          = mysqli_query($DBConnect, "SELECT * FROM unit WHERE unitID = $unitID");
                    $unitg              = mysqli_fetch_assoc($unitQuery);
                    $unitname           = $unitg['unitName'];
                    echo "<tr><td>$ingredientName</td><td>$quantity</td><td>$unitname</td></tr>";
                }
                echo "</table>";
            } else echo "No dish ID specified.";
        ?>
        <div class="dishcontent">
            <br/><a  href="viewRecipe.php"><button class="sbt_btn">Back</button> </a>
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