<?php
$host = "mysql.railway.internal";
$user = "root";
$pass = "rsjvuqetFKJIzsYLEmlEYusQcfgYysQx";
$db_name = "railway";

$conn = new mysqli($host, $user, $pass, $db_name);
if($conn->connect_error){
    die("Connection failed: . $conn->connect_error");
}
else{
    // echo "Connection Done";
}
?>
