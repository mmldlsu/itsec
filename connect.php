<?php
//     $DBConnect = mysqli_connect("127.0.0.1:3307", "root", "") or die ("Unable to Connect". mysqli_error());

//     $conn = mysqli_select_db($DBConnect, 'itsecwb');

define('DB_HOST', '127.0.0.1:3307'); // Replace with your MySQL host
define('DB_USER', 'root'); // Replace with your MySQL username
define('DB_PASSWORD', ''); // Replace with your MySQL password
define('DB_NAME', 'itsecwb'); // Replace with your database name

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

     // Commented out ITISDEV Function
/*     function inputValidation($qty, $ingredient = NULL, $unit = NULL) {
          global $DBConnect;
          // qty not work if have number
          echo "qty = " . $qty . "<br />";
          echo "ingredient = " . $ingredient . "<br />";
          echo "unit = " . $unit . "<br /><br />";
          // if ($result) {
               // Checking if there are any rows returned
          if ($unit != NULL) 
               if ($DBConnect -> query("     SELECT	type
                                             FROM      unit 
                                             WHERE	unitID = $unit
                                             AND		type = (  SELECT	u.type
                                                                 FROM      ingredient i	JOIN unit u	ON u.unitID=i.unitID
                                                                 WHERE	ingredientName = '$ingredient');") -> num_rows == 0) return false;
          if ($ingredient != NULL) 
               if (($DBConnect -> query("    SELECT ingredientID FROM ingredient WHERE ingredientName = '$ingredient'")) -> num_rows == 0) return false;


          echo is_numeric($qty);
          echo $qty > 0 ."<br />";
          return is_numeric($qty) && $qty > 0;
     }*/

?>