<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - Bệnh viện PHÚC AN</title>

    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
            Quên mật khẩu
        </h2>

        <!-- Status message -->
        @if (session('status'))
            <div class="mb-4 text-center text-green-600 text-sm font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-envelope mr-1 text-blue-500"></i> Email
                </label>

                <input type="email" name="email"
                       class="w-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-100 rounded-lg px-4 py-2 outline-none
                       @error('email') border-red-500 @enderror"
                       placeholder="Nhập email để lấy lại mật khẩu"
                       required>

                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2.5 transition duration-200">
                Gửi link đặt lại mật khẩu
            </button>
        </form>

        <!-- Back to login -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline text-sm">
                ← Quay lại trang đăng nhập
            </a>
        </div>

    </div>

    <!-- Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
