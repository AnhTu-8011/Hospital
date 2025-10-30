<div id="admin-chat-widget">
    <!-- Bong bóng tròn -->
    <div id="admin-chat-toggle" class="chat-bubble shadow position-relative">
        💬
        <span id="chat-notify" class="notify-dot d-none"></span>
    </div>

    <!-- Hộp chat -->
    <div id="admin-chat-box" class="chat-box shadow hidden">
        <div class="chat-header bg-primary text-white d-flex justify-content-between align-items-center p-2">
            <span>Chat với người dùng</span>
            <button id="admin-chat-close" class="btn btn-sm btn-light">&times;</button>
        </div>
        <div class="row g-0">
            <div class="col-4 border-end bg-light" style="height: 300px; overflow-y: auto;">
                <ul class="list-group list-group-flush" id="user-list">
                    @foreach($users as $user)
                        <li class="list-group-item user-item" data-id="{{ $user->id }}" style="cursor:pointer;">
                            {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-8 d-flex flex-column">
                <div id="chat-box" class="flex-grow-1 p-2 bg-white" style="overflow-y: auto;">
                    <p class="text-muted text-center">Chọn người dùng để chat</p>
                </div>
                <form id="chat-form" class="border-top d-flex p-2 d-none">
                    @csrf
                    <input type="hidden" id="receiver_id">
                    <input type="text" id="message" class="form-control me-2" placeholder="Nhập tin nhắn...">
                    <button class="btn btn-primary" type="submit">Gửi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
#admin-chat-widget {
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
    width: 500px;
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
    const chatBox = $('#admin-chat-box');
    const toggleBtn = $('#admin-chat-toggle');
    const closeBtn = $('#admin-chat-close');
    const messagesDiv = $('#chat-box');
    const notifyDot = $('#chat-notify');
    let receiverId = null;
    let lastMessageCount = 0;

    // Cho phép hiển thị thông báo trình duyệt
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    // Mở / đóng bong bóng
    toggleBtn.on('click', () => {
        chatBox.toggleClass('hidden');
        notifyDot.addClass('d-none'); // Ẩn chấm đỏ khi mở chat
    });
    closeBtn.on('click', () => chatBox.addClass('hidden'));

    // Chọn người dùng
    $(document).on('click', '.user-item', function() {
        receiverId = $(this).data('id');
        $('#receiver_id').val(receiverId);
        $('#chat-form').removeClass('d-none');
        $('.user-item').removeClass('active');
        $(this).addClass('active');
        loadMessages();
    });

    // Gửi tin nhắn
    $('#chat-form').submit(function(e) {
        e.preventDefault();
        if (!receiverId) return;
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
        if (!receiverId) return;
        $.ajax({
            url: "/chat/" + receiverId,
            method: "GET",
            success: function(messages) {
                // Nếu có tin mới
                if (messages.length > lastMessageCount && !chatBox.is(':visible')) {
                    notifyDot.removeClass('d-none');
                    playNotificationSound();
                    showBrowserNotification('Bạn có tin nhắn mới!');
                }
                lastMessageCount = messages.length;

                // Hiển thị tin nhắn
                messagesDiv.html('');
                messages.forEach(function(msg) {
                    let isMine = msg.sender_id == {{ Auth::id() }};
                    let msgClass = isMine ? 'text-end text-primary' : 'text-start text-dark';
                    let name = isMine ? 'Admin' : 'Người dùng';
                    messagesDiv.append(`<p class="${msgClass}"><strong>${name}:</strong> ${msg.message}</p>`);
                });
                messagesDiv.scrollTop(messagesDiv[0].scrollHeight);
            }
        });
    }

    // Phát âm thanh thông báo
    function playNotificationSound() {
        const audio = new Audio('/sounds/notify.mp3');
        audio.play().catch(() => {});
    }

    // Hiển thị thông báo trình duyệt
    function showBrowserNotification(text) {
        if (Notification.permission === "granted") {
            new Notification("Tin nhắn mới từ người dùng", { body: text });
        }
    }

    // Tự động load mỗi 2 giây
    setInterval(loadMessages, 2000);
});
</script>
