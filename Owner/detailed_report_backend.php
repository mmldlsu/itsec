<?php
	$ingredientName = $_GET['results'];
	$date1 = $_GET['date1'];
	$date2 = $_GET['date2'];

	date_default_timezone_set('Asia/Manila');
	$timestamp = date("Y-m-d h:i:sa");

	// RETRIEVE STOCKIN records in replenish table
	$replenishRecords = mysqli_query($DBConnect, "	SELECT 		SUM(re.quantity) as stock_in, DATE(re.boughtDate) as date
													FROM 		ingredient i			JOIN	replenish re		ON re.ingredientID=i.ingredientID
													WHERE 		DATE(re.boughtDate) 	>= '$date1' 	
													AND 		DATE(re.boughtDate) 	<= '$date2' 
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	DATE(re.boughtDate)
													ORDER BY	DATE(re.boughtDate) DESC;");

	$replenishes = [];	
	while ($replenish = mysqli_fetch_array($replenishRecords)) $replenishes[] = ['stock_in' => $replenish['stock_in'], 'date' => $replenish['date']];

	// RETRIEVE STOCKOUT records in orders and order_item table
	$orderRecords = mysqli_query($DBConnect, "		SELECT 		SUM(oi.quantity * r.quantity) as stock_out, DATE(o.createdAt) as date
													FROM 		ingredient i	JOIN recipe 		r	ON r.ingredientID=i.ingredientID
																				JOIN dish			d	ON d.dishID=r.dishID
																				JOIN order_item		oi	ON oi.dishID=d.dishID
																				JOIN orders			o	ON o.orderID=oi.orderID
																				JOIN unit			u	ON u.unitID=i.unitID
													WHERE   	DATE(o.createdAt) >= '$date1' 
													AND     	DATE(o.createdAt) <= '$date2'
													AND    		i.ingredientName='$ingredientName'
													GROUP BY	DATE(o.createdAt)
													ORDER BY	DATE(o.createdAt) DESC;");

	$orders = [];
	while ($order = mysqli_fetch_array($orderRecords)) $orders[] = ['stock_out' => $order['stock_out'], 'date' => $order['date']];
	
	// RETRIEVE STOCKOUT records in expired table
	$expiredRecords = mysqli_query($DBConnect, "	SELECT 		SUM(e.quantity) as expired, DATE(e.expiredDate) as date
													FROM 		ingredient i			JOIN	expired e		ON e.ingredientID=i.ingredientID
													WHERE 		DATE(e.expiredDate) 	>= '$date1' 	
													AND 		DATE(e.expiredDate) 	<= '$date2' 
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	DATE(e.expiredDate)
													ORDER BY	DATE(e.expiredDate) DESC;");

	$expireds = [];
	while ($expired = mysqli_fetch_array($expiredRecords)) $expireds[] = ['stock_out' => $expired['expired'], 'date' => $expired['date']];

	// RETRIEVE disparity records
	$disparityRecords = mysqli_query($DBConnect, "	SELECT 		d.sQuantity as system_quantity, d.mQuantity as manual_quantity, DATE(d.createdAt) as date
													FROM 		ingredient i			JOIN	disparity d		ON d.ingredientID=i.ingredientID
													WHERE 		DATE(d.createdAt) 	>= '$date1' 	
													AND 		DATE(d.createdAt) 	<= '$date2'
													AND         i.ingredientName='$ingredientName'
													GROUP BY 	DATE(d.createdAt)
													ORDER BY	DATE(d.createdAt) DESC;");

	// SEPARATE disparity records into STOCKIN and STOCKOUT
	$dStockIn = [];
	$dStockOut = [];
	while ($disparity = mysqli_fetch_array($disparityRecords)) {
		if ($disparity['manual_quantity'] > $disparity['system_quantity'])
			$dStockIn[] = ['stock_in' => abs($disparity['manual_quantity'] - $disparity['system_quantity']), 'date' => $disparity['date']];
		else if ($disparity['manual_quantity'] < $disparity['system_quantity']) 
			$dStockOut[] = ['stock_out' => abs($disparity['system_quantity'] - $disparity['manual_quantity']), 'date' => $disparity['date']];
	}

	$dates = [];
	
	if(!empty($replenishes)){
		foreach($replenishes as $replenish){
			array_push($dates, $replenish['date']);
		}
	}
	
	if(!empty($orders)){
		foreach($orders as $order){
			array_push($dates, $order['date']);
		}
	}

	if(!empty($expireds)){
		foreach($expireds as $expired){
			array_push($dates, $expired['date']);
		}
	}
	
	if(!empty($dStockIn)){
		foreach($dStockIn as $stock_in){
			array_push($dates, $stock_in['date']);
		}
	}	
	
	if(!empty($dStockOut)){
		foreach($dStockOut as $stock_out){
			array_push($dates, $stock_out['date']);
		}
	}

	rsort($dates);

	$dates = array_unique($dates);

	
?>
	<div class="reportlabels">
		<div class="backb"><a href="generatereports.php?date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>" class="sbt_btn">Back</a></div>
		<h3>Stock Report</h3>
		<h3>Report Created <?php echo "$timestamp"; ?></h3>
		<h3>Detailed report for <?php echo "$ingredientName";?> from <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
	</div>
	<table class="reporttable">
		<thead>
            <tr>
                <th colspan="2">Stock In</th>
                <th colspan="3">Stock Out</th>
                <th rowspan="2">Date</th>
            </tr>

            <tr>
				<th>Replenish</th>
                <th>Surplus</th>
                <th>Orders</th>
                <th>Expired</th>
				<th>Missing</th>
            </tr>
        </thead>

		<?php
			$counter = 0;
			$temp = 0;
		?>

		<?php foreach ($dates as $date) {
			echo "<tr>";

			//for Replenish column
			if(!empty($replenishes)){
				foreach($replenishes as $replenish){
					if($replenish['date'] == $date){
						$counter++;
						$temp+= $replenish['stock_in'];
					}
				}
			}

			if($counter > 0){
		?>
			<td onclick="window.location.href='detailed_replenish.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo $temp; ?></td>
		<?php	
				$temp = 0;
				$counter = 0;
			}
			else{
		?>
			<td onclick="window.location.href='detailed_replenish.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo $temp; ?></td>
		<?php
			$temp = 0;
			}

			//for DISPARITIES Stock In column
			if(!empty($dStockIn)){
				foreach($dStockIn as $stock_in){
					if($stock_in['date'] == $date){
						$counter++;
						$temp+= $stock_in['stock_in'];
					}
				}
			}

			if($counter > 0){
		?>
				<td onclick="window.location.href='detailed_disparities.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo $temp; ?></td>
		<?php	
					$temp = 0;
					$counter = 0;
			}
			else{
		?>
				<td onclick="window.location.href='detailed_disparities.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo $temp; ?></td>
		<?php
				$temp = 0;
			}

			//for ORDERS column
			if(!empty($orders)){
				foreach($orders as $order){
					if($order['date'] == $date){
						$counter++;
						$temp+= $order['stock_out'];
					}
				}
			}
			if($counter > 0){
		?>
			<td onclick="window.location.href='detailed_orders.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php		
				$temp = 0;
				$counter = 0;
			}
			else{
		?>
			<td onclick="window.location.href='detailed_orders.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php
			}

			//for EXPIRED column
			if(!empty($expireds)){
				foreach($expireds as $expired){
					if($expired['date'] == $date){
						$counter++;
						$temp+= $expired['stock_out'];
					}
				}
			}
			
			if($counter > 0){
		?>
				<td onclick="window.location.href='detailed_expired.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php			
				$temp = 0;
				$counter = 0;
			}
			else{
		?>
				<td onclick="window.location.href='detailed_expired.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php		
			}

			//for DISPARITIES Stock Out column
			if(!empty($dStockOut)){
			foreach($dStockOut as $stock_out){
				if($stock_out['date'] == $date){
					$counter++;
					$temp+= $stock_out['stock_out'];
					}
				}
			}
			
			if($counter > 0){
		?>
				<td onclick="window.location.href='detailed_disparities.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php			
				$temp = 0;
				$counter = 0;
			}
			else{
		?>
				<td onclick="window.location.href='detailed_disparities.php?name=<?php echo $ingredientName; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';"><?php echo "$temp"; ?></td>
		<?php		
			}

			echo	"<td>" . $date			. "</td>";
			echo "</tr>";
		} ?>
		
	</table>
	<div class="reportlabels"><h3>*END OF REPORT*</h3></div>
</body>