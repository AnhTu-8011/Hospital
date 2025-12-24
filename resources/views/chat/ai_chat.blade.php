{{-- AI Chat Widget Container --}}
<div id="ai-chat-widget" style="position:fixed; left:20px; bottom:20px; width:360px; max-width:92vw; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden; font-family:system-ui, -apple-system, Segoe UI, Roboto, Arial; box-shadow:0 10px 20px rgba(0,0,0,.12); background:#fff; z-index:1050;">
    {{-- Chat Header --}}
    <div id="chat-header" style="background:#0d6efd; color:white; padding:10px 12px; display:flex; align-items:center; justify-content:space-between;">
        <div style="font-weight:600; display:flex; align-items:center; gap:8px;">
            <span>🤖</span>
            <span>Tư vấn AI - Bệnh viện</span>
        </div>
        <button id="toggle-chat"
                aria-label="Thu gọn"
                style="background:transparent; color:#fff; border:none; font-size:16px; line-height:1; cursor:pointer;">
            ▾
        </button>
    </div>

    {{-- Chat Container --}}
    <div id="chat-container" style="display:flex; flex-direction:column; height:420px;">
        {{-- Chat Messages Box --}}
        <div id="chat-box" style="flex:1; overflow-y:auto; padding:12px; background:#f8fafc;">
            <div id="messages"></div>
        </div>

        {{-- Chat Input Area --}}
        <div id="chat-input" style="display:flex; align-items:end; gap:8px; border-top:1px solid #e5e7eb; padding:8px; background:#fff;">
            <textarea id="user-input"
                      placeholder="Mô tả triệu chứng của bạn..."
                      rows="2"
                      style="flex:1; border:1px solid #e5e7eb; padding:8px 10px; resize:none; outline:none; border-radius:8px;"></textarea>
            <button id="send-btn"
                    style="flex:0 0 auto; height:38px; padding:0 14px; background:#0d6efd; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                Gửi
            </button>
        </div>
    </div>
</div>

{{-- AI Chat Widget Script --}}
<script>
    // Responsive adjustment for narrow viewports
    try {
        const mq = window.matchMedia('(max-width: 420px)');
        const ai = document.getElementById('ai-chat-widget');
        function adjust() {
            ai.style.bottom = mq.matches ? '90px' : '20px';
        }
        adjust();
        mq.addEventListener ? mq.addEventListener('change', adjust) : mq.addListener(adjust);
    } catch (e) {}

    // Widget elements
    const widget = document.getElementById('ai-chat-widget');
    const toggleBtn = document.getElementById('toggle-chat');
    const messagesDiv = widget.querySelector('#messages');
    const input = widget.querySelector('#user-input');
    const sendBtn = widget.querySelector('#send-btn');
    const container = widget.querySelector('#chat-container');

    // Toggle chat container
    let collapsed = false;
    toggleBtn.addEventListener('click', () => {
        collapsed = !collapsed;
        container.style.display = collapsed ? 'none' : 'flex';
        toggleBtn.textContent = collapsed ? '▴' : '▾';
    });

    // Create message bubble
    function bubble(content, align = 'left', tone = 'ai') {
        const wrap = document.createElement('div');
        wrap.style.margin = '8px 0';
        wrap.style.display = 'flex';
        wrap.style.justifyContent = align === 'right' ? 'flex-end' : 'flex-start';

        const span = document.createElement('div');
        span.style.maxWidth = '85%';
        span.style.padding = '8px 12px';
        span.style.borderRadius = '12px';
        span.style.whiteSpace = 'pre-wrap';
        span.style.wordBreak = 'break-word';

        if (tone === 'user') {
            span.style.background = '#0d6efd';
            span.style.color = '#fff';
        } else {
            span.style.background = '#e9eef5';
            span.style.color = '#111827';
        }

        span.innerHTML = content;
        wrap.appendChild(span);
        messagesDiv.appendChild(wrap);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        return wrap;
    }

    // Render suggestions (services and departments)
    function renderSuggestions(sugs) {
        if (!sugs) return;

        const hasServices = Array.isArray(sugs.services) && sugs.services.length;
        const hasDepts = Array.isArray(sugs.departments) && sugs.departments.length;
        if (!hasServices && !hasDepts) return;

        let html = '<div style="margin-top:6px">';

        // Services section
        if (hasServices) {
            html += '<div style="font-weight:600; margin:6px 0 4px;">Dịch vụ phù hợp</div>';
            html += '<ul style="padding-left:16px; margin:0;">'
                + sugs.services.map(s => `<li style="margin:2px 0;">${s.name}${s.price !== null && s.price !== undefined ? ` - <span style='color:#0d6efd'>${Number(s.price).toLocaleString('vi-VN')} đ</span>` : ''}</li>`).join('')
                + '</ul>';
        }

        // Departments section
        if (hasDepts) {
            html += '<div style="font-weight:600; margin:10px 0 4px;">Khoa gợi ý</div>';
            html += '<ul style="padding-left:16px; margin:0;">'
                + sugs.departments.map(d => `<li style="margin:2px 0;">${d.name}</li>`).join('')
                + '</ul>';
        }

        // Action buttons
        html += '<div style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">'
            + `<a href="#services" style="text-decoration:none; background:#0d6efd; color:#fff; padding:6px 10px; border-radius:8px; font-size:12px;">Xem dịch vụ</a>`
            + `<a href="#departments" style="text-decoration:none; background:#e5e7eb; color:#111; padding:6px 10px; border-radius:8px; font-size:12px;">Xem khoa</a>`
            + '</div>';
        html += '</div>';

        bubble(html, 'left', 'ai');
    }

    // Send message to AI
    async function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        bubble(message, 'right', 'user');
        input.value = '';

        const loading = bubble('<i>Đang xử lý...</i>', 'left', 'ai');

        try {
            const res = await fetch('/api/chat/ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });
            const data = await res.json();
            loading.remove();
            bubble(data.reply || 'Xin lỗi, tôi chưa hiểu câu hỏi của bạn.', 'left', 'ai');
            renderSuggestions(data.suggestions);
        } catch (e) {
            loading.remove();
            bubble('⚠️ Có lỗi xảy ra, vui lòng thử lại.', 'left', 'ai');
        }
    }

    // Send button click handler
    sendBtn.addEventListener('click', sendMessage);

    // Enter key handler (Shift+Enter for new line)
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
</script>
