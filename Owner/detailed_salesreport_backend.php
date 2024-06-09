<?php
	$dishName = $_GET['results'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	
    $sql = "
        SELECT 	d.dishID, d.dishName, oi.quantity AS dishes_sold, SUM( d.price * oi.quantity) as total_sales, DATE(o.createdAt) as date
        FROM	dish d			JOIN order_item oi		ON oi.dishID=d.dishID
                                JOIN orders		o 		ON o.orderID=oi.orderID

        WHERE 	 DATE(o.createdAt) >= '$date1'
        AND 	 DATE(o.createdAt) <= '$date2'
        AND		 d.dishName='$dishName'	
        AND		 d.Active ='Yes'
        GROUP BY o.createdAt
        ORDER BY o.createdAt DESC;";

    $records = mysqli_query($DBConnect, $sql) or die(mysqli_error($DBConnect));

	
?>
	<div class="reportlabels">
		<div class="backb"><a href="generatesalesreports.php?date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Sales Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed sales report for <?php echo "$dishName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<th>Dish</th>
        <th>Total Dishes Sold</th>
		<th>Total Sales</th>
        <th>Date</th>

		<?php 
            while($salesResults = mysqli_fetch_array($records))
            {
			    echo "<tr>";
                echo "<td >".$salesResults['dishName']."</td>";
                echo "<td >".$salesResults['dishes_sold']."</td>";
                echo "<td >".$salesResults['total_sales']."</td>";
                echo "<td >".$salesResults['date']."</td>";

		    } 
        ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>