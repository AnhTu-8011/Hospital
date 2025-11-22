<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bệnh viện PHÚC AN')</title>

    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);
            min-height: 100vh;
        }
        .top-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }
        .top-bar a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .top-bar a:hover {
            color: white;
            transform: translateX(3px);
        }
        .top-bar-item {
            color: rgba(255,255,255,0.9);
        }
        .social-links a {
            color: rgba(255,255,255,0.9);
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        .social-links a:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-3px);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="top-bar-left">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="top-bar-item">
                            <i class="fas fa-phone-alt me-2"></i>
                            <a href="tel:19001234">1900 1234</a>
                        </div>
                        <div class="top-bar-item">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:info@phucan.vn">info@phucan.vn</a>
                        </div>
                    </div>
                </div>
                <div class="top-bar-right">
                    <div class="d-flex align-items-center gap-2">
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Youtube"><i class="fab fa-youtube"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                        @auth
                        <div class="d-none">
                            <div>User ID: {{ Auth::id() }}</div>
                            <div>User Name: {{ Auth::user()->name }}</div>
                            <div>User Email: {{ Auth::user()->email }}</div>
                            <div>Role: {{ Auth::user()->role ? Auth::user()->role->name : 'No Role' }}</div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    @stack('scripts')
</body>
</html>