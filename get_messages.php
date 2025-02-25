<?php
header("Content-Type: application/json");

if (file_exists("chat_log.txt")) {
    $messages = file("chat_log.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    echo json_encode(["status" => "success", "messages" => $messages]);
} else {
    echo json_encode(["status" => "success", "messages" => []]); // Return empty if no messages
}
?>
