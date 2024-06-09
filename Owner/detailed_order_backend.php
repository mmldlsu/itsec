<?php
	$ingredientName = $_GET['name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	// RETRIEVE STOCKOUT records in orders and order_item table
	$orderRecords = mysqli_query($DBConnect, "	SELECT 		d.dishName, SUM(oi.quantity * r.quantity) as stock_out, u.unitName, o.createdAt
												FROM 		ingredient i	JOIN recipe 		r	ON r.ingredientID=i.ingredientID
																			JOIN dish			d	ON d.dishID=r.dishID
																			JOIN order_item		oi	ON oi.dishID=d.dishID
																			JOIN orders			o	ON o.orderID=oi.orderID
																			JOIN unit			u	ON u.unitID=i.unitID
												WHERE   	DATE(o.createdAt) >= '$date1' 
												AND     	DATE(o.createdAt) <= '$date2'
												AND    		i.ingredientName='$ingredientName'
												GROUP BY    o.createdAt
												ORDER BY	o.createdAt DESC;");

	$orders = [];
	while ($order = mysqli_fetch_array($orderRecords)) $orders[] = ['dishName' => $order['dishName'],'stock_out' => $order['stock_out'], 'unit' => $order['unitName'] ,'createdAt' => $order['createdAt']];

?>
	<div class="reportlabels">
		<div class="backb"><a href="detailed_report.php?results=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Stock Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed order report for <?php echo "$ingredientName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<th>Dish Name</th>
		<th>Quantity</th>
		<th>Unit</th>
		<th>Date Time</th>

		<?php
			$counter = 0;
			$temp = 0;
		?>

		<?php foreach ($orders as $order) {
			echo "<tr>";
			echo	"<td>" . $order['dishName']	    . "</td>";
			echo	"<td>" . $order['stock_out']	. "</td>";
			echo	"<td>" . $order['unit']		. "</td>";
			echo	"<td>" . $order['createdAt']			. "</td>";
			echo "</tr>";
		} ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>