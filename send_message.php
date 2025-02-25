<?php
session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

if (!isset($_POST['message']) || trim($_POST['message']) === "") {
    echo json_encode(["status" => "error", "message" => "Message is empty"]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}

$username = $_SESSION['username']; // Get sender's name
$message = htmlspecialchars($_POST['message']); // Prevent XSS
$file = "chat_log.txt";

// Ensure file exists
if (!file_exists($file)) {
    file_put_contents($file, ""); // Create if missing
}

// Append message with sender's name
$fullMessage = "$username: $message" . PHP_EOL;

if (file_put_contents($file, $fullMessage, FILE_APPEND) === false) {
    echo json_encode(["status" => "error", "message" => "Failed to write message"]);
    exit;
}

echo json_encode(["status" => "success", "message" => $fullMessage]);
?>
