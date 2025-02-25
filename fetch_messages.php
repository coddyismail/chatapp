<?php
session_start();

if (!isset($_SESSION['chat_messages'])) {
    $_SESSION['chat_messages'] = [];
}

echo json_encode($_SESSION['chat_messages']);
?>
