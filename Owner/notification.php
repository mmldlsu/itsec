<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <style>
        .notifications {
            flex: 1 1 auto;
            background: var(--btn-info);
            text-align: center;
            height: fit-content;
            /* Centered Text */
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            border-radius: 26px;
            border-width: 100px;
            min-width: 220px;
            min-height: 500px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .notifications ul{
            margin-bottom: 1vh;
            padding-left: 0;
            list-style:circle;
            text-align: start;
            padding-left: 3vw;
            padding-right: 2vw;
        }
        .notifications li{
            line-break: normal;
            word-wrap: break-word;
            margin-bottom: 2vh;
            text-decoration: none;

        }
        .notifications li:hover{
            background:var(--btn-primary-hover);
        }
    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php' ?>
    <main>
        <div class="notifications">
            <h2>Notifications</h2>
            <ul>
                <?php 
                    $ingredients =  mysqli_query($DBConnect, "SELECT ingredientID FROM ingredient;");
                    while ($ingredient = mysqli_fetch_assoc($ingredients)) {
                        $ingredientData = [];
                        $ingredient['data'] = mysqli_fetch_array(mysqli_query($DBConnect, " SELECT	oi.quantity * r.quantity AS data
                                                                                            FROM	order_item oi	JOIN orders o	ON o.orderID=oi.orderID
                                                                                                                    JOIN recipe r	ON r.dishID=oi.dishID
                                                                                            WHERE	r.ingredientID = " . $ingredient['ingredientID'] . "
                                                                                            AND		DATE(o.createdAt) = CURDATE() - INTERVAL 1 DAY;"));
                        
                        $ingredientData = $ingredient['data'];
                        // Minimum Quantity
                        
                        // Example usage with historical data for an ingredient (replace with actual data)
                        // $ingredientData = [10, 15, 20, 12, 18, 25];
                        if (!empty($ingredientData)) {
                            $safetyFactor = 1.5;
                            // Step 1: Calculate the average usage
                            $average = array_sum($ingredientData) / count($ingredientData);
                        
                            // Step 2: Calculate the standard deviation
                            $deviation = 0;
                            foreach ($ingredientData as $value) $deviation += pow($value - $average, 2);
                            $deviation = sqrt($deviation / count($ingredientData));
                        
                            // Step 3: Calculate the minimum qty
                            $threshold = $average + ($safetyFactor * $deviation);
                            $replenish = ($average + ($safetyFactor * 10)) - $threshold;

                            $ingredientQty = mysqli_fetch_array(mysqli_query($DBConnect, "SELECT i.ingredientName, i.quantity, u.unitName FROM ingredient i JOIN unit u ON i.unitID=u.unitID WHERE ingredientID =  " . $ingredient['ingredientID'] . ""));
                            if ($ingredientQty['quantity'] <= $threshold) echo "<li>" . $ingredientQty['ingredientName'] . " needs to be replenished by $replenish " . $ingredientQty['unitName'] . " </li>";
                            // else echo "<li>" . $ingredientQty['ingredientName'] . " = " . $ingredientQty['quantity'] . " / " . $threshold . "</li>";
                        }
                    }
                ?>
            </ul>
        </div>
    </main>
</body>
</html>
<?php
        }
    }
    else {
        header("Location: ../home.php");
        exit();
    }
?>