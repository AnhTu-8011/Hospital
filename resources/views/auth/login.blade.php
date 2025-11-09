<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Bệnh viện PHÚC AN</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">BỆNH VIỆN PHÚC AN</h1>
            <p class="text-gray-500 text-sm mt-1">Chăm sóc sức khỏe tận tâm</p>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-6 text-center">
            Đăng nhập tài khoản
        </h2>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-envelope mr-1 text-blue-500"></i> Email
                </label>
                <input id="email" type="email"
                    class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none
                    @error('email') border-red-500 @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Nhập email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="text-gray-700 text-sm font-medium">
                        <i class="fa-solid fa-lock mr-1 text-blue-500"></i> Mật khẩu
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>

                <div class="relative">
                    <input id="password" type="password"
                        class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none
                        @error('password') border-red-500 @enderror"
                        name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu">
                    <button type="button" onclick="togglePassword('password')" 
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-700">Ghi nhớ đăng nhập</label>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2.5 transition duration-200">
                <i class="fa-solid fa-right-to-bracket mr-1"></i> Đăng nhập
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-grow h-px bg-gray-200"></div>
            <span class="text-gray-400 text-sm mx-3">hoặc</span>
            <div class="flex-grow h-px bg-gray-200"></div>
        </div>

        <!-- Register -->
        <p class="text-center text-gray-700 text-sm">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">
                Đăng ký ngay
            </a>
        </p>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Toggle password -->
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = event.currentTarget.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
