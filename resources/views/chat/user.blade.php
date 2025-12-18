<div id="chat-widget">
    <!-- Bong bóng tròn nhỏ -->
    <div id="chat-toggle" class="chat-bubble shadow position-relative">
        💬
        <span id="chat-notify" class="notify-badge d-none"></span>
    </div>

    <!-- Hộp chat -->
    <div id="chat-box" class="chat-box shadow hidden">
        <div class="chat-header bg-primary text-white d-flex justify-content-between align-items-center p-2">
            <span>Chat với Admin</span>
            <button id="chat-close" class="btn btn-sm btn-light">&times;</button>
        </div>
        <div id="chat-messages" class="p-2 bg-light" style="height: 300px; overflow-y: auto;">
            <p class="text-muted text-center">Đang tải tin nhắn...</p>
        </div>
        <form id="chat-form" class="d-flex border-top">
            @csrf
            <input type="hidden" id="receiver_id" value="{{ $receiverId ?? '' }}">
            <input type="text" id="message" class="form-control border-0" placeholder="Nhập tin nhắn...">
            <button class="btn btn-primary" type="submit">Gửi</button>
        </form>
    </div>
</div>

<style>
#chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.chat-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}
.chat-bubble:hover { 
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.chat-box {
    width: 350px;
    background: white;
    border-radius: 16px;
    overflow: hidden;
    position: absolute;
    bottom: 75px;
    right: 0;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    border: none;
}
.hidden { display: none; }

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    padding: 12px 16px !important;
}

.notify-badge {
    position: absolute;
    top: 6px;
    right: 6px;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    background: #f5576c;
    border-radius: 999px;
    border: 2px solid white;
    font-size: 12px;
    font-weight: 700;
    line-height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const chatBox = $('#chat-box');
    const toggleBtn = $('#chat-toggle');
    const closeBtn = $('#chat-close');
    let receiverId = $('#receiver_id').val();
    const messagesDiv = $('#chat-messages');
    const notifyDot = $('#chat-notify');
    let lastMessageCount = 0;

    const unreadUrlTemplate = "{{ route('chat.unread_count', ['senderId' => '__SID__']) }}";
    const messagesUrlTemplate = "{{ route('chat.get', ['receiverId' => '__RID__']) }}";

    // Cho phép hiển thị thông báo trình duyệt
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Toggle chat box
    toggleBtn.on('click', () => {
        chatBox.toggleClass('hidden');
        notifyDot.text('').addClass('d-none');
        if (!chatBox.hasClass('hidden')) {
            receiverId = $('#receiver_id').val();
            loadMessages();
        }
    });

    closeBtn.on('click', () => chatBox.addClass('hidden'));

    // Gửi tin nhắn
    $('#chat-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('chat.send') }}",
            method: "POST",
            data: {
                _token: $('input[name=_token]').val(),
                receiver_id: receiverId,
                message: $('#message').val(),
            },
            success: function() {
                $('#message').val('');
                loadMessages();
            }
        });
    });

    function updateUnreadBadge() {
        receiverId = $('#receiver_id').val();
        if (!receiverId) {
            notifyDot.text('').addClass('d-none');
            return;
        }
        const url = unreadUrlTemplate.replace('__SID__', receiverId);
        $.get(url, function (res) {
            const count = res && typeof res.count !== 'undefined' ? Number(res.count) : 0;
            if (count > 0 && chatBox.hasClass('hidden')) {
                notifyDot.text(String(count)).removeClass('d-none');
            } else {
                notifyDot.text('').addClass('d-none');
            }
        });
    }

    // Load tin nhắn
    function loadMessages() {
        receiverId = $('#receiver_id').val();
        if (!receiverId) {
            messagesDiv.html('<p class="text-danger text-center mb-0">Không tìm thấy Admin để chat. Vui lòng đăng nhập lại hoặc liên hệ quản trị.</p>');
            return;
        }
        $.ajax({
            url: messagesUrlTemplate.replace('__RID__', receiverId),
            method: "GET",
            success: function(messages) {
                // Kiểm tra tin nhắn mới
                if (messages.length > lastMessageCount && chatBox.hasClass('hidden')) {
                    updateUnreadBadge();
                    playNotificationSound();
                    showBrowserNotification('Bạn có tin nhắn mới từ Admin!');
                }
                lastMessageCount = messages.length;

                // Hiển thị tin nhắn
                messagesDiv.html('');
                messages.forEach(function(msg) {
                    let isMine = msg.sender_id == {{ Auth::id() }};
                    let msgAlign = isMine ? 'text-end text-primary' : 'text-start text-dark';
                    let sender = isMine ? 'Bạn' : 'Admin';
                    messagesDiv.append(`<p class="${msgAlign}"><strong>${sender}:</strong> ${msg.message}</p>`);
                });
                messagesDiv.scrollTop(messagesDiv[0].scrollHeight);
            },
            error: function (xhr) {
                const status = xhr && xhr.status ? String(xhr.status) : '';
                let text = 'Không tải được tin nhắn.';
                if (status === '401') {
                    text = 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.';
                } else if (status === '403') {
                    text = 'Bạn không có quyền chat. Vui lòng kiểm tra quyền tài khoản.';
                }
                messagesDiv.html('<p class="text-danger text-center mb-0">' + text + '</p>');
            }
        });
    }

    // Âm thanh thông báo
    function playNotificationSound() {
        const audio = new Audio('/sounds/notify.mp3');
        audio.play().catch(() => {});
    }

    // Thông báo trình duyệt
    function showBrowserNotification(text) {
        if (Notification.permission === "granted") {
            new Notification("Tin nhắn mới", { body: text });
        }
    }

    // Tải tin nhắn / unread định kỳ
    setInterval(function () {
        if (!chatBox.hasClass('hidden')) {
            loadMessages();
        } else {
            updateUnreadBadge();
        }
    }, 2000);

    updateUnreadBadge();
});
</script>
