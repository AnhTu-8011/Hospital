<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Thông tin cá nhân') - Bệnh viện Đa khoa Phúc An</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .top-bar {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            transition: all 0.3s;
        }
        
        .top-bar a:hover {
            color: #ffc107;
        }
        
        .top-bar i {
            margin-right: 5px;
        }
        
        .navbar {
            padding: 15px 0;
            background-color: white !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: var(--primary-color) !important;
        }
        
        .navbar-brand span {
            color: var(--secondary-color);
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            padding: 8px 15px !important;
            margin: 0 5px;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary-color);
            color: white !important;
        }
        
        .btn-appointment {
            background-color: var(--primary-color);
            color: white !important;
            border-radius: 30px;
            padding: 8px 20px !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-appointment:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
            border: 2px solid var(--primary-color);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 10px 0;
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 8px 20px;
            font-size: 14px;
            color: var(--dark-color);
            transition: all 0.3s;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            border-top: 1px solid #eee;
            margin: 5px 0;
        }
        
        /* Hide admin navigation */
        .sidebar, .navbar-vertical.navbar-expand-lg {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .top-bar {
                text-align: center;
            }
            
            .navbar-nav {
                margin-top: 15px;
            }
            
            .nav-link {
                margin: 5px 0;
                padding: 10px 15px !important;
            }
            
            .btn-appointment {
                margin-top: 10px;
                display: inline-block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex">
                        <a href="tel:+84912345678"><i class="fas fa-phone-alt"></i> 0912 345 678</a>
                        <a href="mailto:info@benhvienphucan.com"><i class="far fa-envelope"></i> info@benhvienphucan.com</a>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">BỆNH VIỆN <span>PHÚC AN</span></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle me-1"></i> Hồ sơ cá nhân
                        </a>
                    </li>
                    @php
                        $pendingAppointmentsCount = 0;
                        if (Auth::check() && Auth::user()->patient) {
                            $pendingAppointmentsCount = \App\Models\Appointment::where('patient_id', Auth::user()->patient->id)
                                ->whereDate('appointment_date', '>=', \Carbon\Carbon::today())
                                ->whereIn('status', ['pending','confirmed'])
                                ->count();
                        }
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('appointments.index') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Lịch hẹn
                            @if($pendingAppointmentsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $pendingAppointmentsCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown user-dropdown">
                        @php
                            $patient = Auth::user()->patient ?? null;
                            $avatarUrl = ($patient && !empty($patient->avatar))
                                ? asset('storage/' . $patient->avatar)
                                : asset('images/default-avatar.png');
                        @endphp
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $avatarUrl }}" 
                                 alt="User" class="user-avatar">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle me-2"></i> Hồ sơ cá nhân
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('appointments.index') }}">
                                <i class="fas fa-calendar-alt me-2"></i> Lịch hẹn
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Cài đặt
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Nội dung --}}
    <main class="container py-4">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>