function sendMessage() {
  const input = document.getElementById("user-input");
  const log = document.getElementById("chat-log");

  if (!input.value.trim()) return;

  const userMessage = document.createElement("p");
  userMessage.innerHTML = "<strong>You:</strong> " + input.value;
  log.appendChild(userMessage);

  const botMessage = document.createElement("p");
  botMessage.innerHTML = "<strong>Bot:</strong> I'm still learning. Please try again later.";
  log.appendChild(botMessage);

  input.value = "";
  log.scrollTop = log.scrollHeight;
}