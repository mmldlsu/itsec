<?php

if (!defined('DEBUG')) {
    define('DEBUG', true); // Set to false to disable debug mode
}

// Set error reporting based on debug mode
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Check if the function is already defined before declaring it
if (!function_exists('globalErrorHandler')) {
    // Global Error Handler
    function globalErrorHandler($errno, $errstr, $errfile, $errline)
    {
        $errorMsg = "Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}";

        if (DEBUG) {
            echo "<b>Debug Error:</b> {$errorMsg}<br>";
            echo "<pre>" . print_r(debug_backtrace(), true) . "</pre><br>";
        } else {
            error_log($errorMsg, 3, 'error_log.txt');
            echo 'An error occurred.';
        }

        return true;
    }
}

// Check if the function is already defined before declaring it
if (!function_exists('globalExceptionHandler')) {
    // Global Exception Handler
    function globalExceptionHandler($exception)
    {
        $exceptionMsg = "Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();

        if (DEBUG) {
            echo "<b>Debug Error:</b> {$exceptionMsg}<br>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre><br>";
        } else {
            error_log($exceptionMsg, 3, 'error_log.txt');
            echo 'An error occurred.';
        }

        exit();
    }
}

// Check if the function is already defined before declaring it
if (!function_exists('shutdownFunction')) {
    // Shutdown function to catch fatal errors
    function shutdownFunction()
    {
        $error = error_get_last();
        if ($error !== null) {
            globalErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}

// Register global handlers
set_error_handler("globalErrorHandler");
set_exception_handler("globalExceptionHandler");
register_shutdown_function('shutdownFunction');

?>
