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
    <link rel="stylesheet" type="text/css" href="../Owner/style.css">
</head>
<body>
    <?php include 'navbar.php' ?>
    <?php
        $query = mysqli_query($DBConnect, "SELECT * FROM dish WHERE active = 'Yes' ");
        $count = 1;
        echo'<div class="recipe">';
        echo'<h2>View Recipe</h2>';

        while ($retrieve = mysqli_fetch_assoc($query)) {
            $dishID = $retrieve['dishID'];
            $dishName = $retrieve['dishName'];
            $dateCreated =  $retrieve['dateCreated'];
            $price =    $retrieve['price'];
            $dishImg = $retrieve['img'];
            
            
            if ($count%3==1||$count==1){
                echo "<div class = 'row'>";
            }
            
            echo "<div class='column'><h3>$dishName</h3>";  
            echo "<form action='recipe.php' method='POST'>";
            echo "<img src='$dishImg'  class='dish-image' id='myBtn$dishID' onclick='this.parentNode.submit();'>";
            echo "<input type='hidden' name='dishID' value='$dishID'>";
            echo "</form>";
            echo "</div>";
            $count++;
            if ($count%3==1){
                echo "</div>";  
            }
        }
        echo "</tr>";
        echo "</div>";
        ?>
        <style>
            .recipe{
                margin: 5vh 3vw 10vh 10vw;
                padding: 2vh;
                width:85%;
                box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
                border-radius: 26px;
                border-width: 100px;
                background:var(--btn-info);
                backdrop-filter: blur(10px);
                min-width: 220px;
                height: fit-content;
            }
            
            .recipe h2{
                text-align: center;
            }
            
            .recipe .row{
                display: flex;
            }
            
            .recipe .column{
                flex: 33.33%;
                padding: 10px;
                text-align: center;
            }
            
            .column h3{
                text-align: center;
            }
            
            table {
                border-collapse: collapse;
                margin-left: auto;
                margin-right: auto;
            }
            
            th {
                padding: 10px;
                border: 1px solid #ccc;
            }
            
            td {
                padding: 10px;
                border: 1px solid #ccc;
            }
            
            .dish-image {
                width: 240px;
                height: 190px;
                object-fit: cover;
                cursor: pointer;
            }
            
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .image-gallery {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .image-gallery img {
                margin: 10px;
                cursor: pointer;
            }
        </style>
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