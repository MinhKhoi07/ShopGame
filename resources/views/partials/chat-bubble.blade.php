<!-- Chat Bubble Widget - Chèn ở cuối trang -->
<div id="chat-bubble-widget">
    <!-- Chat Bubble Icon -->
    <div id="chat-bubble-toggle" class="chat-bubble-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span id="unread-badge" class="unread-badge" style="display: none;">0</span>
    </div>

    <!-- Chat Box -->
    <div id="chat-box" class="chat-box" style="display: none;">
        <div class="chat-header">
            <div class="chat-title">
                <h3>Hỗ trợ khách hàng</h3>
                <p class="text-sm text-gray-500">Gửi tin nhắn cho chúng tôi</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                @auth
                <button id="chat-refresh" class="chat-refresh-btn" title="Tải lại tin nhắn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </button>
                @endauth
                <button id="chat-close" class="chat-close-btn">×</button>
            </div>
        </div>

        <!-- Auth Check -->
        @if (!Auth::check())
            <div class="chat-login-message">
                <p>Vui lòng <a href="{{ route('login') }}" style="color: #2563eb; text-decoration: underline;">đăng nhập</a> để gửi tin nhắn</p>
            </div>
        @else
            <div class="chat-messages" id="chat-messages-container">
                <div class="chat-placeholder">
                    <p>Chào {{ Auth::user()->name }}! 👋</p>
                    <p class="text-sm" style="margin-top: 8px;">Gửi tin nhắn hỗ trợ cho chúng tôi</p>
                    <p class="text-sm" style="margin-top: 4px; color: #6b7280;">Admin sẽ phản hồi khi nhận được tin nhắn</p>
                </div>
            </div>

            <div class="chat-input-area">
                <form id="chat-form">
                    <div style="display: flex; gap: 8px;">
                        <input 
                            type="text" 
                            id="chat-message-input" 
                            class="chat-input" 
                            placeholder="Nhập tin nhắn..." 
                            maxlength="1000"
                            required
                        >
                        <button type="submit" class="chat-send-btn" id="chat-send-btn">Gửi</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<style>
    /* Chat Bubble Styles */
    #chat-bubble-widget {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }

    .chat-bubble-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        position: relative;
    }

    .chat-bubble-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .chat-bubble-icon svg {
        width: 24px;
        height: 24px;
    }

    .unread-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 12px;
        font-weight: bold;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    .chat-box {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 400px;
        max-height: 600px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .chat-title h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chat-title p {
        margin: 4px 0 0 0;
        font-size: 13px;
        opacity: 0.9;
        font-weight: 500;
    }

    .chat-close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-refresh-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        cursor: pointer;
        padding: 6px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .chat-refresh-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .chat-refresh-btn:active {
        transform: rotate(180deg);
    }

    .chat-login-message {
        padding: 20px;
        text-align: center;
        color: #1f2937;
        font-size: 14px;
    }

    .chat-login-message p {
        margin: 0;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #f9fafb;
        min-height: 400px;
        max-height: 450px;
    }

    .chat-placeholder {
        text-align: center;
        color: #1f2937;
        padding: 20px 0;
    }

    .chat-placeholder p {
        margin: 8px 0;
        font-size: 14px;
        line-height: 1.5;
        color: #374151;
    }

    .chat-placeholder p:first-child {
        font-weight: 700;
        color: #111827;
        font-size: 16px;
    }

    .chat-message {
        display: flex;
        margin-bottom: 12px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-message.from-user {
        justify-content: flex-end;
    }

    .chat-message.from-admin {
        justify-content: flex-start;
    }

    .chat-message-content {
        max-width: 70%;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .chat-message.from-user .chat-message-content {
        background: #667eea;
        color: white;
        border-bottom-right-radius: 2px;
    }

    .chat-message.from-admin .chat-message-content {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 2px;
    }

    .chat-message-time {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .chat-input-area {
        padding: 12px;
        background: white;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 12px 12px;
    }

    .chat-input {
        flex: 1;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        background: white;
    }

    .chat-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .chat-input::placeholder {
        color: #6b7280;
        font-weight: 500;
    }

    .chat-send-btn {
        padding: 10px 20px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .chat-send-btn:hover:not(:disabled) {
        background: #5568d3;
    }

    .chat-send-btn:active:not(:disabled) {
        transform: scale(0.98);
    }

    .chat-send-btn:disabled {
        background: #a5a5a5;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .text-sm {
        font-size: 13px;
    }

    .text-gray-500 {
        color: rgba(255,255,255,0.85);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .chat-box {
            width: calc(100vw - 40px);
            right: 20px;
            max-height: 500px;
        }

        .chat-message-content {
            max-width: 85%;
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBubbleToggle = document.getElementById('chat-bubble-toggle');
        const chatBox = document.getElementById('chat-box');
        const chatCloseBtn = document.getElementById('chat-close');
        const chatRefreshBtn = document.getElementById('chat-refresh');
        const chatForm = document.getElementById('chat-form');
        const messagesContainer = document.getElementById('chat-messages-container');

        // Attach form submit listener
        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                sendChatMessage(e);
            });
        }

        // Toggle chat box
        if (chatBubbleToggle) {
            chatBubbleToggle.addEventListener('click', function() {
                const isVisible = chatBox.style.display === 'flex';
                chatBox.style.display = isVisible ? 'none' : 'flex';
                if (!isVisible) {
                    // Chỉ load tin nhắn khi mở chat
                    loadChatMessages();
                }
            });
        }

        // Close chat box
        if (chatCloseBtn) {
            chatCloseBtn.addEventListener('click', function() {
                chatBox.style.display = 'none';
            });
        }

        // Refresh messages
        if (chatRefreshBtn) {
            chatRefreshBtn.addEventListener('click', function() {
                loadChatMessages();
                showNotification('Đã tải lại tin nhắn', 'info');
            });
        }

        console.log('Chat widget initialized');
    });

    let isSending = false; // Biến để ngăn gửi nhiều lần

    function sendChatMessage(event) {
        event.preventDefault();
        
        // Ngăn gửi nhiều lần
        if (isSending) {
            console.log('Already sending, ignoring duplicate request');
            return;
        }
        
        const input = document.getElementById('chat-message-input');
        const message = input.value.trim();

        if (!message) {
            alert('Vui lòng nhập tin nhắn');
            return;
        }

        const btn = document.getElementById('chat-send-btn');
        console.log('Sending message:', message);
        const originalText = btn.textContent;
        
        // Đánh dấu đang gửi
        isSending = true;
        btn.disabled = true;
        btn.textContent = 'Đang gửi...';
        
        // Dùng đường dẫn tương đối để tránh sai host (APP_URL) khi chạy artisan serve
        const apiUrl = '/api/messages';
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
                        console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
                        console.log('Response data:', data);
            if (data.success) {
                input.value = '';
                btn.textContent = originalText;
                btn.disabled = false;
                isSending = false;
                // Load lại tin nhắn sau khi gửi thành công
                loadChatMessages();
                // Hiển thị thông báo thành công
                showNotification('Tin nhắn đã được gửi! Admin sẽ phản hồi sớm nhất có thể.', 'success');
            } else {
                                console.error('API returned error:', data.error);
                alert(data.error || 'Lỗi khi gửi tin nhắn');
                btn.textContent = originalText;
                btn.disabled = false;
                isSending = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
                        console.error('Stack:', error.stack);
            alert('Không thể gửi tin nhắn. Vui lòng thử lại!');
            btn.textContent = originalText;
            btn.disabled = false;
            isSending = false;
        });
    }

    function loadChatMessages() {
        const messagesContainer = document.getElementById('chat-messages-container');
        if (!messagesContainer) return;

        // Dùng đường dẫn tương đối để đảm bảo cùng origin
        fetch('/api/messages')
            .then(response => response.json())
            .then(data => {
                if (data.messages) {
                    const hasMessages = data.messages.length > 0;
                    const unreadCount = data.messages.filter(m => m.is_from_admin && !m.is_read).length;

                    // Update chat messages
                    if (hasMessages) {
                        // Xóa tất cả nội dung cũ (bao gồm placeholder)
                        messagesContainer.innerHTML = '';
                        
                        // Thêm tin nhắn mới
                        messagesContainer.innerHTML = data.messages.map(msg => `
                            <div class="chat-message ${msg.is_from_admin ? 'from-admin' : 'from-user'}">
                                <div>
                                    <div class="chat-message-content">${escapeHtml(msg.message)}</div>
                                    <div class="chat-message-time">${msg.created_at}</div>
                                </div>
                            </div>
                        `).join('');
                        
                        // Scroll to bottom
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }

                    // Update badge
                    const badge = document.getElementById('unread-badge');
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'chat-notification ' + type;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 300px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
