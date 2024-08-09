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

        // Query to get logs
        $stmt = $conn->prepare("SELECT user_id, action, timestamp, ip_address, details FROM activity_logs ORDER BY timestamp DESC");
        $stmt->execute();
        $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php @include 'navbar.php' ?>
    <div class="roleview">
        <h1>Activity Logs</h1>
        <div class="content">
            <table border='1' width='25%'>
            <tbody>    
                <th>User ID</th>
                <th>Action</th>
                <th>Timestamp</th>
                <th>IP Address</th>
                <th>Details</th>
            </tbody>
        
            <tbody>
    <?php
         while ($row = $result->fetch_assoc()) {
    ?>        
        <tr>  
           
        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td><?php echo htmlspecialchars($row['action']); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
                <td><?php echo htmlspecialchars($row['details']); ?></td>

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
