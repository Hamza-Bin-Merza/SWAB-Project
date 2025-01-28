<?php
// Database connection details
$host = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP is empty
$database = "hisham"; // Replace with your actual database name
 
// Connect to MySQL
$con = new mysqli($host, $username, $password, $database);
 
// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>