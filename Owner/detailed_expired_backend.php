<?php
	$ingredientName = $_GET['name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	// RETRIEVE STOCKOUT records in expired table
	$expiredRecords = mysqli_query($DBConnect, "	SELECT 		SUM(e.quantity) as expired, u.unitName , e.expiredDate
													FROM 		ingredient i			JOIN	expired e		ON e.ingredientID=i.ingredientID
																						JOIN    unit	u	    ON u.unitID=i.unitID
													WHERE 		DATE(e.expiredDate) 	>= '$date1' 	
													AND 		DATE(e.expiredDate) 	<= '$date2' 
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	e.expiredDate
													ORDER BY	e.expiredDate DESC;");

	$expireds = [];
	while ($expired = mysqli_fetch_array($expiredRecords)) $expireds[] = ['stock_out' => $expired['expired'], 'unit' => $expired['unitName'], 'expiredDate' => $expired['expiredDate']];
?>
	<div class="reportlabels">
		<div class="backb"><a href="detailed_report.php?results=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Stock Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed expired report for <?php echo "$ingredientName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<th>Ingredient</th>
		<th>Quantity</th>
		<th>Unit</th>
		<th>Date Time</th>

		<?php foreach ($expireds as $expired) {
			echo "<tr>";
			echo	"<td>" . $ingredientName	    	. "</td>";
			echo	"<td>" . $expired['stock_out']		. "</td>";
			echo	"<td>" . $expired['unit']			. "</td>";
			echo	"<td>" . $expired['expiredDate']	. "</td>";
			echo "</tr>";
		} ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>