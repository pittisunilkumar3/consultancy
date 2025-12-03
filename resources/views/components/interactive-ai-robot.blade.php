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
    cursor: move;
    z-index: 10000;
    animation: premiumRobotEntrance 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    opacity: 0;
    transform: translateY(50px) scale(0.6) rotateY(-20deg);
    perspective: 1000px;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    transition: filter 0.2s ease;
}

.ai-robot-character.dragging {
    cursor: grabbing;
    filter: brightness(1.1);
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

/* Removed unused expandable chat input styles - chat opens directly to sidebar */

/* Full Chat Interface */
.robot-chat-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0);
    opacity: 0;
    visibility: hidden;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 999998;
    backdrop-filter: blur(0px) brightness(1);
    -webkit-backdrop-filter: blur(0px) brightness(1);
}

.robot-chat-overlay.show,
.robot-chat-overlay.open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    backdrop-filter: blur(8px) brightness(0.7) saturate(1.2);
    -webkit-backdrop-filter: blur(8px) brightness(0.7) saturate(1.2);
    background: rgba(15, 23, 42, 0.5);
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
    overflow: hidden;
}

.robot-chat-sidebar.open {
    transform: translateX(0);
}

.robot-chat-header {
    padding: 20px 25px 25px 25px;
    background: #eaefefff;
    color: rgba(65, 55, 81, 1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    border-radius: 0 0 25px 25px;
    box-shadow: 0 4px 20px rgba(102, 210, 234, 0.3);
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
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(102, 210, 234, 0.2);
    border: 1px solid rgba(102, 210, 234, 0.2);
    transform: translateY(10px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000002;
    backdrop-filter: blur(10px);
}

.quick-questions-popup:not(.collapsed) {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.quick-questions-popup-header {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(102, 210, 234, 0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    background: linear-gradient(135deg, rgba(240, 249, 255, 0.8) 0%, rgba(224, 242, 254, 0.8) 100%);
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
    background: linear-gradient(135deg, rgba(240, 249, 255, 0.95) 0%, rgba(224, 242, 254, 0.95) 50%, rgba(240, 253, 244, 0.95) 100%);
    display: flex;
    flex-direction: column;
    margin-bottom: 0;
    border-radius: 25px 25px 0 0;
    margin-top: -25px;
    padding-top: 45px;
    position: relative;
    z-index: 1;
    /* Hide scrollbar but keep scroll functionality */
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
    /* Mask to make messages go under header */
    -webkit-mask: linear-gradient(to bottom, transparent 0px, black 25px, black 100%);
    mask: linear-gradient(to bottom, transparent 0px, black 25px, black 100%);
}

.robot-chat-messages::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

/* Aurora Background - Fixed position wrapper */
.aurora-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.aurora-layer {
    position: absolute;
    inset: -10px;
    opacity: 0.6;
    will-change: transform;
    pointer-events: none;
    
    /* Define CSS custom properties for colors - matching header gradient */
    --white: rgba(255, 255, 255, 0.15);
    --transparent: rgba(255, 255, 255, 0);
    --cyan-400: rgba(102, 210, 234, 0.4);
    --teal-300: rgba(94, 234, 212, 0.3);
    --green-400: rgba(75, 162, 82, 0.4);
    --blue-400: rgba(102, 210, 234, 0.3);
    --emerald-300: rgba(110, 231, 183, 0.3);
    
    /* Aurora gradient patterns */
    background-image: 
        repeating-linear-gradient(100deg, var(--white) 0%, var(--white) 7%, var(--transparent) 10%, var(--transparent) 12%, var(--white) 16%),
        repeating-linear-gradient(100deg, var(--cyan-400) 10%, var(--teal-300) 15%, var(--green-400) 20%, var(--emerald-300) 25%, var(--blue-400) 30%);
    
    background-size: 300%, 200%;
    background-position: 50% 50%, 50% 50%;
    
    filter: blur(10px);
    animation: aurora 20s ease-in-out infinite;
}

.aurora-layer::after {
    content: "";
    position: absolute;
    inset: 0;
    background-image: 
        repeating-linear-gradient(100deg, var(--white) 0%, var(--white) 7%, var(--transparent) 10%, var(--transparent) 12%, var(--white) 16%),
        repeating-linear-gradient(100deg, var(--cyan-400) 10%, var(--teal-300) 15%, var(--green-400) 20%, var(--emerald-300) 25%, var(--blue-400) 30%);
    
    background-size: 200%, 100%;
    background-attachment: fixed;
    mix-blend-mode: difference;
    animation: aurora-reverse 25s ease-in-out infinite;
}

@keyframes aurora {
    0%, 100% {
        background-position: 50% 50%, 50% 50%;
        transform: rotate(0deg);
    }
    25% {
        background-position: 0% 100%, 100% 0%;
        transform: rotate(1deg);
    }
    50% {
        background-position: 100% 0%, 0% 100%;
        transform: rotate(0deg);
    }
    75% {
        background-position: 0% 0%, 100% 100%;
        transform: rotate(-1deg);
    }
}

@keyframes aurora-reverse {
    0%, 100% {
        background-position: 100% 100%, 0% 0%;
        transform: rotate(0deg);
    }
    25% {
        background-position: 0% 0%, 100% 100%;
        transform: rotate(-1deg);
    }
    50% {
        background-position: 100% 100%, 0% 0%;
        transform: rotate(0deg);
    }
    75% {
        background-position: 50% 50%, 50% 50%;
        transform: rotate(1deg);
    }
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
    background: linear-gradient(135deg, rgba(240, 249, 255, 0.95) 0%, rgba(224, 242, 254, 0.95) 100%);
    border-top: 1px solid rgba(102, 210, 234, 0.2);
    box-shadow: 0 -2px 10px rgba(102, 210, 234, 0.1);
    display: flex;
    justify-content: center;
    position: relative;
    z-index: 1000000;
    min-height: 90px;
    backdrop-filter: blur(10px);
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
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(102, 210, 234, 0.3);
    border-radius: 25px;
    padding: 6px 15px;
    box-shadow: 0 5px 20px rgba(102, 210, 234, 0.2);
    cursor: default;
    align-items: center;
    min-height: 56px;
    backdrop-filter: blur(10px);
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
    box-shadow: 0 5px 25px rgba(102, 210, 234, 0.4);
    background: rgba(255, 255, 255, 0.95);
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
        bottom: 5px; /* Move up from bottom */
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

    <!-- Expandable Chat Input removed - using sidebar chat only -->

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

        <!-- Aurora Background - Fixed behind everything -->
        <div class="aurora-background">
            <div class="aurora-layer"></div>
        </div>
        
        <div class="robot-chat-messages" id="robotChatMessages">
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
let speechTimeout;
let isDragging = false;
let currentX;
let currentY;
let initialX;
let initialY;
let xOffset = 0;
let yOffset = 0;

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

// Removed unused expandable input functions - chat opens directly to sidebar

// Drag functionality for robot face
function dragStart(e) {
    if (robotChatOpen) return; // Don't drag when chat is open
    
    if (e.type === "touchstart") {
        initialX = e.touches[0].clientX - xOffset;
        initialY = e.touches[0].clientY - yOffset;
    } else {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;
    }

    if (e.target.closest('#aiRobotCharacter')) {
        isDragging = true;
        document.getElementById('aiRobotCharacter').classList.add('dragging');
    }
}

function drag(e) {
    if (isDragging) {
        e.preventDefault();
        
        if (e.type === "touchmove") {
            currentX = e.touches[0].clientX - initialX;
            currentY = e.touches[0].clientY - initialY;
        } else {
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
        }

        xOffset = currentX;
        yOffset = currentY;

        setTranslate(currentX, currentY, document.getElementById('aiRobotCharacter'));
    }
}

function dragEnd(e) {
    if (isDragging) {
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
        document.getElementById('aiRobotCharacter').classList.remove('dragging');
    }
}

function setTranslate(xPos, yPos, el) {
    // Calculate position from bottom-right corner
    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;
    
    // Original position: 30px from right, 30px from bottom
    const newRight = 30 - xPos;
    const newBottom = 30 - yPos;
    
    el.style.right = newRight + 'px';
    el.style.bottom = newBottom + 'px';
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
    
    // Show robot face again
    document.getElementById('aiRobotCharacter').style.display = 'block';
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

// Sidebar chat input is always expanded - no toggle needed

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
    
    const robotCharacter = document.getElementById('aiRobotCharacter');
    
    // Drag event listeners
    robotCharacter.addEventListener('mousedown', dragStart);
    robotCharacter.addEventListener('touchstart', dragStart);
    
    document.addEventListener('mousemove', drag);
    document.addEventListener('touchmove', drag, { passive: false });
    
    document.addEventListener('mouseup', dragEnd);
    document.addEventListener('touchend', dragEnd);
    
    // Robot character click - opens full chat directly (only if not dragging)
    let clickStartTime;
    let clickStartX;
    let clickStartY;
    
    robotCharacter.addEventListener('mousedown', function(e) {
        clickStartTime = Date.now();
        clickStartX = e.clientX;
        clickStartY = e.clientY;
    });
    
    robotCharacter.addEventListener('mouseup', function(e) {
        const clickDuration = Date.now() - clickStartTime;
        const moveDistance = Math.sqrt(
            Math.pow(e.clientX - clickStartX, 2) + 
            Math.pow(e.clientY - clickStartY, 2)
        );
        
        // Only open chat if it was a quick click and minimal movement (not a drag)
        if (clickDuration < 300 && moveDistance < 10 && !robotChatOpen) {
            openChatDirectly();
        }
    });
    
    // Robot hover effects
    robotCharacter.addEventListener('mouseenter', function() {
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
    
    // Auto-change robot messages
    setInterval(() => {
        if (!robotChatOpen) {
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

