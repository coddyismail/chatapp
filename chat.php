<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <link rel="stylesheet" href="ct.css">
   
    <script>
    let lastMessageCount = 0; // Track last message count to avoid unnecessary reloads

    function sendMessage() {
        let message = document.getElementById("message").value.trim();
        if (message !== "") {
            let formData = new FormData();
            formData.append("message", message);

            fetch("send_message.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    displayMessage(data.username + ": " + data.message, true);
                } else {
                    console.error("⚠️ Error:", data.message);
                }
            })
            .catch(error => console.error("🚫 AJAX Error:", error));

            document.getElementById("message").value = "";
        }
    }

    function fetchMessages() {
    fetch("get_messages.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success" && Array.isArray(data.messages)) {
                let chatBox = document.getElementById("chat-box");
                chatBox.innerHTML = "";  // Clear chat box before updating

                data.messages.forEach(msg => {
                    if (typeof msg === "string" && msg.trim() !== "") {
                        displayMessage(msg);
                    }
                });
            } else {
                console.error("⚠️ Error fetching messages:", data.message || "Invalid response format");
            }
        })
        .catch(error => console.error("🚫 AJAX Error:", error));
}


    function displayMessage(text, isSender) {
        let chatBox = document.getElementById("chat-box");
        let messageElement = document.createElement("p");

        if (isSender) {
            messageElement.style.textAlign = "right"; // Align sent messages to the right
        } else {
            messageElement.style.textAlign = "left"; // Align received messages to the left
        }

        messageElement.textContent = text;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll
    }

    setInterval(fetchMessages, 2000);  // Fetch messages every 2 seconds
    window.onload = fetchMessages;
</script>

</head>
<body>
<div class="chat-container">
        <div class="chat-header">
            Welcome,    <?php echo $_SESSION['username'];   if ($_SESSION['username'] == 'fff') {
                 echo  " (author)";
            }?> !
        </div>
        
        <div id="chat-box"></div>

        <div class="chat-input">
            <input type="text" id="message" placeholder="Type a message..." onkeypress="if(event.keyCode === 13) sendMessage();">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <div class="logout">
        <a href="home.php" style="background-color: #191919;">Logout</a>
    </div>
</body>
</html>
