<div id="chat-widget">
    <!-- Bong bóng tròn nhỏ -->
    <div id="chat-toggle" class="chat-bubble shadow position-relative">
        💬
        <span id="chat-notify" class="notify-dot d-none"></span>
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
    background-color: #007bff;
    color: #fff;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    cursor: pointer;
    transition: 0.3s;
}
.chat-bubble:hover { background-color: #0056b3; }

.chat-box {
    width: 320px;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    position: absolute;
    bottom: 70px;
    right: 0;
}
.hidden { display: none; }

.notify-dot {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 12px;
    height: 12px;
    background: red;
    border-radius: 50%;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    const chatBox = $('#chat-box');
    const toggleBtn = $('#chat-toggle');
    const closeBtn = $('#chat-close');
    const receiverId = $('#receiver_id').val();
    const messagesDiv = $('#chat-messages');
    const notifyDot = $('#chat-notify');
    let lastMessageCount = 0;

    // Cho phép hiển thị thông báo trình duyệt
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Toggle chat box
    toggleBtn.on('click', () => {
        chatBox.toggleClass('hidden');
        notifyDot.addClass('d-none'); // ẩn chấm đỏ khi mở chat
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

    // Load tin nhắn
    function loadMessages() {
        $.ajax({
            url: "/chat/" + receiverId,
            method: "GET",
            success: function(messages) {
                // Kiểm tra tin nhắn mới
                if (messages.length > lastMessageCount && !chatBox.is(':visible')) {
                    notifyDot.removeClass('d-none'); // hiện chấm đỏ
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

    // Tải tin nhắn định kỳ
    setInterval(loadMessages, 2000);
    loadMessages();
});
</script>
