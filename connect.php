<?php
     $DBConnect = mysqli_connect("127.0.0.1:4306", "root", "") or die ("Unable to Connect". mysqli_error());
     $db = mysqli_select_db($DBConnect, 'itisdev');

     function inputValidation($qty, $ingredient = NULL, $unit = NULL) {
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
     }
?>