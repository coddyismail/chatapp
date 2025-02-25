<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "chat_app";

$conn = new mysqli($host, $user, $pass, $db_name);
if($conn->connect_error){
    die("Connection failed: . $conn->connect_error");
}
else{
    // echo "Connection Done";
}
?>