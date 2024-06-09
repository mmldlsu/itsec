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
    <title>Manual Count</title>
    <link rel="stylesheet" type="text/css" href="../Owner/style.css">
    <style>
        /* Add the styles for the submit button here */
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            position: absolute;
            top: 70px;
            right: 100px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="stockview">
        <h1>Manual Count</h1>
        <div class="content">
            <table>
                <tr>
                    <th scope="col">Ingredient</th>
                    <th scope="col">Manual Count</th>
                    <th scope="col">Measurement</th>
                </tr>
                <tbody>
                    <form action="manualCount.php" method="POST">
                        <?php 
                            $query = mysqli_query($DBConnect, "SELECT i.ingredientName, i.ingredientID, u.unitName, u.unitID FROM ingredient i JOIN unit u ON i.unitID=u.unitID ORDER BY i.ingredientName;");
                            while($retrieve = mysqli_fetch_array($query)) {
                                $manual = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT mQuantity FROM disparity WHERE ingredientID = " . $retrieve['ingredientID'] . " ORDER BY createdAt DESC ;"));
                        ?>
                            <tr>
                                <td><?= $retrieve['ingredientName']?></td>
                                <td><input id="<?=$retrieve['ingredientID'] ?>" name="manual[]" type="text" value="<?=$manual['mQuantity'] ?? NULL ?>" style="color: black;"/></td>
                                <td><?= $retrieve['unitName']?></td>
                                <input type="hidden" name="ingredient[]" value="<?=$retrieve['ingredientID'] ?>" />
                            </tr>
                            <?php } ?>
                        <button type="submit">Submit Count</button>
                    </form>
                </tbody>
            </table>
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
