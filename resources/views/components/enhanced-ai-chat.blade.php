{{-- Interactive AI Robot Chat System --}}
<style>
/* Interactive AI Robot Chat System */
.ai-robot-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}

/* AI Assistant Card */
.ai-assistant-card {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 320px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 20px;
    pointer-events: auto;
    transform: translateY(100px);
    opacity: 0;
    animation: slideInCard 0.8s ease-out 1s forwards;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ai-assistant-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(20, 184, 166, 0.2);
}

.ai-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.ai-card-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    animation: breathe 3s ease-in-out infinite;
}

.ai-card-avatar::before {
    content: '🤖';
    font-size: 24px;
}

.ai-card-avatar::after {
    content: '';
    position: absolute;
    top: -3px;
    right: -3px;
    width: 16px;
    height: 16px;
    background: #22c55e;
    border-radius: 50%;
    border: 3px solid white;
    animation: pulse 2s infinite;
}

.ai-card-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

.ai-card-status {
    font-size: 12px;
    color: #22c55e;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ai-card-message {
    background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%);
    padding: 15px;
    border-radius: 12px;
    border-left: 4px solid #14b8a6;
    margin-bottom: 15px;
    position: relative;
}

.ai-card-message p {
    margin: 0;
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
}

.ai-card-actions {
    display: flex;
    gap: 10px;
}

.ai-card-btn {
    flex: 1;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.ai-card-btn-primary {
    background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    color: white;
}

.ai-card-btn-secondary {
    background: rgba(20, 184, 166, 0.1);
    color: #14b8a6;
    border: 1px solid rgba(20, 184, 166, 0.2);
}

.ai-card-btn:hover {
    transform: translateY(-1px);
}

/* Interactive AI Character */
.ai-character {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 80px;
    height: 80px;
    pointer-events: auto;
    cursor: pointer;
    z-index: 9998;
    animation: characterEntrance 1s ease-out 0.5s forwards;
    opacity: 0;
    transform: scale(0);
}

.ai-character-body {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    box-shadow: 0 10px 30px rgba(20, 184, 166, 0.3);
    animation: float 3s ease-in-out infinite;
}

.ai-character-face {
    font-size: 32px;
    animation: blink 4s infinite;
}

.ai-character-speech {
    position: absolute;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    padding: 10px 15px;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    font-size: 12px;
    color: #374151;
    white-space: nowrap;
    opacity: 0;
    transform: translateX(-50%) translateY(10px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.ai-character-speech::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 8px solid transparent;
    border-top-color: white;
}

.ai-character:hover .ai-character-speech {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

/* Chat Sidebar */
.enhanced-chat-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 100vh;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(25px);
    border-left: 1px solid rgba(255, 255, 255, 0.3);
    transform: translateX(100%);
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    pointer-events: auto;
    display: flex;
    flex-direction: column;
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
}

.enhanced-chat-sidebar.open {
    transform: translateX(0);
}

.enhanced-chat-header {
    padding: 25px;
    background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.enhanced-chat-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.enhanced-chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.enhanced-chat-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background 0.2s ease;
}

.enhanced-chat-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

.enhanced-chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: rgba(255, 255, 255, 0.05);
}

.enhanced-chat-input-area {
    padding: 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-top: 1px solid rgba(255, 255, 255, 0.3);
}

.enhanced-chat-input-wrapper {
    display: flex;
    gap: 10px;
    align-items: flex-end;
}

.enhanced-chat-textarea {
    flex: 1;
    border: 2px solid #e5e7eb;
    border-radius: 20px;
    padding: 12px 20px;
    resize: none;
    font-size: 14px;
    max-height: 100px;
    transition: border-color 0.2s ease;
}

.enhanced-chat-textarea:focus {
    outline: none;
    border-color: #14b8a6;
}

.enhanced-chat-send {
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.enhanced-chat-send:hover {
    transform: scale(1.05);
}

/* Animations */
@keyframes slideInCard {
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes breathe {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes characterEntrance {
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes blink {
    0%, 90%, 100% { content: '🤖'; }
    95% { content: '😊'; }
}

/* Responsive */
@media (max-width: 768px) {
    .ai-assistant-card {
        width: 280px;
        right: 15px;
        bottom: 15px;
    }
    
    .enhanced-chat-sidebar {
        width: 100%;
    }
    
    .ai-character {
        left: 15px;
        bottom: 15px;
        width: 60px;
        height: 60px;
    }
}
</style>

<div class="enhanced-ai-chat-container">
    <!-- AI Assistant Card -->
    <div class="ai-assistant-card" id="aiAssistantCard">
        <div class="ai-card-header">
            <div class="ai-card-avatar"></div>
            <div class="ai-card-info">
                <h4>AI Career Assistant</h4>
                <p class="ai-card-status">
                    <span style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; display: inline-block;"></span>
                    Online & Ready to Help
                </p>
            </div>
        </div>
        
        <div class="ai-card-message">
            <p id="aiCardMessage">👋 Hi! I can help you find the perfect university for your profile. What would you like to know?</p>
        </div>
        
        <div class="ai-card-actions">
            <button class="ai-card-btn ai-card-btn-primary" onclick="openEnhancedChat()">
                💬 Start Chat
            </button>
            <button class="ai-card-btn ai-card-btn-secondary" onclick="dismissCard()">
                Later
            </button>
        </div>
    </div>

    <!-- Interactive AI Character -->
    <div class="ai-character" id="aiCharacter" onclick="openEnhancedChat()">
        <div class="ai-character-body">
            <div class="ai-character-face">🤖</div>
        </div>
        <div class="ai-character-speech">Click me for help!</div>
    </div>

    <!-- Enhanced Chat Sidebar -->
    <div class="enhanced-chat-sidebar" id="enhancedChatSidebar">
        <div class="enhanced-chat-header">
            <div class="enhanced-chat-title">
                <div class="enhanced-chat-avatar">🤖</div>
                <div>
                    <h3 style="margin: 0; font-size: 18px;">AI Career Assistant</h3>
                    <p style="margin: 0; font-size: 12px; opacity: 0.8;">Always here to help</p>
                </div>
            </div>
            <button class="enhanced-chat-close" onclick="closeEnhancedChat()">×</button>
        </div>
        
        <div class="enhanced-chat-messages" id="enhancedChatMessages">
            <div style="text-align: center; padding: 20px; color: #6b7280;">
                <div style="font-size: 48px; margin-bottom: 10px;">🎓</div>
                <p>Welcome! I'm your AI assistant for university guidance.</p>
                <p style="font-size: 14px;">Ask me about universities, courses, admissions, or anything related to studying abroad!</p>
            </div>
        </div>
        
        <div class="enhanced-chat-input-area">
            <div class="enhanced-chat-input-wrapper">
                <textarea class="enhanced-chat-textarea" id="enhancedChatInput" placeholder="Type your message..." rows="1"></textarea>
                <button class="enhanced-chat-send" onclick="sendEnhancedMessage()">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced AI Chat JavaScript
let enhancedChatOpen = false;
let cardDismissed = false;
let contextualMessages = [
    "👋 Hi! I can help you find the perfect university for your profile. What would you like to know?",
    "🎓 Looking for university recommendations? I'm here to help!",
    "💡 Need guidance on your study abroad journey? Let's chat!",
    "🌟 Ready to explore your university options? Ask me anything!",
    "📚 I can help you with admissions, visas, and course selection!"
];

function openEnhancedChat() {
    enhancedChatOpen = true;
    document.getElementById('enhancedChatSidebar').classList.add('open');
    document.getElementById('aiAssistantCard').style.display = 'none';
    document.getElementById('aiCharacter').style.display = 'none';
    document.body.style.overflow = 'hidden';
    
}

function closeEnhancedChat() {
    enhancedChatOpen = false;
    document.getElementById('enhancedChatSidebar').classList.remove('open');
    if (!cardDismissed) {
        document.getElementById('aiAssistantCard').style.display = 'block';
    }
    document.getElementById('aiCharacter').style.display = 'block';
    document.body.style.overflow = '';
    
}

function dismissCard() {
    cardDismissed = true;
    document.getElementById('aiAssistantCard').style.display = 'none';
}

function sendEnhancedMessage() {
    const input = document.getElementById('enhancedChatInput');
    const message = input.value.trim();
    
    if (message) {
        addMessageToChat('user', message);
        input.value = '';
        
        // Simulate AI response (replace with actual API call)
        setTimeout(() => {
            addMessageToChat('ai', 'Thanks for your message! I\'m here to help you with your university journey.');
        }, 1000);
    }
}

function addMessageToChat(sender, message) {
    const messagesContainer = document.getElementById('enhancedChatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        margin-bottom: 15px;
        padding: 12px 16px;
        border-radius: 18px;
        max-width: 80%;
        ${sender === 'user' ? 
            'background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); color: white; margin-left: auto; text-align: right;' : 
            'background: white; color: #374151; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'
        }
    `;
    messageDiv.textContent = message;
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Auto-update contextual messages
function updateContextualMessage() {
    if (!cardDismissed && !enhancedChatOpen) {
        const randomMessage = contextualMessages[Math.floor(Math.random() * contextualMessages.length)];
        document.getElementById('aiCardMessage').textContent = randomMessage;
    }
}

// Update message every 10 seconds
setInterval(updateContextualMessage, 10000);

// Auto-resize textarea
document.getElementById('enhancedChatInput').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 100) + 'px';
});

// Enter to send message
document.getElementById('enhancedChatInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendEnhancedMessage();
    }
});
</script>

