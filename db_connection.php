<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "swap project";

// Create a connection using mysqli
$con = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
