<?php
	$ingredientName = $_GET['name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	// RETRIEVE STOCKIN records in replenish table
	$replenishRecords = mysqli_query($DBConnect, "	SELECT 		SUM(re.quantity) as stock_in, u.unitName , re.boughtDate
													FROM 		ingredient i			JOIN	replenish   re		ON re.ingredientID=i.ingredientID
																						JOIN    unit	    u	    ON u.unitID=i.unitID
													WHERE 		DATE(re.boughtDate) 	>= '$date1' 	
													AND 		DATE(re.boughtDate) 	<= '$date2' 
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	re.boughtDate
													ORDER BY	re.boughtDate DESC;");

	$replenishes = [];	
	while ($replenish = mysqli_fetch_array($replenishRecords)) $replenishes[] = ['stock_in' => $replenish['stock_in'], 'unit' => $replenish['unitName'] ,'boughtDate' => $replenish['boughtDate']];
?>
	<div class="reportlabels">
		<div class="backb"><a href="detailed_report.php?results=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Stock Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed Stock In Report for <?php echo "$ingredientName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<th>Ingredient</th>
		<th>Quantity</th>
		<th>Unit</th>
		<th>Date Time</th>

		<?php foreach ($replenishes as $replenish) {
			echo "<tr>";
			echo	"<td>" . $ingredientName	    . "</td>";
			echo	"<td>" . $replenish['stock_in']	. "</td>";
			echo	"<td>" . $replenish['unit']		. "</td>";
			echo	"<td>" . $replenish['boughtDate']		. "</td>";
			echo "</tr>";
		} ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>