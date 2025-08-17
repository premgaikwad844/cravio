<?php
// Chatbot widget for PHP include
?>
<!-- Chatbot Widget Start -->
<link rel="stylesheet" href="Chatbot.css" />
<script>
  // Set this to your production backend URL
  window.CHATBOT_API_URL = "https://premgaikwad.pythonanywhere.com/chat";
</script>
<div id="chatbot-widget">
  <button id="chatbot-toggle" onclick="toggleChatbotWindow()" title="Open Chatbot">
    <span class="chatbot-icon"></span>
  </button>
  <div class="chatbot-window" id="chatbot-window">
    <div class="chat-container">
      <div class="chat-header">
        <h2>Cravio Chatbot</h2>
      </div>
      <div class="prompt-bar">
        <button class="prompt-btn" onclick="sendPrompt('How do I use the search bar?')">How do I use the search bar?</button>
        <button class="prompt-btn" onclick="sendPrompt('What is this website?')">What is this website?</button>
        <button class="prompt-btn" onclick="sendPrompt('How do I contact support?')">How do I contact support?</button>
        <button class="prompt-btn" onclick="sendPrompt('How do I filter food by diet?')">How do I filter food by diet?</button>
      </div>
      <div id="chat-box"></div>
      <div class="input-area">
        <input type="text" id="user-input" placeholder="Type your message here..." onkeydown="if(event.key==='Enter'){sendMessage();}" />
        <button onclick="sendMessage()">Send</button>
        <button id="clear-btn" onclick="clearChat()" title="Clear All">Clear</button>
        <button id="input-copy-btn" class="copy-btn" title="Copy"><span class="copy-icon"></span></button>
      </div>
    </div>
  </div>
  <div id="welcome-popup" class="welcome-popup">
    <div class="welcome-content">
      <p>Hi! Need help? Chat with me.</p>
    </div>
  </div>
</div>
<script src="Chatbot.js"></script>
<!-- Chatbot Widget End --> 