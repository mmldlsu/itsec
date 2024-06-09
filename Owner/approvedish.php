<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dishes to Approve</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php @include 'navbar.php' ?>
    <div class="approvedishcss">
        <h2> Dishes to Approve </h2>
        <table class="reporttable">
            <th>Dish</th>
            <th>Type</th>
            <th>Created By</th>
            <th>Actions</th>
            <?php 
                $selectQuery = mysqli_query($DBConnect, "   SELECT nDishID, dishName, type, dateCreated, approved, img, createdBy
                                                            FROM pending_dish
                                                            WHERE approved IS NULL 
                                                            ORDER BY createdBy;");
                while($pendingDish = mysqli_fetch_array($selectQuery)) {
            ?>
                <tr>
                    <td>
                        <form action="dishdetailed.php" method="POST">
                            <input type="hidden" name="nDishID" value="<?= $pendingDish['nDishID']; ?>" />
                            <button type="submit" class="ingredname"><?= $pendingDish['dishName']; ?></button>
                        </form>
                    </td>
                    <td><?= $pendingDish['type']?></td>
                    <?php
                        $name = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT CONCAT(firstName , ' ', lastName) AS name FROM user WHERE employeeID = " . $pendingDish['createdBy'] . ";"));
                    ?>
                    <td><?= $name['name']?></td>
                    <td>
                        <form method="POST" action="dishentry.php">
                            <input type="hidden" name="nDishID" value="<?= $pendingDish['nDishID']; ?>"/>
                            <button type="submit" name="approve" class="approve">Approve</button>
                            <button name="deny" value="deny" class="deny">Deny</button>
                        </form>
                    </td>
                </tr>
            <?php
                }
            ?>
        </table>
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