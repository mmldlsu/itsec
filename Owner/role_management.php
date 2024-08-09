<?php
session_start();
include '../connect.php';
include '../logfunctions.php';; // Include your logging function
include '../config.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
    if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
    if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
    else if ($_SESSION['role'] === 'Admin') {

        if (isset($_POST['rolechoice'])) {
            // Collect input values
            $newrole = $_POST['rolechoice'];
            $employeeid = $_POST['employeeid'];
            $client_ip = $_SERVER['REMOTE_ADDR'];
            $usr_id = $_SESSION['id'];
            $email = $_SESSION['email']; // Assuming admin's email is stored in session

            // Get the current role of the user
            logMessage('INFO', 'Attempt by user ' . $_SESSION['email'] . ' Reading of Data from Database Tables Users' . $email,  $usr_id, 'Account Changes', 'Attempted', $client_ip, 'readReqs.log');

            $currentRoleQuery = mysqli_query($conn, "SELECT role FROM users WHERE user_id='$employeeid'");
            $currentRole = mysqli_fetch_assoc($currentRoleQuery)['role'];

            if (isset($_POST['update'])) {
                $sql = "UPDATE users SET role='$newrole' WHERE user_id='$employeeid';";
                $records = mysqli_query($conn, $sql) or die(mysqli_error($conn));

                // Log the role change and update request to the database
                logMessage('INFO', 'User ' . $_SESSION['email'] . ' Updating  of Data on Database Tables Users (Replacing)'  . ' successfully updated account with ID:' . $employeeid . ' from ' . $currentRole . ' to ' . $newrole, $usr_id, 'Role Change',  'Success', $client_ip, 'UpdateReqs.log');
                logMessage('INFO', 'User ' . $usr_id . ' changed role for user ID ' . $employeeid . ' from ' . $currentRole . ' to ' . $newrole, $usr_id, 'Role Change', 'Success', $client_ip, 'account_changes.log');
                logUserActivity($conn, $_SESSION['id'] , 'Change of User Role', 'User ' . $usr_id . ' changed role for user ID ' . $employeeid . ' from ' . $currentRole . ' to ' . $newrole, $usr_id);
            }

            if (isset($_POST['terminate'])) {
                mysqli_query($conn, "UPDATE users SET status = 'Deactivated' WHERE user_id='$employeeid';");

                // Log the account deactivation
                logMessage('INFO', 'User ' . $usr_id . ' deactivated account for user ID ' . $employeeid . ' with role ' . $currentRole, $usr_id, 'Account Deactivation', 'Success', $client_ip, 'account_deactivation.log');
                logUserActivity($conn, $_SESSION['id'] , 'Account Deactivation' ,'User ' . $usr_id . ' deactivated account for user ID ' . $employeeid . ' with role ' . $currentRole, $usr_id, 'Account Deactivation');
                echo '<script>window.location.href = "role_management.php";</script>';
            }
        }

        $sql = "SELECT user_id, first_name, last_name, role FROM users WHERE status = 'Active' ORDER BY user_id;";
        $records = mysqli_query($conn, $sql) or die(mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Roles</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php @include 'navbar.php' ?>
    <div class="roleview">
        <h1>List of Employees</h1>
        <div class="content">
            <table border='1' width='25%'>
            <tbody>    
                <th>NAME </th>
                <th>ROLE </th>
            </tbody>
        
            <tbody>
    <?php
        while($wow = mysqli_fetch_array($records)) {
            $EMPLOYEE_ID = $wow['user_id'];
            $FIRST_NAME = $wow['first_name'];
            $LAST_NAME = $wow['last_name'];
            $ROLE = $wow['role'];
    ?>        
        <tr>  
            <td>  
                <form method='post' action='editemployee.php'>  
                    <input type="submit" name="action" value="<?= $FIRST_NAME . ' ' . $LAST_NAME ?>" style="padding: 0; border: none; background: none; color: white;" />  
                    <input type='hidden' name='employee_id' value='<?= $EMPLOYEE_ID ?>'>  
                </form>  
            </td>  
            <td> <?=$ROLE?> </td>  
        </tr>  

    <?php
        }
    ?>
        </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php
    }
} else {
    header("Location: ../home.php");
    exit();
}
?>
