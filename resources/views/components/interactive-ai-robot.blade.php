{{-- Premium 3D AI Robot Chat System --}}
<!-- Include Animate.css for smooth animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<!-- Include jQuery for N8N API calls -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
/* Premium 3D AI Robot System */
.ai-robot-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9997;
}

/* Cursor Following Dots - Only in Chat Sidebar */
.cursor-dots {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.robot-chat-sidebar.open .cursor-dots {
    opacity: 1;
}

.cursor-dot {
    position: absolute;
    width: 4px;
    height: 4px;
    background: linear-gradient(45deg, #66d2ea, #4ba252);
    border-radius: 50%;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow: 0 0 10px rgba(102, 210, 234, 0.5);
}

.cursor-dot.active {
    opacity: 0.8;
    transform: scale(1.2);
}

.cursor-dot:nth-child(1) { transition-delay: 0ms; }
.cursor-dot:nth-child(2) { transition-delay: 50ms; }
.cursor-dot:nth-child(3) { transition-delay: 100ms; }
.cursor-dot:nth-child(4) { transition-delay: 150ms; }
.cursor-dot:nth-child(5) { transition-delay: 200ms; }

/* Premium 3D AI Robot Face */
.ai-robot-character {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 80px;
    height: 80px;
    pointer-events: auto;
    cursor: pointer;
    z-index: 10000;
    animation: premiumRobotEntrance 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    opacity: 0;
    transform: translateY(50px) scale(0.6) rotateY(-20deg);
    perspective: 1000px;
}

.robot-face {
    position: relative;
    width: 80px;
    height: 80px;
    background: linear-gradient(145deg, #8b5cf6 0%, #a855f7 50%, #c084fc 100%);
    border-radius: 50%;
    margin: 0 auto;
    box-shadow: 
        0 15px 30px rgba(139, 92, 246, 0.4),
        inset 0 3px 12px rgba(255, 255, 255, 0.3),
        inset 0 -3px 12px rgba(0, 0, 0, 0.2);
    animation: premiumRobotFloat 4s ease-in-out infinite;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform-style: preserve-3d;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.robot-face::before {
    content: '';
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 60px;
    background: linear-gradient(145deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
    border-radius: 50%;
    border: 1px solid rgba(255,255,255,0.2);
}

.robot-face:hover {
    transform: scale(1.1) rotateY(5deg);
    box-shadow: 
        0 20px 40px rgba(139, 92, 246, 0.5),
        inset 0 3px 15px rgba(255, 255, 255, 0.4),
        inset 0 -3px 15px rgba(0, 0, 0, 0.3);
}

.robot-face:hover .robot-mouth {
    width: 30px;
    height: 16px;
    border-width: 3px;
    box-shadow: 
        0 0 15px rgba(0, 245, 255, 0.9),
        inset 0 -4px 8px rgba(0, 245, 255, 0.5);
}

.robot-eyes {
    position: absolute;
    top: 22px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 2;
}

.robot-eye {
    width: 14px;
    height: 14px;
    background: radial-gradient(circle at 30% 30%, #00f5ff, #00d4ff, #0099cc);
    border-radius: 50%;
    animation: premiumRobotBlink 5s infinite;
    box-shadow: 
        0 0 15px rgba(0, 245, 255, 0.8),
        inset 0 2px 4px rgba(255, 255, 255, 0.6),
        inset 0 -2px 4px rgba(0, 0, 0, 0.3);
    position: relative;
}

.robot-eye::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 3px;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    animation: eyeShine 3s ease-in-out infinite;
}

.robot-eye::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border: 2px solid rgba(0, 245, 255, 0.3);
    border-radius: 50%;
    animation: eyeGlow 2s ease-in-out infinite alternate;
}

.robot-mouth {
    position: absolute;
    bottom: 18px;
    left: 50%;
    transform: translateX(-50%);
    width: 24px;
    height: 12px;
    border: 2px solid #00f5ff;
    border-top: none;
    border-radius: 0 0 24px 24px;
    background: transparent;
    animation: premiumRobotSmile 3s ease-in-out infinite;
    box-shadow: 
        0 0 8px rgba(0, 245, 255, 0.6),
        inset 0 -2px 4px rgba(0, 245, 255, 0.3);
    z-index: 2;
}


/* Premium Robot Speech Bubble */
.robot-speech {
    position: absolute;
    bottom: 100px;
    right: -10px;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    padding: 18px 24px;
    border-radius: 25px 25px 8px 25px;
    box-shadow: 
        0 15px 35px rgba(0, 0, 0, 0.15),
        0 5px 15px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    max-width: 280px;
    font-size: 14px;
    color: #2d3748;
    line-height: 1.5;
    opacity: 0;
    transform: translateY(25px) scale(0.85) rotateX(-10deg);
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    pointer-events: none;
    border: 2px solid rgba(102, 126, 234, 0.2);
    font-weight: 500;
    backdrop-filter: blur(10px);
}

.robot-speech::after {
    content: '';
    position: absolute;
    bottom: -8px;
    right: 20px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid white;
}

.robot-speech.show {
    opacity: 1;
    transform: translateY(0) scale(1) rotateX(0deg);
    animation: speechBubbleBounce 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes speechBubbleBounce {
    0% {
        transform: translateY(25px) scale(0.85) rotateX(-10deg);
        opacity: 0;
    }
    60% {
        transform: translateY(-5px) scale(1.05) rotateX(2deg);
        opacity: 1;
    }
    100% {
        transform: translateY(0) scale(1) rotateX(0deg);
        opacity: 1;
    }
}

.robot-speech .typing-dots {
    display: inline-flex;
    gap: 3px;
    margin-left: 5px;
}

.robot-speech .typing-dot {
    width: 4px;
    height: 4px;
    background: #6366f1;
    border-radius: 50%;
    animation: typingDots 1.4s ease-in-out infinite;
}

.robot-speech .typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.robot-speech .typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

/* Premium Expandable Chat Input (Hidden by default) */
.robot-chat-input {
    position: fixed;
    bottom: 30px;
    right: 130px;
    width: 55px;
    height: 55px;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    border-radius: 28px;
    box-shadow: 
        0 15px 35px rgba(0, 0, 0, 0.12),
        0 5px 15px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    border: 2px solid rgba(102, 126, 234, 0.2);
    pointer-events: auto;
    cursor: pointer;
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    display: none; /* Hidden by default - only shows in chat */
}

.robot-chat-input.expanded {
    width: 350px;
    border-radius: 25px;
    cursor: default;
}

.robot-chat-input-icon {
    font-size: 20px;
    color: #6366f1;
    transition: all 0.3s ease;
}

.robot-chat-input.expanded .robot-chat-input-icon {
    opacity: 0;
    transform: scale(0);
}

.robot-chat-textarea {
    position: absolute;
    left: 20px;
    right: 60px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    outline: none;
    font-size: 14px;
    resize: none;
    opacity: 0;
    transition: opacity 0.3s ease 0.2s;
    background: transparent;
    color: #374151;
}

.robot-chat-input.expanded .robot-chat-textarea {
    opacity: 1;
}

.robot-send-btn {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(145deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: translateY(-50%) scale(0);
    transition: all 0.3s ease 0.2s;
}

.robot-chat-input.expanded .robot-send-btn {
    opacity: 1;
    transform: translateY(-50%) scale(1);
}

.robot-send-btn:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
}

/* Full Chat Interface */
.robot-chat-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0);
    opacity: 0;
    pointer-events: none;
    transition: all 0.4s ease;
    z-index: 9998;
}

.robot-chat-overlay.open {
    background: rgba(0, 0, 0, 0.3);
    opacity: 1;
    pointer-events: auto;
}

.robot-chat-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 655px;
    height: 100vh;
    background: white;
    transform: translateX(100%);
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    pointer-events: auto;
    display: flex;
    flex-direction: column;
    box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
    z-index: 9999;
}

.robot-chat-sidebar.open {
    transform: translateX(0);
}

.robot-chat-header {
    padding: 30px 25px;
    background: linear-gradient(135deg, #66d2ea 0%, #4ba252 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
}

.robot-chat-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.robot-chat-header::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.05) 50%, transparent 70%);
    animation: headerShimmer 3s ease-in-out infinite;
    pointer-events: none;
}

@keyframes headerShimmer {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    50% { transform: translateX(-50%) translateY(-50%) rotate(45deg); }
    100% { transform: translateX(0%) translateY(0%) rotate(45deg); }
}

.robot-chat-title {
    display: flex;
    align-items: center;
    gap: 18px;
    position: relative;
    z-index: 1;
}

.robot-header-avatar {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    animation: headerRobotFloat 2s ease-in-out infinite;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.2);
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.robot-header-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 18px;
}

.header-info {
    flex: 1;
}

.header-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    letter-spacing: -0.5px;
}

.header-subtitle {
    margin: 2px 0 0 0;
    font-size: 13px;
    opacity: 0.9;
    font-weight: 400;
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.15);
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    margin-top: 6px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.header-badge-icon {
    width: 12px;
    height: 12px;
    background: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
}

.robot-chat-close {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 10px;
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    backdrop-filter: blur(10px);
}

.robot-chat-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg) scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Quick Questions Section */
.quick-questions-section {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 16px;
    max-height: 140px;
    overflow-y: auto;
    transition: all 0.3s ease;
}

.quick-questions-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
}

.quick-questions-title {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quick-questions-toggle {
    background: linear-gradient(135deg, #66d2ea 0%, #4ba252 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 210, 234, 0.3);
}

.quick-questions-toggle:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 210, 234, 0.4);
}

.quick-questions-section.collapsed .quick-questions-grid {
    display: none;
}

.quick-questions-section.collapsed {
    padding: 10px 16px;
}

.quick-questions-icon {
    font-size: 16px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.quick-questions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 8px;
}

.quick-question-btn {
    background: white;
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 8px 12px;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-size: 12px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.quick-question-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 210, 234, 0.1), transparent);
    transition: left 0.5s ease;
}

.quick-question-btn:hover::before {
    left: 100%;
}

.quick-question-btn:hover {
    border-color: #66d2ea;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 210, 234, 0.2);
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.quick-question-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 12px rgba(102, 210, 234, 0.3);
}

.question-icon {
    font-size: 16px;
    flex-shrink: 0;
}

.robot-chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    background: white;
    display: flex;
    flex-direction: column;
}

.robot-message.typing {
    background: white;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
}

.robot-message.typing .robot-avatar-small {
    width: 30px;
    height: 30px;
    background: linear-gradient(145deg, #6366f1, #8b5cf6);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    animation: smallRobotThink 1s ease-in-out infinite;
}

/* Expandable Chat Input Area in Sidebar */
.robot-chat-input-area {
    padding: 20px;
    background: white;
    border-top: 1px solid #e5e7eb;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: center;
}

.expandable-chat-input {
    width: 50px;
    height: 50px;
    background: linear-gradient(145deg, #66d2ea, #4ba252);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    box-shadow: 0 5px 15px rgba(102, 210, 234, 0.3);
}

.expandable-chat-input.expanded {
    width: 100%;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 25px;
    padding: 6px 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    cursor: default;
    align-items: center;
    min-height: 56px;
}

.expandable-input-icon {
    font-size: 20px;
    color: white;
    transition: all 0.3s ease;
}

.expandable-chat-input.expanded .expandable-input-icon {
    display: none;
}

.robot-chat-textarea-main {
    flex: 1;
    border: none;
    outline: none;
    font-size: 16px;
    line-height: 1.5;
    resize: none;
    max-height: 120px;
    min-height: 44px;
    font-family: inherit;
    background: transparent;
    margin-right: 10px;
    padding: 8px 0;
    vertical-align: middle;
    overflow-y: auto;
    /* Hide scrollbar but keep functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

/* Hide scrollbar for Chrome, Safari and Opera */
.robot-chat-textarea-main::-webkit-scrollbar {
    display: none;
}

.robot-chat-textarea-main:focus {
    outline: none;
}

.expandable-chat-input.expanded:focus-within {
    border-color: #66d2ea;
    box-shadow: 0 5px 20px rgba(102, 210, 234, 0.3);
}

/* Typing animation styles */
.typing-text {
    margin: 0;
    line-height: 1.6;
    color: #374151;
}

.typing-text::after {
    content: '|';
    animation: blink 1s infinite;
    color: #6366f1;
    font-weight: bold;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

.typing-text.typing-complete::after {
    display: none;
}

/* User message styling for line breaks */
.robot-message.user {
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.5;
    background: linear-gradient(135deg, #66d2ea 0%, #4ba252 100%);
    color: white;
    padding: 12px 16px;
    border-radius: 18px 18px 4px 18px;
    margin: 8px 0 8px auto;
    max-width: 80%;
    align-self: flex-end;
    box-shadow: 0 2px 8px rgba(102, 210, 234, 0.3);
}

/* AI message chat bubble styling */
.robot-message.ai {
    margin: 8px 0;
    display: flex;
    align-items: flex-start;
}

.ai-message-container {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    max-width: 85%;
}

.ai-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(145deg, #6366f1, #8b5cf6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    overflow: hidden;
}

.ai-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.ai-message-bubble {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px 18px 18px 18px;
    padding: 12px 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
}

.ai-message-bubble::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 12px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid #8990d4;
}

/* Typing dots in bubble */
.ai-message-bubble .typing-dots {
    display: flex;
    gap: 4px;
    align-items: center;
}

.ai-message-bubble .typing-dot {
    width: 6px;
    height: 6px;
    background: #6366f1;
    border-radius: 50%;
    animation: typingDots 1.4s ease-in-out infinite;
}

.ai-message-bubble .typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.ai-message-bubble .typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typingDots {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.4;
    }
    30% {
        transform: translateY(-8px);
        opacity: 1;
    }
}


.robot-chat-send-main {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(145deg, #66d2ea, #4ba252);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 14px;
    box-shadow: 0 3px 10px rgba(102, 210, 234, 0.3);
}

.robot-chat-send-main:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 210, 234, 0.4);
}

.robot-chat-send-main:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    transform: scale(1);
}

/* Premium 3D Animations */
@keyframes premiumRobotEntrance {
    0% {
        opacity: 0;
        transform: translateY(80px) scale(0.6) rotateY(-20deg) rotateX(10deg);
        filter: blur(5px);
    }
    40% {
        opacity: 0.8;
        transform: translateY(-15px) scale(1.1) rotateY(10deg) rotateX(-5deg);
        filter: blur(2px);
    }
    70% {
        transform: translateY(5px) scale(0.95) rotateY(-5deg) rotateX(2deg);
        filter: blur(0px);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1) rotateY(0deg) rotateX(0deg);
        filter: blur(0px);
    }
}

@keyframes premiumRobotFloat {
    0%, 100% { 
        transform: translateY(0) rotateY(0deg) rotateX(0deg);
    }
    25% { 
        transform: translateY(-12px) rotateY(2deg) rotateX(1deg);
    }
    50% { 
        transform: translateY(-8px) rotateY(0deg) rotateX(-1deg);
    }
    75% { 
        transform: translateY(-12px) rotateY(-2deg) rotateX(1deg);
    }
}

@keyframes premiumHeadBob {
    0%, 100% { transform: translateX(-50%) rotateY(0deg); }
    50% { transform: translateX(-50%) rotateY(3deg) translateY(-2px); }
}

@keyframes premiumRobotBlink {
    0%, 85%, 100% { 
        height: 14px;
        box-shadow: 
            0 0 15px rgba(0, 245, 255, 0.8),
            inset 0 2px 4px rgba(255, 255, 255, 0.6),
            inset 0 -2px 4px rgba(0, 0, 0, 0.3);
    }
    90%, 95% { 
        height: 3px;
        box-shadow: 
            0 0 8px rgba(0, 245, 255, 0.5),
            inset 0 1px 2px rgba(255, 255, 255, 0.4);
    }
}

@keyframes eyeShine {
    0%, 100% { opacity: 0.9; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.2); }
}

@keyframes eyeGlow {
    0% { 
        border-color: rgba(0, 245, 255, 0.3);
        transform: scale(1);
    }
    100% { 
        border-color: rgba(0, 245, 255, 0.6);
        transform: scale(1.1);
    }
}

@keyframes premiumRobotSmile {
    0%, 100% { 
        width: 24px;
        height: 12px;
        border-width: 2px;
        box-shadow: 0 0 8px rgba(0, 245, 255, 0.6), inset 0 -2px 4px rgba(0, 245, 255, 0.3);
    }
    50% { 
        width: 28px;
        height: 14px;
        border-width: 2.5px;
        box-shadow: 0 0 12px rgba(0, 245, 255, 0.8), inset 0 -3px 6px rgba(0, 245, 255, 0.4);
    }
}

@keyframes premiumRobotWaveLeft {
    0%, 100% { 
        transform: rotate(0deg) rotateY(0deg);
    }
    25% { 
        transform: rotate(-25deg) rotateY(-10deg);
    }
    50% { 
        transform: rotate(15deg) rotateY(5deg);
    }
    75% { 
        transform: rotate(-10deg) rotateY(-5deg);
    }
}

@keyframes premiumRobotWaveRight {
    0%, 100% { 
        transform: rotate(0deg) rotateY(0deg);
    }
    20% { 
        transform: rotate(30deg) rotateY(10deg);
    }
    40% { 
        transform: rotate(-15deg) rotateY(-5deg);
    }
    60% { 
        transform: rotate(20deg) rotateY(8deg);
    }
    80% { 
        transform: rotate(-8deg) rotateY(-3deg);
    }
}

@keyframes typingDots {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}

@keyframes premiumInputSlideIn {
    0% {
        opacity: 0;
        transform: translateX(60px) scale(0.8) rotateY(-15deg);
        filter: blur(3px);
    }
    60% {
        opacity: 0.8;
        transform: translateX(-10px) scale(1.05) rotateY(5deg);
        filter: blur(1px);
    }
    100% {
        opacity: 1;
        transform: translateX(0) scale(1) rotateY(0deg);
        filter: blur(0px);
    }
}

@keyframes headerRobotFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

@keyframes messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes smallRobotThink {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Responsive */
/* Responsive Design */
@media (min-width: 1400px) {
    .robot-chat-sidebar {
        width: 655px; /* Full width on large screens */
    }
}

@media (max-width: 1024px) {
    .robot-chat-sidebar {
        width: 500px;
    }
}

@media (max-width: 768px) {
    .ai-robot-character {
        bottom: 20px;
        right: 20px;
        width: 70px;
        height: 70px;
    }
    
    .robot-face {
        width: 70px;
        height: 70px;
    }
    
    .robot-chat-input {
        right: 110px;
        bottom: 20px;
        width: 50px;
        height: 50px;
    }
    
    .robot-chat-input.expanded {
        width: 280px;
    }
    
    .robot-chat-sidebar {
        width: 100%;
        max-width: 100vw;
    }
    
    .robot-speech {
        max-width: 200px;
        right: -20px;
        bottom: 85px;
    }
    
    .ai-message-container {
        max-width: 90%;
    }
    
    .robot-chat-header {
        padding: 20px 15px;
    }
    
    .robot-header-avatar {
        width: 50px;
        height: 50px;
    }
    
    .header-title {
        font-size: 18px;
    }
    
    .header-subtitle {
        font-size: 12px;
    }
    
    .header-badge {
        font-size: 10px;
        padding: 3px 8px;
    }
    
    .quick-questions-section {
        padding: 8px 12px;
        max-height: 120px;
    }
    
    .quick-questions-grid {
        grid-template-columns: 1fr;
        gap: 6px;
    }
    
    .quick-question-btn {
        font-size: 11px;
        padding: 6px 10px;
        gap: 6px;
    }
    
    .quick-questions-header {
        margin-bottom: 8px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .robot-chat-sidebar {
        width: 100%;
        max-width: 100vw;
    }
    
    .robot-chat-messages {
        padding: 15px;
    }
    
    .robot-chat-input-area {
        padding: 15px;
    }
    
    .ai-message-container {
        max-width: 95%;
    }
    
    .robot-message.user {
        max-width: 85%;
    }
}
</style>

<div class="ai-robot-container">
    <!-- Interactive AI Robot Face -->
    <div class="ai-robot-character" id="aiRobotCharacter">
        <div class="robot-face">
            <div class="robot-eyes">
                <div class="robot-eye"></div>
                <div class="robot-eye"></div>
            </div>
            <div class="robot-mouth"></div>
        </div>
        
        <div class="robot-speech" id="robotSpeech">
            <span id="robotSpeechText">👋 Hi there! Need help finding the perfect university?</span>
            <div class="typing-dots" id="robotTypingDots" style="display: none;">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    </div>

    <!-- Expandable Chat Input -->
    <div class="robot-chat-input" id="robotChatInput">
        <div class="robot-chat-input-icon">💬</div>
        <textarea class="robot-chat-textarea" id="robotTextarea" placeholder="Ask me anything about universities..." rows="1"></textarea>
        <button class="robot-send-btn" id="robotSendBtn">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>

    <!-- Chat Overlay -->
    <div class="robot-chat-overlay" id="robotChatOverlay"></div>

    <!-- Full Chat Sidebar -->
    <div class="robot-chat-sidebar" id="robotChatSidebar">
        <!-- Cursor Following Dots -->
        <div class="cursor-dots" id="cursorDots">
            <div class="cursor-dot"></div>
            <div class="cursor-dot"></div>
            <div class="cursor-dot"></div>
            <div class="cursor-dot"></div>
            <div class="cursor-dot"></div>
        </div>
        
        <div class="robot-chat-header">
            <div class="robot-chat-title">
                <div class="robot-header-avatar">
                    <img src="{{ asset('assets/images/ai_profile.png') }}" alt="AI Assistant" onerror="this.style.display='none'; this.parentNode.innerHTML='🤖';">
                </div>
                <div class="header-info">
                    <h3 class="header-title">AI University Assistant</h3>
                    <p class="header-subtitle">Your personal study abroad guide</p>
                    <div class="header-badge">
                        <div class="header-badge-icon">🎓</div>
                        Trained on 10,000+ Universities Data
                    </div>
                </div>
            </div>
            <button class="robot-chat-close" id="robotChatClose">×</button>
        </div>
        
        <!-- Quick Questions Section -->
        <div class="quick-questions-section" id="quickQuestionsSection">
            <div class="quick-questions-header">
                <div class="quick-questions-title">
                    <span class="quick-questions-icon">💡</span>
                    <span>Quick Questions</span>
                </div>
                <button class="quick-questions-toggle" id="quickQuestionsToggle" style="display: none;">
                    <span class="toggle-icon">📝</span>
                    Show Questions
                </button>
            </div>
            <div class="quick-questions-grid">
                <button class="quick-question-btn" data-question="Which universities accept my score?">
                    <span class="question-icon">🎓</span>
                    Which universities accept my score?
                </button>
                <button class="quick-question-btn" data-question="What documents do I need?">
                    <span class="question-icon">📄</span>
                    What documents do I need?
                </button>
                <button class="quick-question-btn" data-question="When are visa processing deadlines?">
                    <span class="question-icon">⏰</span>
                    When are visa processing deadlines?
                </button>
                <button class="quick-question-btn" data-question="Can I work part-time?">
                    <span class="question-icon">💼</span>
                    Can I work part-time?
                </button>
                <button class="quick-question-btn" data-question="What's the SOP format?">
                    <span class="question-icon">📝</span>
                    What's the SOP format?
                </button>
                <button class="quick-question-btn" data-question="Which universities can I apply to with IELTS 6.5?">
                    <span class="question-icon">🌟</span>
                    Which universities can I apply to with IELTS 6.5?
                </button>
            </div>
        </div>

        <div class="robot-chat-messages" id="robotChatMessages">
            <!-- Messages will be added dynamically -->
        </div>
        
        <!-- Expandable Chat Input Area -->
        <div class="robot-chat-input-area">
            <div class="expandable-chat-input" id="expandableChatInput">
                <div class="expandable-input-icon" id="expandableInputIcon">💬</div>
                <textarea class="robot-chat-textarea-main" id="robotChatTextareaMain" placeholder="Ask me anything about universities..." rows="1" style="display: none;"></textarea>
                <button class="robot-chat-send-main" id="robotChatSendMain" style="display: none;">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Interactive AI Robot JavaScript
let robotChatOpen = false;
let inputExpanded = false;
let chatInputExpanded = false;
let speechTimeout;

// N8N Chat Integration Variables
let chatSessionId = '{{ uniqid() }}'; // Generate a session ID for this page load
let studentContext = null;
let studentContextLoaded = false;
let welcomeMessageShown = false;

const robotMessages = [
    "👋 Hi there! Click me to start chatting about universities!",
    "🎓 Need study abroad guidance? Let's talk!",
    "💡 Ready to explore your university options? Click to chat!",
    "🌟 I'm here to help with your educational journey!",
    "📚 Questions about admissions or visas? Click to ask!",
    "🚀 Let's find your dream university together!"
];

// Robot Speech Management
function showRobotSpeech(message, showTyping = false) {
    const speechBubble = document.getElementById('robotSpeech');
    const speechText = document.getElementById('robotSpeechText');
    const typingDots = document.getElementById('robotTypingDots');
    
    if (showTyping) {
        speechText.style.display = 'none';
        typingDots.style.display = 'inline-flex';
        speechBubble.classList.add('show');
        
        setTimeout(() => {
            typingDots.style.display = 'none';
            speechText.textContent = message;
            speechText.style.display = 'inline';
        }, 1500);
    } else {
        speechText.textContent = message;
        speechBubble.classList.add('show');
    }
    
    clearTimeout(speechTimeout);
    speechTimeout = setTimeout(() => {
        speechBubble.classList.remove('show');
    }, 4000);
}

// Expandable Input Management
function toggleChatInput() {
    const chatInput = document.getElementById('robotChatInput');
    const textarea = document.getElementById('robotTextarea');
    
    if (!inputExpanded) {
        chatInput.classList.add('expanded');
        inputExpanded = true;
        setTimeout(() => textarea.focus(), 300);
        showRobotSpeech("Great! What would you like to know?", true);
    }
}

function collapseChatInput() {
    const chatInput = document.getElementById('robotChatInput');
    chatInput.classList.remove('expanded');
    inputExpanded = false;
}

// Function to fetch student context
async function fetchStudentContext() {
    if (studentContextLoaded) return;
    
    try {
        // Check if we're on a student page and the route exists
        const contextRoute = '{{ route("student.career-corner.student-context") }}';
        
        const response = await fetch(contextRoute, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            studentContext = await response.json();
            studentContextLoaded = true;
            console.log('Student context loaded:', studentContext);
            console.log('Has profile:', studentContext.hasProfile);
            console.log('Criteria available:', !!(studentContext.studentContext && studentContext.studentContext.criteria));
        } else {
            console.log('No student context available or not on student page');
            studentContext = { hasProfile: false, criteria: null };
        }
    } catch (error) {
        console.error('Error fetching student context (might be on non-student page):', error);
        studentContext = { hasProfile: false, criteria: null };
        studentContextLoaded = true; // Mark as loaded to prevent retries
    }
}

// Function to open chat with context loading
async function openChatDirectly() {
    if (!studentContextLoaded) {
        await fetchStudentContext();
    }
    openFullChat();
}

// Full Chat Management
function openFullChat() {
    robotChatOpen = true;
    document.getElementById('robotChatOverlay').classList.add('open');
    document.getElementById('robotChatSidebar').classList.add('open');
    document.body.style.overflow = 'hidden';
    
    // Hide robot face completely when chat opens
    document.getElementById('aiRobotCharacter').style.display = 'none';
    // Hide the expandable input (we now have input in sidebar)
    document.getElementById('robotChatInput').style.display = 'none';
    
    // Focus on the main chat input
    setTimeout(() => {
        document.getElementById('robotChatTextareaMain').focus();
    }, 300);
    
    // Show welcome message with profile info after a short delay (only first time)
    if (!welcomeMessageShown) {
        setTimeout(() => {
            showWelcomeMessage();
            welcomeMessageShown = true;
        }, 800);
    }
}

function closeFullChat() {
    robotChatOpen = false;
    document.getElementById('robotChatOverlay').classList.remove('open');
    document.getElementById('robotChatSidebar').classList.remove('open');
    document.body.style.overflow = '';
    
    // Show robot face again (hide expandable input)
    document.getElementById('aiRobotCharacter').style.display = 'block';
    document.getElementById('robotChatInput').style.display = 'none';
}

// Message Management for Main Chat
function sendMainChatMessage() {
    const textarea = document.getElementById('robotChatTextareaMain');
    const message = textarea.value.trim();
    
    if (!message) return;
    
    // Add user message
    addRobotMessage('user', message);
    
    // Collapse the input after sending
    collapseChatInput();
    
    // Show typing indicator
    showTypingIndicator();
    
    // Prepare payload with student context
    const payload = {
        sessionId: chatSessionId,
        chatInput: message
    };
    
    // Add student context if available with correct structure
    if (studentContext && studentContext.hasProfile && studentContext.studentContext) {
        payload.studentContext = {
            hasProfile: true,
            formattedAnswers: studentContext.studentContext.formattedAnswers || [],
            criteria: studentContext.studentContext.criteria || {}
        };
    } else {
        payload.studentContext = {
            hasProfile: false
        };
    }
    
    // API Call to N8N
    $.ajax({
        url: 'https://n8n.exploring-talent.com/webhook/consultancy/startchat',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(response) {
            hideTypingIndicator();
            
            // Parse response based on actual API response structure
            let aiResponse = '';
            if (typeof response === 'string') {
                aiResponse = response;
            } else if (typeof response === 'object') {
                // Try to find the message in common fields
                aiResponse = response.output || response.message || response.text || response.response || JSON.stringify(response);
            }
            
            // Add AI response with typing animation
            addRobotMessage('ai', aiResponse);
        },
        error: function(xhr) {
            hideTypingIndicator();
            addRobotMessage('ai', 'Sorry, I encountered an error. Please try again.');
            console.error('AI Chat Error:', xhr);
        }
    });
}

// Expandable Chat Input Management
function toggleChatInput() {
    const chatInput = document.getElementById('expandableChatInput');
    const textarea = document.getElementById('robotChatTextareaMain');
    const sendBtn = document.getElementById('robotChatSendMain');
    
    if (!chatInputExpanded) {
        // Expand the input
        chatInput.classList.add('expanded');
        textarea.style.display = 'block';
        sendBtn.style.display = 'flex';
        chatInputExpanded = true;
        
        setTimeout(() => {
            textarea.focus();
        }, 300);
    }
}

function collapseChatInput() {
    const chatInput = document.getElementById('expandableChatInput');
    const textarea = document.getElementById('robotChatTextareaMain');
    const sendBtn = document.getElementById('robotChatSendMain');
    
    if (chatInputExpanded) {
        chatInput.classList.remove('expanded');
        textarea.style.display = 'none';
        sendBtn.style.display = 'none';
        textarea.value = '';
        textarea.style.height = '44px'; // Reset height
        chatInput.style.minHeight = '50px'; // Reset container height
        chatInputExpanded = false;
    }
}

// Auto-resize textarea
function autoResizeTextarea(textarea) {
    // Reset height to get accurate scrollHeight
    textarea.style.height = '44px';
    
    // Calculate new height based on content, with max limit
    const newHeight = Math.min(textarea.scrollHeight, 120);
    textarea.style.height = newHeight + 'px';
    
    // Ensure parent container adjusts too
    const parentContainer = textarea.closest('.expandable-chat-input');
    if (parentContainer) {
        const containerHeight = Math.max(56, newHeight + 12); // 12px for padding
        parentContainer.style.minHeight = containerHeight + 'px';
    }
}

function addRobotMessage(sender, message, isTyping = false) {
    const messagesContainer = document.getElementById('robotChatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `robot-message ${sender}`;
    
    if (sender === 'user') {
        // Preserve line breaks for user messages
        const formattedMessage = message.replace(/\n/g, '<br>');
        messageDiv.innerHTML = formattedMessage;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    } else {
        // AI message with typing animation - chat bubble style
        messageDiv.innerHTML = `
            <div class="ai-message-container">
                <div class="ai-avatar">
                    <img src="{{ asset('assets/images/ai_profile.png') }}" alt="AI" onerror="this.style.display='none'; this.parentNode.innerHTML='🤖';">
                </div>
                <div class="ai-message-bubble">
                    <div class="typing-text" id="typingText-${Date.now()}"></div>
                </div>
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        
        // Start typing animation
        const typingElement = messageDiv.querySelector('.typing-text');
        typeMessage(typingElement, message);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Animated typing function with faster speed
function typeMessage(element, message, speed = 25) {
    // Format message with markdown-like parsing for bold, newlines, and bullet points
    const formattedMessage = message
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/^• /gm, '• '); // Keep bullet points as is
    
    let index = 0;
    element.innerHTML = '';
    
    // Create a temporary div to parse HTML properly
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = formattedMessage;
    const textContent = tempDiv.textContent || tempDiv.innerText || '';
    
    function typeChar() {
        if (index < textContent.length) {
            // Get the current portion of text
            const currentText = textContent.substring(0, index + 1);
            
            // Apply formatting to the current text
            let displayText = currentText
                .replace(/\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            element.innerHTML = displayText;
            index++;
            
            // Scroll to bottom as text appears
            const messagesContainer = document.getElementById('robotChatMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            setTimeout(typeChar, speed);
        } else {
            // Set final formatted content and remove blinking cursor
            element.innerHTML = formattedMessage;
            element.classList.add('typing-complete');
        }
    }
    
    typeChar();
}

// Function to show welcome message with profile info (only once per session)
function showWelcomeMessage() {
    let welcomeMessage = '';
    
    // Check for student context with the correct nested structure
    if (studentContext && studentContext.hasProfile && studentContext.studentContext && studentContext.studentContext.criteria) {
        // Message with profile information
        const criteria = studentContext.studentContext.criteria;
        console.log('Using student criteria:', criteria);
        
        welcomeMessage = `**Great! I have your profile information:**

🌍 **Interested in:** ${criteria.preferredCountries ? criteria.preferredCountries.join(', ') : 'Not specified'}
🎓 **Study Level:** ${criteria.studyLevel || 'Not specified'}
🌟 **Language Test:** ${criteria.languageTests ? criteria.languageTests.join(', ') : 'Not specified'}

This helps me provide personalized recommendations! What would you like to know? 😊`;
    } else {
        // Generic welcome message
        console.log('No student context available, showing generic message');
        welcomeMessage = `Hello! I'm your AI university assistant. I can help you with:

• University recommendations
• Admission requirements  
• Visa guidance
• Course selection

What would you like to know? 😊`;
    }
    
    // Add the welcome message with typing animation
    addRobotMessage('ai', welcomeMessage);
}

function showTypingIndicator() {
    const messagesContainer = document.getElementById('robotChatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'robot-message ai';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `
        <div class="ai-message-container">
            <div class="ai-avatar">
                <img src="{{ asset('assets/images/ai_profile.png') }}" alt="AI" onerror="this.style.display='none'; this.parentNode.innerHTML='🤖';">
            </div>
            <div class="ai-message-bubble">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>
    `;
    
    messagesContainer.appendChild(typingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Cursor Following Dots Effect - Only in Chat Sidebar
let cursorDots = [];
let mouseX = 0, mouseY = 0;

function initCursorDots() {
    const dotsContainer = document.getElementById('cursorDots');
    const chatSidebar = document.getElementById('robotChatSidebar');
    cursorDots = Array.from(dotsContainer.children);
    
    // Only track mouse movement within the chat sidebar
    chatSidebar.addEventListener('mousemove', (e) => {
        const rect = chatSidebar.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
        
        cursorDots.forEach((dot, index) => {
            setTimeout(() => {
                dot.style.left = mouseX + 'px';
                dot.style.top = mouseY + 'px';
                dot.classList.add('active');
                
                setTimeout(() => {
                    dot.classList.remove('active');
                }, 300);
            }, index * 50);
        });
    });
    
    // Hide dots when mouse leaves sidebar
    chatSidebar.addEventListener('mouseleave', () => {
        cursorDots.forEach(dot => {
            dot.classList.remove('active');
        });
    });
}

// Quick Questions Functionality
function initQuickQuestions() {
    const quickQuestionBtns = document.querySelectorAll('.quick-question-btn');
    
    quickQuestionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const question = btn.getAttribute('data-question');
            
            // Add question as user message
            addRobotMessage('user', question);
            
            // Hide quick questions after first use
            hideQuickQuestions();
            
            // Show typing indicator
            showTypingIndicator();
            
            // Send directly to N8N API
            sendQuestionToAI(question);
        });
    });
}

// Function to send quick questions directly to AI
function sendQuestionToAI(message) {
    // Prepare the payload for N8N with correct structure
    const payload = {
        sessionId: chatSessionId,
        chatInput: message
    };
    
    // Add student context if available with correct structure
    if (studentContext && studentContext.hasProfile && studentContext.studentContext) {
        payload.studentContext = {
            hasProfile: true,
            formattedAnswers: studentContext.studentContext.formattedAnswers || [],
            criteria: studentContext.studentContext.criteria || {}
        };
    } else {
        payload.studentContext = {
            hasProfile: false
        };
    }

    // API Call to N8N
    $.ajax({
        url: 'https://n8n.exploring-talent.com/webhook/consultancy/startchat',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(payload),
        success: function(response) {
            hideTypingIndicator();
            
            // Parse response based on actual API response structure
            let aiResponse = '';
            if (typeof response === 'string') {
                aiResponse = response;
            } else if (typeof response === 'object') {
                // Try to find the message in common fields
                aiResponse = response.output || response.message || response.text || response.response || JSON.stringify(response);
            }
            
            // Add AI response with typing animation
            addRobotMessage('ai', aiResponse);
        },
        error: function(xhr) {
            hideTypingIndicator();
            addRobotMessage('ai', 'Sorry, I encountered an error. Please try again.');
            console.error('AI Chat Error:', xhr);
        }
    });
}

function hideQuickQuestions() {
    const quickQuestionsSection = document.getElementById('quickQuestionsSection');
    const toggleButton = document.getElementById('quickQuestionsToggle');
    
    // Collapse the section instead of hiding completely
    quickQuestionsSection.classList.add('collapsed');
    toggleButton.style.display = 'flex';
    toggleButton.innerHTML = '<span class="toggle-icon">📝</span> Show Questions';
}

function showQuickQuestions() {
    const quickQuestionsSection = document.getElementById('quickQuestionsSection');
    const toggleButton = document.getElementById('quickQuestionsToggle');
    
    quickQuestionsSection.classList.remove('collapsed');
    toggleButton.innerHTML = '<span class="toggle-icon">📋</span> Hide Questions';
}

function toggleQuickQuestions() {
    const quickQuestionsSection = document.getElementById('quickQuestionsSection');
    
    if (quickQuestionsSection.classList.contains('collapsed')) {
        showQuickQuestions();
    } else {
        hideQuickQuestions();
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cursor dots effect
    initCursorDots();
    
    // Initialize quick questions
    initQuickQuestions();
    
    // Quick questions toggle button
    document.getElementById('quickQuestionsToggle').addEventListener('click', toggleQuickQuestions);
    
    // Robot character click - opens full chat directly
    document.getElementById('aiRobotCharacter').addEventListener('click', function() {
        if (!robotChatOpen) {
            openChatDirectly();
        }
    });
    
    // Robot hover effects
    document.getElementById('aiRobotCharacter').addEventListener('mouseenter', function() {
        if (!robotChatOpen) {
            showRobotSpeech("Click me to start chatting! 😊");
        }
    });
    
    // Expandable chat input click
    document.getElementById('expandableChatInput').addEventListener('click', function() {
        if (!chatInputExpanded) {
            toggleChatInput();
        }
    });
    
    // Main chat send button
    document.getElementById('robotChatSendMain').addEventListener('click', sendMainChatMessage);
    
    // Main chat textarea enter key
    document.getElementById('robotChatTextareaMain').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMainChatMessage();
        }
    });
    
    // Auto-resize main textarea
    document.getElementById('robotChatTextareaMain').addEventListener('input', function() {
        autoResizeTextarea(this);
    });
    
    // Click outside to collapse chat input
    document.addEventListener('click', function(e) {
        const chatInput = document.getElementById('expandableChatInput');
        const sidebar = document.getElementById('robotChatSidebar');
        
        if (chatInputExpanded && !chatInput.contains(e.target) && sidebar.contains(e.target)) {
            collapseChatInput();
        }
    });
    
    // Close chat
    document.getElementById('robotChatClose').addEventListener('click', closeFullChat);
    document.getElementById('robotChatOverlay').addEventListener('click', closeFullChat);
    
    // Textarea enter key
    document.getElementById('robotTextarea').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendRobotMessage();
        }
    });
    
    // Click outside to collapse input
    document.addEventListener('click', function(e) {
        const chatInput = document.getElementById('robotChatInput');
        const robot = document.getElementById('aiRobotCharacter');
        
        if (inputExpanded && !chatInput.contains(e.target) && !robot.contains(e.target)) {
            collapseChatInput();
        }
    });
    
    // Auto-change robot messages
    setInterval(() => {
        if (!robotChatOpen && !inputExpanded) {
            const randomMessage = robotMessages[Math.floor(Math.random() * robotMessages.length)];
            showRobotSpeech(randomMessage);
        }
    }, 8000);
    
    // Initial welcome message
    setTimeout(() => {
        showRobotSpeech(robotMessages[0]);
    }, 2000);
});
</script>
