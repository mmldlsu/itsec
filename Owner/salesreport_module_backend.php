<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			  // collect value of input field	
			  $date1 = $_POST['date1'];
			  $date2 = $_POST['date2'];
			  date_default_timezone_set('Asia/Manila');
			  $timestamp = date("Y-m-d h:i:sa");

			  		$sql0 = "
			  			SELECT 	dishID, dishName
			  			FROM 	dish
						WHERE 	Active ='Yes'
			  			ORDER BY dishName;";

					$sql = "
						SELECT 	d.dishID, d.dishName, SUM(oi.quantity) AS dishes_sold, SUM( d.price * oi.quantity) as total_sales
                        FROM	dish d			JOIN order_item oi		ON oi.dishID=d.dishID
						                        JOIN orders		o 		ON o.orderID=oi.orderID

						WHERE 	 DATE(o.createdAt) >= '$date1'
						AND 	 DATE(o.createdAt) <= '$date2'
						AND		 d.Active ='Yes'
						GROUP BY d.dishID
						ORDER BY d.dishName;";

					$dishes = mysqli_query($DBConnect, $sql0) or die(mysqli_error($DBConnect));
					$records = mysqli_query($DBConnect, $sql) or die(mysqli_error($DBConnect));

					$sales = [];
					while($salesResults = mysqli_fetch_array($records))
					{
						$id = $salesResults['dishID'];
						$dishName = $salesResults['dishName'];
						$dishes_sold = $salesResults['dishes_sold'];
						$total_sales = $salesResults['total_sales'];

						$sale = array(
							'dishID' => $id,
							'dishName' => $dishName,
							'dishes_sold' => $dishes_sold,
							'total_sales' => $total_sales
					 		);
							
						array_push($sales, $sale);	 	 
					}
		
					
					?>
					<div class="reportlabels">
					<h3>Sales Report</h3>
					<h3>Report Created <?php echo "$timestamp"; ?></h3>
					<h3>Report for <?php echo "$date1";?> to <?php echo "$date2"; ?></h3>
					</div>
					<table class="reporttable">
					<th>Dish</th>
                    <th>Total Dishes Sold</th>
					<th>Total Sales</th>
		
					<?php

					$counter = 0;
					$dishes_sold = 0;
					$total_sales = 0;
					while($results = mysqli_fetch_array($dishes))
					{
				?>
					<tr onclick="window.location.href='detailed_salesreport.php?results=<?php echo $results['dishName']; ?>&date1=<?php echo $date1; ?>&date2=<?php echo $date2; ?>';">
					<td ><?php echo $results['dishName']; ?></td>
				<?php
					foreach($sales as $sale){
						if($sale['dishID'] == $results['dishID']){
							$dishes_sold = $sale['dishes_sold'];
							$total_sales = $sale['total_sales'];
							$counter++;
						}
					}

					if($counter > 0){
						echo "<td >".$dishes_sold."</td>";
						echo "<td >".$total_sales."</td>";
						$dishes_sold = 0;
						$total_sales = 0;
						$counter = 0;
					}
					else{
						echo "<td >".$dishes_sold."</td>";
						echo "<td >".$total_sales."</td>";
					}
				?>	
                    </tr>
				<?php
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


		mysqli_close($DBConnect);
		?>
    </body>
</html>