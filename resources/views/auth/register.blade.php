<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - Bệnh viện PHÚC AN</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 px-4">

    <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl p-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">BỆNH VIỆN PHÚC AN</h1>
            <p class="text-gray-500 text-sm mt-1">Hệ thống quản lý bệnh viện</p>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-6 text-center">
            <i class="fa-solid fa-user-plus mr-1 text-blue-500"></i> Đăng ký tài khoản
        </h2>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-600 rounded-lg p-3 mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Họ và tên -->
            <div>
                <label for="name" class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-user text-blue-500 mr-1"></i> Họ và tên
                </label>
                <input id="name" name="name" type="text"
                    value="{{ old('name') }}"
                    required autocomplete="name"
                    placeholder="Nhập họ và tên đầy đủ"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none
                    @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ngày sinh -->
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-calendar-days text-blue-500 mr-1"></i> Ngày sinh
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <select id="birth_day" class="border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none">
                        <option value="">Ngày</option>
                        @for ($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" {{ old('birth_day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endfor
                    </select>
                    <select id="birth_month" class="border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none">
                        <option value="">Tháng</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('birth_month') == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                    <input id="birth_year" type="number" min="1900" max="{{ now()->year }}" placeholder="Năm"
                           value="{{ old('birth_year') }}"
                           class="border border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none" />
                </div>
                <input type="hidden" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" />
                @error('birthdate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-envelope text-blue-500 mr-1"></i> Email
                </label>
                <input id="email" name="email" type="email"
                    value="{{ old('email') }}"
                    required autocomplete="username"
                    placeholder="Nhập email"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none
                    @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mật khẩu -->
            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-lock text-blue-500 mr-1"></i> Mật khẩu
                </label>
                <div class="relative">
                    <input id="password" name="password" type="password"
                        required autocomplete="new-password"
                        placeholder="Nhập mật khẩu"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none
                        @error('password') border-red-500 @enderror">
                    <button type="button" onclick="togglePassword('password')" 
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Xác nhận mật khẩu -->
            <div>
                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-1">
                    <i class="fa-solid fa-lock text-blue-500 mr-1"></i> Xác nhận mật khẩu
                </label>
                <div class="relative">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        required autocomplete="new-password"
                        placeholder="Nhập lại mật khẩu"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 pr-10 focus:border-blue-500 focus:ring focus:ring-blue-100 outline-none">
                    <button type="button" onclick="togglePassword('password_confirmation')" 
                        class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Điều khoản -->
            <div class="flex items-start space-x-2">
                <input id="terms" name="terms" type="checkbox" required
                    class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="terms" class="text-sm text-gray-700">
                    Tôi đồng ý với <a href="#" class="text-blue-600 hover:underline">Điều khoản</a> &
                    <a href="#" class="text-blue-600 hover:underline">Chính sách</a> của bệnh viện
                </label>
            </div>

            <!-- Nút đăng ký -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg py-2.5 transition duration-200">
                <i class="fa-solid fa-user-plus mr-1"></i> Đăng ký
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-700 text-sm mt-6">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                Đăng nhập ngay
            </a>
        </p>

        <p class="text-center text-gray-400 text-xs mt-4">
            &copy; {{ date('Y') }} Bệnh viện PHÚC AN. Tất cả các quyền được bảo lưu.
        </p>
    </div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function(){
    function pad(n){ return (n<10? '0'+n : n); }
    function updateHiddenBirthdate(){
        const d = parseInt($('#birth_day').val(), 10);
        const m = parseInt($('#birth_month').val(), 10);
        const y = parseInt($('#birth_year').val(), 10);
        if(d && m && y){
            $('#birthdate').val(`${y}-${pad(m)}-${pad(d)}`);
        }
    }

    // If old hidden value exists (dd/mm/yyyy or yyyy-mm-dd), prefill parts
    const oldVal = $('#birthdate').val();
    if(oldVal){
        let y,m,d;
        if(/^\d{2}\/\d{2}\/\d{4}$/.test(oldVal)){
            const parts = oldVal.split('/');
            d = parseInt(parts[0],10); m = parseInt(parts[1],10); y = parseInt(parts[2],10);
        } else if(/^\d{4}-\d{2}-\d{2}$/.test(oldVal)){
            const parts = oldVal.split('-');
            y = parseInt(parts[0],10); m = parseInt(parts[1],10); d = parseInt(parts[2],10);
        }
        if(y){ $('#birth_year').val(y); }
        if(m){ $('#birth_month').val(m); }
        if(d){ $('#birth_day').val(d); }
    }

    $('#birth_day, #birth_month, #birth_year').on('change keyup', updateHiddenBirthdate);

    $('form[action="{{ route('register') }}"]').on('submit', function(e){
        updateHiddenBirthdate();
        if(!$('#birthdate').val()){
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ Ngày/Tháng và nhập Năm.');
        }
    });
});
</script>
</html>
