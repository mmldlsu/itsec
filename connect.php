<?php
//     $DBConnect = mysqli_connect("127.0.0.1:3307", "root", "") or die ("Unable to Connect". mysqli_error());

//     $conn = mysqli_select_db($DBConnect, 'itsecwb');

 // define('DB_HOST', '127.0.0.1:3306'); // Replace with your MySQL host
/*
define('DB_HOST', '127.0.0.1:3307'); // Replace with your MySQL host
define('DB_USER', 'root'); // Replace with your MySQL username
define('DB_PASSWORD', ''); // Replace with your MySQL password
define('DB_NAME', 'itsecwb'); // Replace with your database name
*/

if (!defined('DEBUG')) {
     define('DEBUG', true); // Set to false to disable debug mode
 }
 
 error_reporting(0);
 
 // Session timeout after 10 minutes of inactivity
 $timeout_duration = 600; // change to 600 for 10 minutes (60s * 10 = 600s) // or change to lower value to check if it words
 
 if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
     // Last request was more than the timeout duration
     session_unset();    
     session_destroy();   
     header("Location: index.php?error=Session Timeout"); 
     exit();
 }
 
 $_SESSION['LAST_ACTIVITY'] = time(); // Update activity time 

// cloud db
define('DB_HOST', 'itsecwb-cap-2233.f.aivencloud.com:10849'); // Replace with your MySQL host
define('DB_USER', 'avnadmin'); // Replace with your MySQL username
define('DB_PASSWORD', 'AVNS_967cdqGleFe-rR02H-s'); // Replace with your MySQL password
define('DB_NAME', 'itsecwb'); // Replace with your database name

try {
     $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
} catch(Exception $e) {
    
     if (DEBUG) {
          echo 'Debug Error: ' .$e->getMessage();
          echo  '<br></br>';
          echo print_r(debug_backtrace(), true);
          echo  '<br></br>';
      } else {
          // echo 'ERROR 404';
          header("Location: index.php?error=ERROR 404");
      }
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
