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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
            margin-right: 15px;
            transition: all 0.3s;
        }
        
        .top-bar a:hover {
            color: white;
            transform: translateX(3px);
        }
        
        .top-bar i {
            margin-right: 5px;
        }
        
        .navbar {
            padding: 15px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand span {
            color: rgba(255,255,255,0.9);
        }
        
        .nav-link {
            font-weight: 500;
            color: rgba(255,255,255,0.9) !important;
            padding: 10px 16px !important;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.15);
            color: white !important;
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background-color: rgba(255,255,255,0.25);
            color: white !important;
            font-weight: 600;
        }
        
        .btn-appointment {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white !important;
            border-radius: 30px;
            padding: 10px 24px !important;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
        }
        
        .btn-appointment:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4);
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
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 8px 0;
            margin-top: 10px;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            color: var(--dark-color);
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
            color: var(--primary-color);
            transform: translateX(5px);
        }
        
        .dropdown-divider {
            border-top: 1px solid #eee;
            margin: 8px 0;
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
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                <div class="d-inline-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-primary shadow-sm" style="width: 40px; height: 40px;">
                        <i class="bi bi-hospital fs-5"></i>
                    </span>
                    <span class="text-white">BỆNH VIỆN <span class="text-white-50">PHÚC AN</span></span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid rgba(255,255,255,0.3) !important;">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
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
                        <a class="nav-link position-relative {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Lịch hẹn
                            @if($pendingAppointmentsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
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
                                : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                        @endphp
                        <a class="nav-link dropdown-toggle d-flex align-items-center btn btn-light rounded-pill shadow-sm" href="#" id="userDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">
                            <img src="{{ $avatarUrl }}" 
                                 alt="User" class="user-avatar">
                            <span class="fw-semibold text-dark">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0" aria-labelledby="userDropdown" style="margin-top: 10px;">
                            <li class="dropdown-header bg-light rounded-top-4 p-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="{{ $avatarUrl }}" alt="User" class="user-avatar" width="48" height="48">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">
                                            {{ Auth::user()->role ? ucfirst(Auth::user()->role->name) : 'Thành viên' }}
                                        </small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li><a class="dropdown-item rounded-3 mx-2" href="{{ route('profile.edit') }}" style="transition: all 0.3s ease;" onmouseover="this.style.background='#f8f9ff'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
                                <i class="fas fa-user-circle me-2 text-primary"></i> Hồ sơ cá nhân
                            </a></li>
                            <li><a class="dropdown-item rounded-3 mx-2 position-relative" href="{{ route('appointments.index') }}" style="transition: all 0.3s ease;" onmouseover="this.style.background='#f8f9ff'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i> Lịch hẹn
                                @if($pendingAppointmentsCount > 0)
                                    <span class="badge bg-danger rounded-pill ms-2">{{ $pendingAppointmentsCount }}</span>
                                @endif
                            </a></li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger rounded-3 mx-2 w-100 text-start" style="transition: all 0.3s ease;" onmouseover="this.style.background='#fff5f5'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
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
    <main class="container py-4" style="min-height: calc(100vh - 200px);">
        @yield('content')
    </main>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>