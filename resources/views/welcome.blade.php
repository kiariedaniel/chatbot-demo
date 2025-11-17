<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Style Chatbot</title>
    <style>
    body {
        background: #e5ddd5;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        padding-top: 30px;
    }

    .chat-container {
        width: 380px;
        height: 600px;
        background: #f0f0f0;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 0 10px #aaa;
    }

    .chat-header {
        background: #075e54;
        padding: 15px;
        color: white;
        display: flex;
        align-items: center;
        font-size: 18px;
    }

    .chat-header img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        background: #efeae2;
    }

    .msg-row {
        display: flex;
        margin-bottom: 12px;
        align-items: flex-end;
    }

    .msg-row.bot {
        justify-content: flex-start;
    }

    .msg-row.user {
        justify-content: flex-end;
    }

    .msg-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .msg-bubble {
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 14px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .bot-bubble {
        background: #ffffff;
        border-top-left-radius: 0;
    }

    .user-bubble {
        background: #dcf8c6;
        border-top-right-radius: 0;
        margin-right: 5px;
    }

    .timestamp {
        font-size: 11px;
        color: gray;
        margin-top: 3px;
        text-align: right;
    }

    .options button {
        margin-top: 5px;
        display: block;
        padding: 8px 12px;
        background: #128c7e;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }
     
/* Fade-in animation for messages */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade {
    animation: fadeIn 0.3s ease;
}

/* Button hover animation */
.options button {
    transition: 0.2s;
}

.options button:hover {
    background: #0d6d64;
    transform: scale(1.03);
}

/* Typing indicator */
.typing {
    display: inline-block;
    padding: 10px 14px;
    background: #ffffff;
    border-radius: 6px;
    font-size: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    margin-left: 45px;
}

.typing span {
    height: 8px;
    width: 8px;
    background: #999;
    display: inline-block;
    margin: 0 2px;
    border-radius: 50%;
    animation: blink 1.4s infinite;
}

@keyframes blink {
    0% { opacity: .2; }
    20% { opacity: 1; }
    100% { opacity: .2; }
}

.typing span:nth-child(2) { animation-delay: .2s; }
.typing span:nth-child(3) { animation-delay: .4s; }

/* Mic button press */
#micBtn:active { transform: scale(0.9); }
    </style>
</head>

<body>

<div class="chat-container">

    <!-- Header -->
    <div class="chat-header">
        <img src="https://i.ibb.co/2Fsf7QH/bot-avatar.png">
        Demo Chatbot
    </div>

    <!-- Chat Window -->
    <div class="chat-body" id="chat-body"></div>

    <!-- Input + Mic -->
    <div style="padding: 10px; background: #f0f0f0; display: flex; align-items: center; gap: 10px;">
        <input id="voiceInput" type="text" placeholder="Speak or type..."
               style="flex: 1; padding: 10px; border-radius: 20px; border: none; background: white;">

        <button id="micBtn"
                style="background: #25d366; border: none; padding: 12px; border-radius: 50%; cursor: pointer;">
            🎤
        </button>
    </div>

</div>

<audio id="botVoice"></audio>

<script>

function currentTime() {
    let d = new Date();
    return d.getHours() + ":" + String(d.getMinutes()).padStart(2,'0');
}

function appendBotMessage(message) {
    document.getElementById('chat-body').innerHTML += `
        <div class="msg-row bot fade">
            <img class="msg-avatar" src="https://i.ibb.co/2Fsf7QH/bot-avatar.png">
            <div>
                <div class="msg-bubble bot-bubble">${message}</div>
                <div class="timestamp">${currentTime()}</div>
            </div>
        </div>
    `;
    speakBotMessage(message);  // 🔊 Bot talks
    scrollToBottom();
}

function appendUserMessage(message) {
    document.getElementById('chat-body').innerHTML += `
        <div class="msg-row user fade">
            <div>
                <div class="msg-bubble user-bubble">${message}</div>
                <div class="timestamp">${currentTime()}</div>
            </div>
            <img class="msg-avatar" src="https://i.ibb.co/9V39vj1/user-avatar.png">
        </div>
    `;
    scrollToBottom();
}

function showTyping() {
    document.getElementById('chat-body').innerHTML += `
        <div id="typing" class="msg-row bot fade">
            <div class="typing"><span></span><span></span><span></span></div>
        </div>
    `;
    scrollToBottom();
}

function removeTyping() {
    let typing = document.getElementById("typing");
    if (typing) typing.remove();
}

function scrollToBottom() {
    let chatBody = document.getElementById('chat-body');
    chatBody.scrollTop = chatBody.scrollHeight;
}

/* 🔊 BOT TEXT TO SPEECH */
function speakBotMessage(text) {
    const msg = new SpeechSynthesisUtterance();
    msg.text = text;
    msg.lang = "en-US";
    msg.rate = 1;
    msg.pitch = 1;
    speechSynthesis.speak(msg);
}

/* 🎤 USER VOICE INPUT */
let recognition;
try {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = "en-US";
    recognition.continuous = false;
} catch (e) {
    console.log("Speech recognition not supported", e);
}

document.getElementById("micBtn").onclick = () => {
    if (!recognition) {
        alert("Your browser does not support voice input.");
        return;
    }
    recognition.start();
    document.getElementById("voiceInput").placeholder = "Listening… 🎤";
};

if (recognition) {
    recognition.onresult = function(event) {
        const text = event.results[0][0].transcript;
        document.getElementById("voiceInput").value = text;
        appendUserMessage(text);
        loadStep(text.toLowerCase().replace(/\s+/g, ""));
    };

    recognition.onend = function() {
        document.getElementById("voiceInput").placeholder = "Speak or type…";
    };
}

function loadStep(step) {
    showTyping();

    setTimeout(() => {
        removeTyping();

        fetch("/chatbot/flow", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ step: step })
        })
        .then(res => res.json())
        .then(data => {

            // MULTI-BUBBLE SUPPORT
            if (data.multi) {
                data.multi.forEach((msg, index) => {
                    setTimeout(() => {
                        appendBotMessage(msg.message);
                    }, index * 500);
                });
            } 
            else {
                appendBotMessage(data.message);
            }

            // BUTTON OPTIONS
            if (data.options) {
                let buttons = "";
                data.options.forEach(option => {
                    buttons += `<button onclick="chooseOption('${option.goto}', '${option.label}')">${option.label}</button>`;
                });

                document.getElementById('chat-body').innerHTML += `
                    <div class="msg-row bot fade">
                        <img class="msg-avatar" src="https://i.ibb.co/2Fsf7QH/bot-avatar.png">
                        <div>
                            <div class="msg-bubble bot-bubble options">${buttons}</div>
                            <div class="timestamp">${currentTime()}</div>
                        </div>
                    </div>
                `;
            }

            scrollToBottom();
        });
    }, 800);
}


function chooseOption(step, text) {
    appendUserMessage(text);
    loadStep(step);
}

// If user presses ENTER on text input
document.getElementById("voiceInput").addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        const text = this.value.trim();
        if (text.length > 0) {
            appendUserMessage(text);
            loadStep(text.toLowerCase());
            this.value = "";
        }
    }
});

// Start chat
window.onload = () => loadStep("start");



window.onload = () => loadStep("start");

</script>

</body>
</html>
