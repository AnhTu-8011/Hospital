<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multi-tab Token Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-100">
<div class="max-w-lg w-full bg-white rounded-2xl shadow p-6 space-y-4">
    <h1 class="text-xl font-bold text-slate-800">Test multi-tab với token</h1>
    <p class="text-sm text-slate-600">
        Trang này gọi <code>/api/user</code> bằng token lưu trong <code>sessionStorage</code> của từng tab.
        Nếu bạn đăng nhập 2 tài khoản ở 2 tab khác nhau, dữ liệu bên dưới sẽ khác nhau.
    </p>

    <div id="status" class="text-sm text-slate-500">Đang gọi API...</div>

    <pre id="user-data" class="text-xs bg-slate-900 text-slate-100 rounded p-3 overflow-auto"></pre>
</div>

<script>
(function () {
    const TOKEN_KEY = 'tab_auth_token';
    const token = sessionStorage.getItem(TOKEN_KEY);
    const statusEl = document.getElementById('status');
    const userEl = document.getElementById('user-data');

    if (!token) {
        statusEl.textContent = 'Không tìm thấy token trong sessionStorage của tab này. Hãy đăng nhập bằng form mới rồi reload.';
        return;
    }

    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

    axios.get('/api/user')
        .then(function (res) {
            statusEl.textContent = 'Gọi API thành công. Đây là user của tab này:';
            userEl.textContent = JSON.stringify(res.data, null, 2);
        })
        .catch(function (err) {
            statusEl.textContent = 'Gọi API thất bại. Có thể token hết hạn hoặc cấu hình Sanctum chưa đúng.';
            if (err.response) {
                userEl.textContent = JSON.stringify({
                    status: err.response.status,
                    data: err.response.data
                }, null, 2);
            } else {
                userEl.textContent = err.toString();
            }
        });
})();
</script>
</body>
</html>
