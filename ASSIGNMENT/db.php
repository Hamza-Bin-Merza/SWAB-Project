<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_management";

// Create a connection using mysqli
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
