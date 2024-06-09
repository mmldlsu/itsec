<?php
	$ingredientName = $_GET['name'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	// RETRIEVE disparity records
	$disparityRecords = mysqli_query($DBConnect, "	SELECT 		d.sQuantity as system_quantity, d.mQuantity as manual_quantity, DATE(d.createdAt) as date
													FROM 		ingredient i			JOIN	disparity d		ON d.ingredientID=i.ingredientID
													WHERE 		DATE(d.createdAt) 	>= '$date1' 	
													AND 		DATE(d.createdAt) 	<= '$date2'
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	DATE(d.createdAt)
													ORDER BY	DATE(d.createdAt) DESC;");

	$disparities = [];
		while($result = mysqli_fetch_array($disparityRecords)){
			$sys_qty = $result['system_quantity'];
			$man_qty = $result['manual_quantity'];
			$value = $sys_qty - $man_qty;
			$date = $result['date'];

			if($value > 0){
				$identifier = "Missing";
			}
			else if($value < 0){
				$identifier = "Surplus";
				$value = abs($value);
			}

			$disparity = array(
				'value' => $value,
				'identifier' => $identifier,
				'date' => $date
			);
							
			array_push($disparities, $disparity);	 	 
		}
?>
	<div class="reportlabels">
		<div class="backb"><a href="detailed_report.php?results=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Stock Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed expired report for <?php echo "$ingredientName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<th>Manual Count</th>
		<th>Surplus / Missing</th>
		<th>Date</th>

		<?php foreach ($disparities as $disparity) {
			echo "<tr>";
			echo	"<td>" . $disparity['value']	        . "</td>";
			echo	"<td>" . $disparity['identifier']       . "</td>";
			echo	"<td>" . $disparity['date']             . "</td>";
			echo "</tr>";
		} ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>