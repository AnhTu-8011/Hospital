{{-- Chat Widget Container --}}
<div id="chat-widget">
    {{-- Chat Toggle Bubble --}}
    <div id="chat-toggle" class="chat-bubble shadow position-relative">
        💬
        <span id="chat-notify" class="notify-badge d-none"></span>
    </div>

    {{-- Chat Box --}}
    <div id="chat-box" class="chat-box shadow hidden">
        {{-- Chat Header --}}
        <div class="chat-header bg-primary text-white d-flex justify-content-between align-items-center p-2">
            <span>Chat với Admin</span>
            <button id="chat-close" class="btn btn-sm btn-light">&times;</button>
        </div>

        {{-- Chat Messages Area --}}
        <div id="chat-messages" class="p-2 bg-light" style="height: 300px; overflow-y: auto;">
            <p class="text-muted text-center">Đang tải tin nhắn...</p>
        </div>

        {{-- Chat Form --}}
        <form id="chat-form" class="d-flex border-top" data-user-id="{{ Auth::id() ?? '' }}">
            @csrf
            <input type="hidden" id="receiver_id" value="{{ $receiverId ?? '' }}">
            <input type="text"
                   id="message"
                   class="form-control border-0"
                   placeholder="Nhập tin nhắn...">
            <button class="btn btn-primary" type="submit">Gửi</button>
        </form>
    </div>
</div>

{{-- Chat Widget Styles --}}
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
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        border: none;
    }

    .hidden {
        display: none;
    }

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
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.8;
        }
    }
</style>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Chat Widget Config --}}
<script>
    // Truyền config từ Blade vào JavaScript
    window.chatUserConfig = {
        routes: {
            send: "{{ route('chat.send') }}",
            unreadCount: "{{ route('chat.unread_count', ['senderId' => '__SID__']) }}",
            getMessages: "{{ route('chat.get', ['receiverId' => '__RID__']) }}"
        },
        pollInterval: 2000
    };
</script>

{{-- Chat Widget Script --}}
<script src="{{ asset('js/chat/chat_user.js') }}"></script>
