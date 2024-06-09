<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			  // collect value of input field	
			  $date1 = $_POST['date1'];
			  $date2 = $_POST['date2'];
			  date_default_timezone_set('Asia/Manila');
			  $timestamp = date("Y-m-d h:i:sa");

			  		$sql0 = "
						SELECT 	i.ingredientID, i.ingredientName, u.unitName
						FROM 	ingredient	i  	JOIN unit	u		ON u.unitID=i.unitID
						ORDER BY i.ingredientName;";

					$sql = "
						SELECT 	i.ingredientID, i.ingredientName, SUM(oi.quantity * r.quantity) AS stock_out, u.unitName
						FROM 	ingredient	i  		JOIN recipe 		r					ON r.ingredientID=i.ingredientID
													JOIN dish			d					ON d.dishID=r.dishID
													JOIN order_item		oi					ON oi.dishID=d.dishID
													JOIN orders			o					ON o.orderID=oi.orderID
													JOIN unit			u					ON u.unitID=i.unitID
	
						WHERE 	 DATE(o.createdAt) >= '$date1' 
						AND 	 DATE(o.createdAt) <= '$date2'
						AND		 d.Active ='Yes'
						GROUP BY i.ingredientID
						ORDER BY i.ingredientName;";
		
					$sql2 = "
						SELECT 		re.ingredientID,i.ingredientName, SUM(re.quantity) as stock_in, u.unitName
						FROM 		ingredient i			JOIN	replenish 	re		ON re.ingredientID=i.ingredientID
															JOIN 	unit	 	u		ON u.unitID=i.unitID
 
						WHERE 		DATE(re.boughtDate) 	>= '$date1' 	
						AND 		DATE(re.boughtDate) 	<= '$date2' 
						GROUP BY 	re.ingredientID
						ORDER BY 	i.ingredientName;";

		
					$sql3 =  "
						SELECT 		e.ingredientID,i.ingredientName, SUM(e.quantity) as expired, u.unitName
						FROM 		ingredient i			JOIN	expired e		ON e.ingredientID=i.ingredientID
													JOIN 	unit	u		ON u.unitID=i.unitID
 
						WHERE 		DATE(e.expiredDate) 	>= '$date1' 	
						AND 		DATE(e.expiredDate) 	<= '$date2' 
						GROUP BY 	e.ingredientID
						ORDER BY 	i.ingredientName;";
					
					$sql4 =  "
						SELECT 		d.ingredientID,i.ingredientName, d.sQuantity as system_quantity, d.mQuantity as manual_quantity
			    		FROM 		ingredient i			JOIN	disparity d		ON d.ingredientID=i.ingredientID
			    		WHERE 		DATE(d.createdAt) 	>= '$date1' 	
			    		AND 		DATE(d.createdAt) 	<= '$date2' 
						ORDER BY 	i.ingredientName;";	

					$ingredients = mysqli_query($DBConnect, $sql0) or die(mysqli_error($DBConnect));
					$records = mysqli_query($DBConnect, $sql) or die(mysqli_error($DBConnect));
					$stockResult = mysqli_query($DBConnect, $sql2) or die(mysqli_error($DBConnect));
					$expiredResult = mysqli_query($DBConnect, $sql3) or die(mysqli_error($DBConnect));
					$disparityResult = mysqli_query($DBConnect, $sql4) or die(mysqli_error($DBConnect));

					$orders = [];
					while($orderResults = mysqli_fetch_array($records))
					{
						$id = $orderResults['ingredientID'];
						$ingredientName = $orderResults['ingredientName'];
						$stockOut = $orderResults['stock_out'];
						$unit = $orderResults['unitName'];

						$order = array(
							'ingredientID' => $id,
							'ingredientName' => $ingredientName,
							'stock_out' => $stockOut,
							'unitName' => $unit
					 		);
							
						array_push($orders, $order);	 	 
					}
		
					$stock_in = [];
					while($stockResults = mysqli_fetch_array($stockResult))
					{
						$id = $stockResults['ingredientID'];
						$ingredientName = $stockResults['ingredientName'];
						$stockIn = $stockResults['stock_in'];
						$unit = $stockResults['unitName'];

						$stocks = array(
							'ingredientID' => $id,
							'ingredientName' => $ingredientName,
							'stock_in' => $stockIn,
							'unitName' => $unit
					 		);
							
						array_push($stock_in, $stocks);	 	 
					}
		
					$expired_array = [];
					while($expiredResults = mysqli_fetch_array($expiredResult))
					{
						$id = $expiredResults['ingredientID'];
						$ingredientName = $expiredResults['ingredientName'];
						$expired_qty = $expiredResults['expired'];
						$unit = $expiredResults['unitName'];

						$expired = array(
							'ingredientID' => $id,
							'ingredientName' => $ingredientName,
							'expired_qty' => $expired_qty,
							'unitName' => $unit
					 		);
							
						array_push($expired_array, $expired);	 	 
					}

					$disparities_array = [];
					while($disparities = mysqli_fetch_array($disparityResult))
					{
						$id = $disparities['ingredientID'];
						$ingredientName = $disparities['ingredientName'];
						$sys_qty = $disparities['system_quantity'];
						$man_qty = $disparities['manual_quantity'];
						$value = $sys_qty - $man_qty;

						if($value > 0){
							$identifier = "STOCK_OUT";
						}
						else if($value < 0){
							$identifier = "STOCK_IN";
							$value = abs($value);
						}

						$disparity = array(
							'ingredientID' => $id,
							'ingredientName' => $ingredientName,
							'value' => $value,
							'identifier' => $identifier
					 		);
							
						array_push($disparities_array, $disparity);	 	 
					}
					?>
					<div class="reportlabels">
					<h3>Stock Report</h3>
					<h3>Report Created <?php echo "$timestamp"; ?></h3>
					<h3>Report for <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
					</div>
					<table class="reporttable">
					<th>Ingredient</th>
					<th>Stock In</th>
					<th>Stock Out</th>
					<th>Unit</th>
		
					<?php
					$in_counter = 0;
					$in = 0;
					$out_counter = 0;
					$out = 0;

					while($results = mysqli_fetch_array($ingredients))
					{
				?>
					<tr onclick="window.location.href='detailed_report.php?results=<?php echo $results['ingredientName']; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';">
					<td ><?php echo $results['ingredientName']; ?></td>
				<?php
						$temp_in = 0;
						$temp_out = 0;
						
						foreach ($disparities_array as $dis) {
							
							if($dis['ingredientID'] == $results['ingredientID']){
								if($dis['identifier'] == "STOCK_IN"){
									$temp_in += $dis['value'];
								}
								else if($dis['identifier'] == "STOCK_OUT"){
									$temp_out += $dis['value'];
								}
							}
			
						}

						if(empty($stock_in)){
							echo "<td>".$temp_in."</td>";
							$temp_in = 0;
						}
						else{
							foreach($stock_in as $stockIn){
								if($stockIn['ingredientID'] == $results['ingredientID']){
									$in_counter++;
									$in = $stockIn['stock_in'];
								}
							}
							
							if($in_counter == 0){
								if ($temp_in != 0){
									$in += $temp_in;
									echo"<td>".$in."</td>";
									$in = 0;
									$temp_in = 0;
								}
								else{
									$in = 0;
									echo"<td>".$in."</td>";
								}
			
							}
							else{
								$in += $temp_in;
								echo"<td>".$in."</td>";
								$in_counter = 0;
								$in = 0;
								$temp_in = 0;
							}
						}

						if(!empty($expired_array)){
							foreach ($expired_array as $exp) {
								if($exp['ingredientID'] == $results['ingredientID']){
									$out = $exp['expired_qty'];
								}
							}

						}

						if(empty($orders)){
							$out += $temp_out;
							echo "<td>".$out."</td>";
							$temp_out = 0;
							$out = 0;

						}
						else{
							foreach($orders as $order){
								if($order['ingredientID'] == $results['ingredientID']){
									$out_counter++;
									$out += $order['stock_out'];
								}
							}
							if($out_counter == 0){
								if ($temp_out != 0){
									$out += $temp_out;
									echo"<td>".$out."</td>";
									$out = 0;
									$temp_out = 0;
								}
								else{
									echo"<td>".$out."</td>";
									$out = 0;
									$temp_out = 0;
								}
			
							}
							else{
								$out += $temp_out;
								echo"<td>".$out."</td>";
								$out_counter = 0;
								$out = 0;
								$temp_out = 0;
							}
						}
						
						echo"<td>".$results['unitName']."</td>";
					}

				?>
				<?php
					echo '</table>';
					?>
					<div class="reportlabels">
					<h3>*END OF REPORT*</h3>
					</div>
					<?php
	}
	else{
		$sql5 = "
				SELECT 	i.ingredientID, i.ingredientName, i.quantity, u.unitName
				FROM 	ingredient	i  		JOIN unit			u					ON u.unitID=i.unitID
				ORDER BY i.ingredientName";

		$inventoryResult = mysqli_query($DBConnect, $sql5) or die(mysqli_error($DBConnect));
	?>
		<div class="reportlabels">
			<h3>Current Stocks</h3>
		</div>
		<table class="reporttable">
			<th>Ingredient</th>
			<th>Current Quantity</th>
			<th>Unit</th>
	<?php	
		while($result = mysqli_fetch_array($inventoryResult))
		{
			echo "<tr>";
			echo"<td>".$result['ingredientName']."</td>";
			echo"<td>".$result['quantity']."</td>";
			echo"<td>".$result['unitName']."</td>";
			echo "</tr>";
		}
	} 

		mysqli_close($DBConnect);
		?>
    </body>
</html>