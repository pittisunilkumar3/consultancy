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
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 999998;
    backdrop-filter: blur(2px);
}

.robot-chat-overlay.show {
    opacity: 1;
    visibility: visible;
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
    z-index: 999999;
}

.robot-chat-sidebar.open {
    transform: translateX(0);
}

.robot-chat-header {
    padding: 20px 25px 25px 25px;
    background: linear-gradient(135deg, #66d2ea 0%, #4ba252 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    border-radius: 0 0 25px 25px;
    box-shadow: 0 4px 20px rgba(102, 210, 234, 0.3);
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
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    animation: headerRobotFloat 2s ease-in-out infinite;
    overflow: hidden;
    border: 3px solid rgba(255, 255, 255, 0.3);
    box-shadow: 
        0 10px 40px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(15px);
    flex-shrink: 0;
}

.robot-header-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 22px;
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
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-size: 22px;
    cursor: pointer;
    padding: 12px;
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 1;
    backdrop-filter: blur(15px);
    flex-shrink: 0;
}

.robot-chat-close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg) scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Floating Quick Questions */
.quick-questions-floating {
    position: absolute;
    bottom: 100px;
    right: 20px;
    z-index: 1000001;
}

.quick-questions-fab {
    background: linear-gradient(135deg, #66d2ea 0%, #4ba252 100%);
    color: white;
    border: none;
    border-radius: 25px;
    padding: 12px 20px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 20px rgba(102, 210, 234, 0.4);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.quick-questions-fab:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(102, 210, 234, 0.5);
}

.fab-icon {
    font-size: 16px;
    animation: pulse 2s infinite;
}

.fab-text {
    font-size: 12px;
    white-space: nowrap;
}

.quick-questions-popup {
    position: absolute;
    bottom: 60px;
    right: 0;
    width: 320px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid #e2e8f0;
    transform: translateY(10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000002;
}

.quick-questions-popup:not(.collapsed) {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.quick-questions-popup-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 15px 15px 0 0;
}

.popup-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #6b7280;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.popup-close:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #374151;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.quick-questions-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
    padding: 15px 20px;
    max-height: 300px;
    overflow-y: auto;
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
    padding-bottom: 10px;
    overflow-y: auto;
    background: white;
    display: flex;
    flex-direction: column;
    margin-bottom: 0;
    /* Hide scrollbar but keep scroll functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.robot-chat-messages::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
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
    position: relative;
    z-index: 1000000;
    min-height: 90px;
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
    z-index: 1000001;
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
    position: relative;
    z-index: 2;
}

.robot-message.user {
    position: relative;
    z-index: 2;
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
    
    .robot-chat-input-area {
        position: fixed;
        bottom: 60px; /* Move up on tablets */
        left: 0;
        right: 0;
        padding: 18px;
        background: white;
        border-top: 2px solid #e5e7eb;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
        z-index: 1000002;
    }
    
    .robot-chat-messages {
        padding-bottom: 100px; /* Add space for floating input */
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
        padding-bottom: 120px; /* Add space for floating input */
    }
    
    .robot-chat-input-area {
        position: fixed;
        bottom: 80px; /* Move up from bottom */
        left: 0;
        right: 0;
        padding: 15px;
        background: white;
        border-top: 2px solid #e5e7eb;
        box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
        z-index: 1000002;
    }
    
    /* Handle mobile browsers with dynamic viewport */
    .robot-chat-sidebar {
        height: 100vh;
        height: 100dvh; /* Dynamic viewport height for modern browsers */
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

        <div class="robot-chat-messages" id="robotChatMessages" style="position: relative;">
            <!-- Shader Background Canvas for Chat Messages -->
            <canvas id="robot-chat-shader-canvas" style="
                position: absolute;
                top: 0;
                left: 0;
                width: auto;
                height: auto;
                z-index: 1;
                pointer-events: none;
                border-radius: inherit;
            "></canvas>
            <!-- Messages will be added dynamically -->
        </div>
        
        <!-- Quick Questions Floating Button -->
        <div class="quick-questions-floating" id="quickQuestionsFloating">
            <button class="quick-questions-fab" id="quickQuestionsFab" onclick="toggleQuickQuestions()">
                <span class="fab-icon">💡</span>
            </button>
            
            <!-- Quick Questions Popup -->
            <div class="quick-questions-popup collapsed" id="quickQuestionsPopup">
                <div class="quick-questions-popup-header">
                    <span>💡 Quick Questions</span>
                    <button class="popup-close" onclick="toggleQuickQuestions()">×</button>
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
                    <button class="quick-question-btn" data-question="How much does it cost?">
                        <span class="question-icon">💰</span>
                        How much does it cost?
                    </button>
                </div>
            </div>
        </div>

        <!-- Expandable Chat Input Area -->
        <div class="robot-chat-input-area">
            <div class="expandable-chat-input expanded" id="expandableChatInput">
                <div class="expandable-input-icon" id="expandableInputIcon">💬</div>
                <textarea class="robot-chat-textarea-main" id="robotChatTextareaMain" placeholder="Ask me anything about universities..." rows="1"></textarea>
                <button class="robot-chat-send-main" id="robotChatSendMain">
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
let chatInputExpanded = true; // sidebar input is always expanded
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
    
    // Initialize shader background in chat messages area
    if (typeof window.initRobotChatShaderBackground === 'function') {
        window.initRobotChatShaderBackground();
    }
    
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
    
    // Stop shader background
    if (typeof window.stopRobotChatShaderBackground === 'function') {
        window.stopRobotChatShaderBackground();
    }
    
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

    // Clear input but keep it expanded
    textarea.value = '';
    textarea.style.height = '44px';
    autoResizeTextarea(textarea);
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
function typeMessage(element, message, speed = 10) {
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



// Quick Questions Functionality
function initQuickQuestions() {
    const quickQuestionBtns = document.querySelectorAll('.quick-question-btn');
    
    quickQuestionBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const question = btn.getAttribute('data-question');
            
            // Add question as user message
            addRobotMessage('user', question);
            
            // Hide quick questions popup after use
            const quickQuestionsPopup = document.getElementById('quickQuestionsPopup');
            if (quickQuestionsPopup) {
                quickQuestionsPopup.classList.add('collapsed');
            }
            
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

function toggleQuickQuestions() {
    const quickQuestionsPopup = document.getElementById('quickQuestionsPopup');
    
    if (quickQuestionsPopup.classList.contains('collapsed')) {
        quickQuestionsPopup.classList.remove('collapsed');
    } else {
        quickQuestionsPopup.classList.add('collapsed');
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize quick questions
    initQuickQuestions();
    
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

{{-- Simple Shader Background Script for Chat Messages --}}
<script>
// Simple animated background for robot chat messages area
let robotChatShaderCanvas = null;
let robotChatShaderGL = null;
let robotChatShaderProgram = null;
let robotChatAnimationId = null;

window.initRobotChatShaderBackground = function() {
    robotChatShaderCanvas = document.getElementById('robot-chat-shader-canvas');
    if (!robotChatShaderCanvas) return;

    robotChatShaderGL = robotChatShaderCanvas.getContext('webgl');
    if (!robotChatShaderGL) {
        console.error('❌ WebGL not supported in this browser');
        robotChatShaderCanvas.style.background = 'linear-gradient(135deg, rgba(13, 13, 51, 0.8) 0%, rgba(76, 29, 149, 0.8) 50%, rgba(30, 64, 175, 0.8) 100%)';
        return;
    }
    
    console.log('✅ WebGL context created successfully');

    // Simple vertex shader
    const vsSource = `
        attribute vec4 aVertexPosition;
        void main() {
            gl_Position = aVertexPosition;
        }
    `;

    // Beautiful flowing waves shader like in the image
    const fsSource = `
        precision highp float;
        uniform vec2 iResolution;
        uniform float iTime;

        // Function to create smooth flowing lines
        float sdLine(vec2 p, vec2 a, vec2 b) {
            vec2 pa = p - a;
            vec2 ba = b - a;
            float h = clamp(dot(pa, ba) / dot(ba, ba), 0.0, 1.0);
            return length(pa - ba * h);
        }

        // Create flowing wave paths - optimized for chat container
        vec2 getWavePoint(float t, float offset, float amplitude) {
            float x = t * 0.5; // Slower horizontal movement
            float y = sin(t * 2.0 + iTime * 1.8 + offset) * amplitude + 
                     sin(t * 5.0 + iTime * 1.2 + offset * 1.5) * amplitude * 0.4 +
                     sin(t * 8.0 + iTime * 0.8 + offset * 0.8) * amplitude * 0.2;
            return vec2(x, y);
        }

        void main() {
            // Adjust UV for chat container aspect ratio
            vec2 uv = gl_FragCoord.xy / iResolution.xy;
            uv = (uv - 0.5) * 2.0;
            
            // Scale for better wave visibility in narrow containers
            uv.x *= iResolution.x / iResolution.y; // Adjust for aspect ratio
            uv *= 0.8; // Scale down for better wave visibility
            
            // Dark purple to blue gradient background
            vec3 bgColor = mix(vec3(0.02, 0.02, 0.15), vec3(0.08, 0.02, 0.25), uv.y * 0.5 + 0.5);
            vec3 finalColor = bgColor;
            
            // Create multiple flowing wave lines - optimized for chat
            for(int i = 0; i < 6; i++) {
                float offset = float(i) * 1.2;
                float amplitude = 0.4 + sin(iTime * 0.3 + offset) * 0.15;
                
                // Create flowing line segments with better spacing
                for(float t = -3.0; t < 3.0; t += 0.15) {
                    vec2 p1 = getWavePoint(t, offset, amplitude);
                    vec2 p2 = getWavePoint(t + 0.15, offset, amplitude);
                    
                    float dist = sdLine(uv, p1, p2);
                    float intensity = 1.0 / (1.0 + dist * 30.0); // Softer glow
                    
                    // Enhanced purple to cyan gradient
                    vec3 lineColor = mix(
                        vec3(0.6, 0.1, 1.0),  // Brighter Purple
                        vec3(0.1, 0.9, 1.0),  // Brighter Cyan
                        sin(t * 0.5 + iTime * 0.8 + offset) * 0.5 + 0.5
                    );
                    
                    // Enhanced glow effect
                    intensity *= (0.6 + sin(iTime * 1.5 + offset + t * 3.0) * 0.4);
                    finalColor += lineColor * intensity * 0.5; // Brighter lines
                }
            }
            
            // Add some sparkle effects
            vec2 sparkleUV = uv * 10.0;
            float sparkle = sin(sparkleUV.x * 15.0 + iTime * 3.0) * sin(sparkleUV.y * 15.0 + iTime * 2.0);
            sparkle = smoothstep(0.9, 1.0, sparkle);
            finalColor += vec3(0.8, 0.6, 1.0) * sparkle * 0.2;
            
            gl_FragColor = vec4(finalColor, 1.0);
        }
    `;

    // Create and compile shaders
    function createShader(type, source) {
        const shader = robotChatShaderGL.createShader(type);
        robotChatShaderGL.shaderSource(shader, source);
        robotChatShaderGL.compileShader(shader);
        if (!robotChatShaderGL.getShaderParameter(shader, robotChatShaderGL.COMPILE_STATUS)) {
            console.error('❌ Shader compile error:', robotChatShaderGL.getShaderInfoLog(shader));
            console.error('Shader source:', source);
            return null;
        }
        console.log('✅ Shader compiled successfully:', type === robotChatShaderGL.VERTEX_SHADER ? 'VERTEX' : 'FRAGMENT');
        return shader;
    }

    const vertexShader = createShader(robotChatShaderGL.VERTEX_SHADER, vsSource);
    const fragmentShader = createShader(robotChatShaderGL.FRAGMENT_SHADER, fsSource);

    if (!vertexShader || !fragmentShader) {
        robotChatShaderCanvas.style.background = 'linear-gradient(135deg, rgba(13, 13, 51, 0.8) 0%, rgba(76, 29, 149, 0.8) 50%, rgba(30, 64, 175, 0.8) 100%)';
        return;
    }

    // Create program
    robotChatShaderProgram = robotChatShaderGL.createProgram();
    robotChatShaderGL.attachShader(robotChatShaderProgram, vertexShader);
    robotChatShaderGL.attachShader(robotChatShaderProgram, fragmentShader);
    robotChatShaderGL.linkProgram(robotChatShaderProgram);

    if (!robotChatShaderGL.getProgramParameter(robotChatShaderProgram, robotChatShaderGL.LINK_STATUS)) {
        console.error('❌ Program link error:', robotChatShaderGL.getProgramInfoLog(robotChatShaderProgram));
        robotChatShaderCanvas.style.background = 'linear-gradient(135deg, rgba(13, 13, 51, 0.8) 0%, rgba(76, 29, 149, 0.8) 50%, rgba(30, 64, 175, 0.8) 100%)';
        return;
    }
    
    console.log('✅ Shader program linked successfully');

    // Setup geometry
    const positions = [-1, -1, 1, -1, -1, 1, 1, 1];
    const positionBuffer = robotChatShaderGL.createBuffer();
    robotChatShaderGL.bindBuffer(robotChatShaderGL.ARRAY_BUFFER, positionBuffer);
    robotChatShaderGL.bufferData(robotChatShaderGL.ARRAY_BUFFER, new Float32Array(positions), robotChatShaderGL.STATIC_DRAW);

    const positionLocation = robotChatShaderGL.getAttribLocation(robotChatShaderProgram, 'aVertexPosition');
    const resolutionLocation = robotChatShaderGL.getUniformLocation(robotChatShaderProgram, 'iResolution');
    const timeLocation = robotChatShaderGL.getUniformLocation(robotChatShaderProgram, 'iTime');

    // Resize function - now updates dynamically
    function resizeCanvas() {
        const messagesContainer = document.getElementById('robotChatMessages');
        if (messagesContainer) {
            robotChatShaderCanvas.width = messagesContainer.clientWidth;
            robotChatShaderCanvas.height = messagesContainer.scrollHeight; // Use scrollHeight for dynamic content
            robotChatShaderGL.viewport(0, 0, robotChatShaderCanvas.width, robotChatShaderCanvas.height);
        }
    }

    // Observe messages container for changes
    const messagesContainer = document.getElementById('robotChatMessages');
    if (messagesContainer) {
        const observer = new MutationObserver(resizeCanvas);
        observer.observe(messagesContainer, { childList: true, subtree: true });
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    // Animation loop
    const startTime = Date.now();
    function animate() {
        const currentTime = (Date.now() - startTime) / 1000;

        robotChatShaderGL.clearColor(0.1, 0.1, 0.3, 0.3);
        robotChatShaderGL.clear(robotChatShaderGL.COLOR_BUFFER_BIT);

        robotChatShaderGL.useProgram(robotChatShaderProgram);
        robotChatShaderGL.uniform2f(resolutionLocation, robotChatShaderCanvas.width, robotChatShaderCanvas.height);
        robotChatShaderGL.uniform1f(timeLocation, currentTime);

        robotChatShaderGL.bindBuffer(robotChatShaderGL.ARRAY_BUFFER, positionBuffer);
        robotChatShaderGL.vertexAttribPointer(positionLocation, 2, robotChatShaderGL.FLOAT, false, 0, 0);
        robotChatShaderGL.enableVertexAttribArray(positionLocation);

        robotChatShaderGL.drawArrays(robotChatShaderGL.TRIANGLE_STRIP, 0, 4);
        robotChatAnimationId = requestAnimationFrame(animate);
    }

    console.log('🎨 Shader background animation started!');
    animate();
};

window.stopRobotChatShaderBackground = function() {
    if (robotChatAnimationId) {
        cancelAnimationFrame(robotChatAnimationId);
        robotChatAnimationId = null;
    }
};
</script>
