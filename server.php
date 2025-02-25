<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$host = "0.0.0.0";
$port = 8080;
$clients = [];

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "WebSocket server running on port $port...\n";

while (true) {
    $changed = $clients;
    $changed[] = $socket;
    socket_select($changed, $null, $null, 0, 10);

    if (in_array($socket, $changed)) {
        $newClient = socket_accept($socket);
        $clients[] = $newClient;
        $request = socket_read($newClient, 5000);
        
        // Perform WebSocket handshake
        if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $request, $matches)) {
            $key = trim($matches[1]);
            $acceptKey = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
            $upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
                       "Upgrade: websocket\r\n".
                       "Connection: Upgrade\r\n".
                       "Sec-WebSocket-Accept: $acceptKey\r\n\r\n";
            socket_write($newClient, $upgrade, strlen($upgrade));
            echo "New client connected\n";
        }

        unset($changed[array_search($socket, $changed)]);
    }

    foreach ($changed as $client) {
        $data = socket_read($client, 1024);
        if ($data === false) {
            socket_close($client);
            unset($clients[array_search($client, $clients)]);
            continue;
        }

        $message = unmask($data);
        if (!empty($message)) {
            echo "Received: $message\n";
            foreach ($clients as $otherClient) {
                if ($otherClient != $client) {
                    socket_write($otherClient, mask($message), strlen(mask($message)));
                }
            }
        }
    }
}

function mask($text) {
    $b1 = 0x81;
    $length = strlen($text);
    if ($length <= 125) {
        return pack('CC', $b1, $length) . $text;
    } elseif ($length > 125 && $length < 65536) {
        return pack('CCn', $b1, 126, $length) . $text;
    } else {
        return pack('CCNN', $b1, 127, $length) . $text;
    }
}

function unmask($payload) {
    $length = ord($payload[1]) & 127;
    if ($length == 126) {
        $masks = substr($payload, 4, 4);
        $data = substr($payload, 8);
    } elseif ($length == 127) {
        $masks = substr($payload, 10, 4);
        $data = substr($payload, 14);
    } else {
        $masks = substr($payload, 2, 4);
        $data = substr($payload, 6);
    }

    $text = "";
    for ($i = 0; $i < strlen($data); ++$i) {
        $text .= $data[$i] ^ $masks[$i % 4];
    }
    return $text;
}