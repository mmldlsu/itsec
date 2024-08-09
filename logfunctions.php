<?php
date_default_timezone_set('Asia/Manila');
function logMessage($level, $message, $usr_id = null, $evt_category = null, $evt_outcome = null, $network_client_ip = null, $logFileName = 'authorization.log') {
    #   $logDir = __DIR__ . '/logs/';
       $logDir = 'C:/xampp/itsec-logs/';  
       $logFile = $logDir . $logFileName;
   
       if (!file_exists($logDir)) {
           mkdir($logDir, 0755, true);
       }
   
       $fileExists = file_exists($logFile);
   
       $date = date('Y-m-d H:i:s');
       
       // Constructing the log entry
       $logEntry = "[$date] [$level]";
       
       if ($usr_id) {
           $logEntry .= " [usr.id: $usr_id]";
       }
       if ($evt_category) {
           $logEntry .= " [evt.category: $evt_category]";
       }
       if ($evt_outcome) {
           $logEntry .= " [evt.outcome: $evt_outcome]";
       }
       if ($network_client_ip) {
           $logEntry .= " [network.client.ip: $network_client_ip]";
       }
       
       $logEntry .= ": $message" . PHP_EOL;
   
       file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
   
       if (!$fileExists) {
           chmod($logFile, 0644);
       }
   }

function logUserActivity($conn, $userId, $action, $details) {
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    error_log($ipAddress);
    error_log($userId);
    error_log($action);
    error_log($details);
    $timestamp = date('Y-m-d H:i:s');  // Get current date and time

    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, action, timestamp, ip_address, details) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $action, $timestamp, $ipAddress, $details);
    $stmt->execute();
} 

?>