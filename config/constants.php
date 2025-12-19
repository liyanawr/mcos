<?php 
// Start Session only if it is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create Constants to Store Non Repeating Values
define('SITEURL', 'http://localhost/mcos/');
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mcos');
    
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die(mysqli_error()); 
$db_select = mysqli_select_db($conn, DB_NAME) or die(mysqli_error()); 
?>