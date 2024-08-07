<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'Chef') header("Location: ../Chef/viewRecipe.php");
        if ($_SESSION['role'] === 'Cashier') header("Location: ../Cashier/cashier.php");
        if ($_SESSION['role'] === 'Inventory') header("Location: ../Controller/manstockcount.php");
        else if ($_SESSION['role'] === 'Admin') {
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
    <?php
        if (isset($_POST['rolechoice'])) {
            // collect value of input field	
            $newrole = $_POST['rolechoice'];
            $employeeid = $_POST['employeeid'];
                if(isset($_POST['update'])) {
                    $sql = "
                    UPDATE users SET role='$newrole' WHERE user_id='$employeeid';";
                    $records = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                }
                if(isset($_POST['terminate'])) {
                    mysqli_query($conn, "UPDATE users SET status = 'Deactivated' WHERE user_id='$employeeid';");
                    echo '<script>window.location.href = "role_management.php";</script>';
                }
        }
            $sql = "
            SELECT user_id, first_name, last_name, role
            FROM users
            WHERE status = 'Active'
            ORDER BY user_id;";
            $records = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    ?>
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
        while($wow = mysqli_fetch_array($records))
            {
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
    }
    else {
        header("Location: ../home.php");
        exit();
    }
?>