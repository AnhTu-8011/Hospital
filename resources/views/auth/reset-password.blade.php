<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - Bệnh viện PHÚC AN</title>

    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8" style="border: none;">

        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fa-solid fa-hospital text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">BỆNH VIỆN PHÚC AN</h1>
            <p class="text-gray-500 text-sm mt-1">Chăm sóc sức khỏe tận tâm</p>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-gray-700 border-b-2 pb-3 mb-6 text-center" style="border-color: #e3f2ff;">
            <i class="fa-solid fa-lock mr-2" style="color: #667eea;"></i>Đặt lại mật khẩu
        </h2>

        <!-- Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-envelope mr-1 text-blue-500"></i> Email
                </label>

                <input type="email" name="email"
                    class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none
                    @error('email') border-red-500 @enderror"
                    placeholder="Nhập email"
                    required>

                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-lock mr-1 text-blue-500"></i> Mật khẩu mới
                </label>

                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none
                        @error('password') border-red-500 @enderror"
                        placeholder="Nhập mật khẩu mới"
                        required>

                    <button type="button" onclick="togglePassword('password', this)"
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-key mr-1 text-blue-500"></i> Xác nhận mật khẩu
                </label>

                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none"
                        placeholder="Nhập lại mật khẩu"
                        required>

                    <button type="button" onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full text-white font-semibold rounded-full py-3 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fa-solid fa-key mr-1"></i>Đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">
                ← Quay lại trang đăng nhập
            </a>
        </div>

    </div>

    <!-- Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Toggle password -->
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

</body>
</html>
