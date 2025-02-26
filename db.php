<?php
$host = getenv("MYSQLHOST") ?: "mysql.railway.internal";
$db = getenv("MYSQLDATABASE") ?: "railway";
$user = getenv("MYSQLUSER") ?: "root";
$pass = getenv("MYSQLPASSWORD") ?: "rCOjSGZQlSWiQjReDIkVeRPFSAZdONgx";
$port = getenv("MYSQLPORT") ?: 3306;

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
