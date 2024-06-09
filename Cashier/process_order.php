<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        if ($_SESSION['role']  === 'Chef') header("Location: ../Chef/addDish.php");
        else if ($_SESSION['role'] === 'Cashier' || $_SESSION['role'] === 'Admin') {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $dishName   = $_POST['vDish'];
                $qty        = $_POST['vQuantity'];
                $totalPrice = 0;

                for ($i = 0; $i < count($qty); $i++) {
                    $priceQuery = mysqli_query($DBConnect, "SELECT price FROM dish WHERE dishName = '$dishName[$i]';");

                    $dish = mysqli_fetch_array($priceQuery);
                    $prices[$i] = $dish["price"];
                    $totalPrice += $dish["price"] * $qty[$i];
                }
                $id = $_SESSION['id'];

                $orderQuery = mysqli_query($DBConnect, "INSERT INTO orders (total, createdBy, createdAt) VALUES ($totalPrice, $id, NOW());");
            
                for ($i = 0; $i < count($qty); $i++) {
                    // Insert entries to Order and Order_Item tables
                    $orderItemQuery = mysqli_query($DBConnect, "INSERT INTO order_item (orderID, dishID, quantity) VALUES ((SELECT orderID FROM orders ORDER BY createdAt DESC LIMIT 1), (SELECT dishID FROM dish WHERE dishName = '$dishName[$i]'), $qty[$i]);");

                    // Gets the array list of ingredients using ingredientID
                    $ingredientsQuery = mysqli_query($DBConnect, "SELECT i.ingredientID FROM recipe r JOIN dish d ON d.dishID = r.dishID JOIN ingredient i ON r.ingredientID = i.ingredientID WHERE d.dishName = '$dishName[$i]';");

                    // Loop to subtract all the ingredients found on the dish to Inventory base from Recipe table
                    foreach ($ingredientsQuery as $ingredient) {
                        $ingredientQtyQuery = mysqli_query($DBConnect, "SELECT i.quantity FROM recipe r JOIN dish d ON d.dishID = r.dishID JOIN ingredient i ON r.ingredientID = i.ingredientID WHERE i.ingredientID = " . $ingredient['ingredientID']);
                        $ingredientQty = mysqli_fetch_array($ingredientQtyQuery)['quantity'];

                        $subtractQtyQuery = mysqli_query($DBConnect, "SELECT r.quantity FROM recipe r JOIN dish d ON d.dishID = r.dishID JOIN ingredient i ON r.ingredientID = i.ingredientID WHERE i.ingredientID = " . $ingredient['ingredientID']);
                        $subtractQty = mysqli_fetch_array($subtractQtyQuery)['quantity'];

                        $updateQtyQuery =  mysqli_query($DBConnect, "UPDATE ingredient SET quantity = ( $ingredientQty - ($subtractQty * $qty[$i]) ) WHERE ingredientID = " . $ingredient['ingredientID']);
                    }
                } ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Receipt</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #222d4b;
                    }

                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        margin-top: 15vh;
                        background-color: #fff;
                        border: 1px solid #ccc;
                        padding: 20px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    }

                    .header {
                        background-color: #3a4f8a;
                        color: #fff;
                        text-align: center;
                        padding: 10px;
                        font-size: 24px;
                    }

                    .order-details {
                        margin-top: 20px;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        border: none; /* Remove table border */
                    }

                    th, td{
                        border: none; /* Remove cell borders */
                        padding: 8px;
                        text-align: left;
                    }

                    .item-name {
                        font-weight: bold;
                    }

                    .item-quantity,
                    .item-total {
                        font-weight: bold;
                    }

                    .total {
                        font-size: 18px;
                        font-weight: bold;
                        text-align: center;
                        margin-top: 30px;
                    }

                    .footer {
                        margin-top: 30px;
                        text-align: center;
                        color: #777;
                        font-size: 14px;
                    }

                    .footer button {
                        background-color: #3a4f8a;
                        color: #fff;
                        border: none;
                        padding: 10px 20px;
                        cursor: pointer;
                        border-radius: 4px;
                    }

                    .footer button:hover {
                        background-color: #135ba4;
                    }
                </style>
                </head>

                <body>
                <div class="container">
                    <div class="header">Receipt</div>
                    <div class="order-details">
                        <table>
                            <tr>
                                <th>Ingredient Name</th>
                                <th>Quantity</th>
                                <th>Price Each</th>
                                <th>Price</th>
                            </tr>
                            <?php for ($i = 0; $i < count($qty); $i++) {?>
                            <tr>
                                <td><span class="item-name"><?= $dishName[$i] ?></span></td>
                                <td><span class="item-quantity">x<?= $qty[$i] ?></span></td>
                                <td><span>Php <?= $prices[$i] ?></span></td>
                                <td><span class="item-total">Php <?= $qty[$i] * $prices[$i] ?></span></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="total">Total: Php <?= $totalPrice ?></div>
                    <div class="footer">
                        <a href="cashier.php"><button>Add Transaction</button></a>
                    </div>
                </div>
                </body>
                </html>
                <?php       }
            else {
                header("Location: cashier.php");
                exit();
            }
        }
    }
    else {
        header("Location: ../index.php");
        exit();
    }
?>