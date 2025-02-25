const ws = new WebSocket("wss://e2e8-103-160-28-186.ngrok-free.app");




sws.onopen = () => console.log("Connected to WebSocket server!");
ws.onerror = (err) => console.log("WebSocket Error: ", err);
ws.onmessage = (event) => {
    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML += `<p>${event.data}</p>`;
};

function sendMessage() {
    const input = document.getElementById("messageInput");
    if (input.value.trim()) {
        ws.send(input.value);
        input.value = ""; // Clear input after sending
    }
}
