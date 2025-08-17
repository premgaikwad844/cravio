// Configurable backend URL for chatbot
const CHATBOT_API_URL = window.CHATBOT_API_URL || "http://127.0.0.1:5000/chat";

async function sendMessage() {
  const inputField = document.getElementById("user-input");
  const message = inputField.value;
  if (!message.trim()) return;

  addMessage("user", message);
  inputField.value = "";

  // Show researching animation
  const researchingId = addResearchingMessage();

  try {
    const response = await fetch(CHATBOT_API_URL, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ message: message })
    });

    const data = await response.json();
    removeResearchingMessage(researchingId);
    addMessage("bot", data.reply);
  } catch (err) {
    removeResearchingMessage(researchingId);
    addMessage("bot", "Sorry, there was an error fetching the response.");
  }
}

function addMessage(sender, message) {
  const chatBox = document.getElementById("chat-box");
  const msgDiv = document.createElement("div");
  msgDiv.classList.add("message", sender);

  // Message text span
  const textSpan = document.createElement("span");
  textSpan.className = "message-text";
  textSpan.innerText = message;
  msgDiv.appendChild(textSpan);

  // Copy button
  const copyBtn = document.createElement("button");
  copyBtn.className = "copy-btn";
  copyBtn.title = "Copy";
  copyBtn.innerHTML = '<span class="copy-icon"></span>';
  copyBtn.onclick = function() {
    navigator.clipboard.writeText(message);
    showCopyTooltip(copyBtn);
  };
  msgDiv.appendChild(copyBtn);

  // Show copy button logic: visible while hovering message or button, 3s timeout after leaving both
  let showCopyTimeout;
  let isOverMessage = false;
  let isOverButton = false;
  function showCopy() {
    msgDiv.classList.add('show-copy');
    clearTimeout(showCopyTimeout);
  }
  function hideCopyWithDelay() {
    clearTimeout(showCopyTimeout);
    showCopyTimeout = setTimeout(() => {
      if (!isOverMessage && !isOverButton) {
        msgDiv.classList.remove('show-copy');
      }
    }, 1000);
  }
  msgDiv.addEventListener('mouseenter', function() {
    isOverMessage = true;
    showCopy();
  });
  msgDiv.addEventListener('mouseleave', function() {
    isOverMessage = false;
    hideCopyWithDelay();
  });
  copyBtn.addEventListener('mouseenter', function() {
    isOverButton = true;
    showCopy();
  });
  copyBtn.addEventListener('mouseleave', function() {
    isOverButton = false;
    hideCopyWithDelay();
  });

  chatBox.appendChild(msgDiv);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function showCopyTooltip(btn) {
  btn.classList.add('copied');
  setTimeout(() => btn.classList.remove('copied'), 1000);
}

function addResearchingMessage() {
  const chatBox = document.getElementById("chat-box");
  const msgDiv = document.createElement("div");
  msgDiv.classList.add("message", "bot", "researching");
  msgDiv.innerHTML = '<span class="dot-typing"></span> Researching...';
  chatBox.appendChild(msgDiv);
  chatBox.scrollTop = chatBox.scrollHeight;
  return msgDiv;
}

function removeResearchingMessage(msgDiv) {
  if (msgDiv && msgDiv.parentNode) {
    msgDiv.parentNode.removeChild(msgDiv);
  }
}

function clearChat() {
  const chatBox = document.getElementById("chat-box");
  chatBox.innerHTML = "";
}

function toggleChatbotWindow() {
  const widget = document.getElementById('chatbot-widget');
  const chatWindow = document.getElementById('chatbot-window');
  const input = document.getElementById('user-input');
  widget.classList.toggle('active');
  if (widget.classList.contains('active')) {
    setTimeout(() => { input && input.focus(); }, 200);
    // Hide welcome popup if visible
    var welcomePopup = document.querySelector('#chatbot-widget #welcome-popup');
    if (welcomePopup && welcomePopup.classList.contains('active')) {
      welcomePopup.classList.remove('active');
    }
    // No need to remove chatbot button; let chat window overlap it
  } else {
    input && input.blur();
    // No need to re-add chatbot button
  }
}

function sendPrompt(prompt) {
  const inputField = document.getElementById("user-input");
  inputField.value = prompt;
  sendMessage();
  inputField.focus();
}

window.onload = function() {
  // Add copy button to input
  const inputArea = document.querySelector('.input-area');
  const userInput = document.getElementById('user-input');
  if (inputArea && userInput && !document.getElementById('input-copy-btn')) {
    const inputCopyBtn = document.createElement('button');
    inputCopyBtn.id = 'input-copy-btn';
    inputCopyBtn.className = 'copy-btn';
    inputCopyBtn.title = 'Copy';
    inputCopyBtn.innerHTML = '<span class="copy-icon"></span>';
    inputCopyBtn.onclick = function() {
      navigator.clipboard.writeText(userInput.value);
      showCopyTooltip(inputCopyBtn);
    };
    inputArea.insertBefore(inputCopyBtn, userInput.nextSibling);
  }
  // Welcome popup logic
  var welcomePopup = document.querySelector('#chatbot-widget #welcome-popup');
  if (welcomePopup) welcomePopup.classList.add('active');
  // Hide popup when clicking outside
  document.addEventListener('mousedown', function(event) {
    const widget = document.getElementById('chatbot-widget');
    var welcomePopup = document.querySelector('#chatbot-widget #welcome-popup');
    if (welcomePopup && welcomePopup.classList.contains('active')) {
      if (!welcomePopup.contains(event.target) && !document.getElementById('chatbot-toggle').contains(event.target)) {
        welcomePopup.classList.remove('active');
      }
    }
    // Hide chatbot window when clicking outside
    const chatWindow = document.getElementById('chatbot-window');
    if (widget.classList.contains('active')) {
      if (
        chatWindow &&
        !chatWindow.contains(event.target) &&
        !document.getElementById('chatbot-toggle').contains(event.target)
      ) {
        widget.classList.remove('active');
        // Optionally blur input
        const input = document.getElementById('user-input');
        input && input.blur();
      }
    }
  });
}; 