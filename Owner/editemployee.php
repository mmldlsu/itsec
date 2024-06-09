<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
?>
<html>
    <head>
        <title>Employee Details</title>
		<link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    </head>
    <body>
	<?php include 'navbar.php' ?>
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$empid = $_POST['employee_id'];
	
			$sql = "
			SELECT firstName, lastName, role
			FROM   user
			WHERE  employeeID = $empid;";
			$records = mysqli_query($DBConnect, $sql) or die(mysqli_error($DBConnect));
			
			while($wow = mysqli_fetch_array($records))
			{
				$FIRST_NAME = $wow['firstName'];
				$LAST_NAME = $wow['lastName'];
				$ROLE = $wow['role'];
			}
	?>		
	<div class="roleview">
		<h1>Edit Role</h1>
		<div class="content">
			<table border='1' width='25%'>
				<form action='role_management.php' method='post'>
					<input type='hidden' name='employeeid' value='<?= $empid ?>'>
					<tr>
						<td> FIRST NAME: </td> <td><?= $FIRST_NAME ?></td>
					</tr>
					<tr>
						<td> LAST NAME: </td> <td><?= $LAST_NAME ?></td>
					</tr>
					<tr>
						<td> ROLE: </td>
							<td>
								<select id='rolechoice' name='rolechoice'>
									<option value='Chef' style="color: black;">Chef</option>
									<option value='Cashier' style="color: black;">Cashier</option>
									<option value='Inventory' style="color: black;">Inventory</option>
									<option value='Admin' style="color: black;">Admin</option>
								</select>
								<button type="submit" name="update">Update</button>
								<button name="terminate" value="terminate" style="background: rgb(251,0,0); color: white;">Terminate</button>
							</td>
					</tr>
				</form>
			</table>
			<?php		 
				} 
			?>
		</div>
	</div>
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