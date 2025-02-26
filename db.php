<?php
$host = "mysql.railway.internal"; // MySQL Host
$user = "root"; // MySQL User
$password = "rCOjSGZQlSWiQjReDIkVeRPFSAZdONgx"; // MySQL Password
$database = "railway"; // Database Name
$port = 3306; // MySQL Port

// Create a connection
$conn = new mysqli($host, $user, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
