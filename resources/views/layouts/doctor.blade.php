<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Doctor Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* Top Navbar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 1rem 1.5rem;
        }

        .navbar .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar .navbar-brand::before {
            content: '🏥';
            font-size: 1.5rem;
        }

        .navbar .nav-link {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            margin-right: 0.5rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            transform: translateY(-2px);
        }

        .navbar .nav-link.active {
            background: rgba(255,255,255,0.25);
            color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .navbar .dropdown-menu {
            font-size: 0.9rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .navbar .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateX(5px);
        }

        /* Dashboard cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .card-header {
            border-bottom: 2px solid #f0f0f0;
            background: white;
            font-weight: 600;
        }

        .content-wrapper {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Border left cards */
        .border-left-primary {
            border-left: 4px solid #667eea !important;
        }

        .border-left-warning {
            border-left: 4px solid #f093fb !important;
        }

        .border-left-success {
            border-left: 4px solid #4facfe !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('doctor.dashboard') }}">Bệnh viện Phúc An</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}" href="{{ route('doctor.dashboard') }}">
                            <i class="fas fa-home me-2"></i> Bảng điều khiển
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctor.patient.history') ? 'active' : '' }}" href="{{ route('doctor.patient.history') }}">
                            <i class="fas fa-calendar-check me-2"></i> Lịch sử khám
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- User -->
                    <li class="nav-item dropdown">
                        @php
                            // Use the web_doctor guard for doctor-related pages
                            $user = Auth::guard('web_doctor')->user();
                            
                            // Get the doctor profile for the authenticated user
                            $doctor = $user->doctor ?? null;
                            
                            // Debug information (uncomment if needed)
                            // dd([
                            //     'user' => $user,
                            //     'doctor' => $doctor,
                            //     'doctor_avatar' => $doctor?->avatar,
                            //     'avatar_url' => $doctor ? asset('storage/' . $doctor->avatar) : null
                            // ]);
                            
                            // Get avatar URL - fallback to default if not set
                            $avatarUrl = $doctor && !empty($doctor->avatar) 
                                ? asset('storage/' . $doctor->avatar) 
                                : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                            
                            // Get display name with fallback to user's name or default
                            $displayName = $user->name ?? ($doctor?->name ?? 'Bác sĩ');
                        @endphp

                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <img src="{{ $avatarUrl }}" alt="avatar" width="40" height="40" class="rounded-circle me-2 border border-white border-2" style="object-fit: cover;">
                            <span class="fw-semibold">{{ $displayName }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <li>
                                <a class="dropdown-item" href="{{ route('doctor.profile.edit') }}">
                                    <i class="fas fa-user-md me-2 text-primary"></i> Hồ sơ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2 text-secondary"></i> Cài đặt
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
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

    <!-- Page Content -->
    <main class="content-wrapper">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
