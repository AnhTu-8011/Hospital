<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light text-dark" style="font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);">
<div class="d-flex min-vh-100">

    <!-- 🌙 Sidebar -->
    <aside class="sidebar d-flex flex-column flex-shrink-0 shadow-lg border-end" style="width: 260px; background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%);">
        <!-- Logo -->
        <div class="d-flex align-items-center mb-4 px-3 py-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                <i class="fas fa-heartbeat text-primary fs-5"></i>
            </div>
            <div>
                <span class="fs-6 fw-bold text-white d-block">Bệnh viện</span>
                <span class="fs-7 text-white-50 d-block">PHÚC AN</span>
            </div>
        </div>

        <!-- Admin Info -->
        <div class="text-center rounded-4 p-3 mb-4 mx-3" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="fas fa-user-shield text-primary fs-5"></i>
            </div>
            <h6 class="fw-semibold mb-0 text-white">Administrator</h6>
            <small class="text-white-50">👑 Quản trị viên</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column flex-grow-1 px-2">
            <li class="nav-item mb-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-3"></i> <span>Trang chủ</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.appointments.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-3"></i> <span>Lịch hẹn</span>
                    @if(isset($newAppointmentsCount) && $newAppointmentsCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $newAppointmentsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.doctors.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md me-3"></i> <span>Bác sĩ</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.departments.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="fas fa-building me-3"></i> <span>Khoa phòng</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.services.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope me-3"></i> <span>Dịch vụ</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.test-types.index') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('admin.test-types.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-3"></i> <span>Loại xét nghiệm</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.lab_tests.index') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('admin.lab_tests.*') ? 'active' : '' }}">
                    <i class="fas fa-vials me-3"></i> <span>Xét nghiệm</span>
                    @if(isset($newLabTestsCount) && $newLabTestsCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $newLabTestsCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-3"></i> <span>Người dùng</span>
                </a>
            </li>
            <!-- Logout -->
            <li class="nav-item mt-auto mb-3">
                <a href="#" class="nav-link logout-link d-flex align-items-center"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-3"></i> <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </aside>

    <!-- 🌤 Main content -->
    <main class="flex-grow-1 d-flex flex-column">
        <!-- Top bar: sidebar toggle on small screens -->
        <div class="d-lg-none d-flex justify-content-between align-items-center p-3 border-bottom bg-white">
            <button id="sidebarToggle" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-bars"></i>
            </button>
            <span class="fw-semibold">Bệnh viện PHÚC AN - Admin</span>
        </div>

        <!-- Content -->
        <div class="flex-grow-1 p-4">
            <div class="bg-white rounded-4 shadow-lg border-0 p-4" style="min-height: calc(100vh - 2rem);">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </main>
</div>

<!-- 💅 CSS -->
<style>
    /* Sidebar Navigation Links */
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        padding: 12px 16px;
        border-radius: 12px;
        transition: all 0.3s ease;
        margin-bottom: 4px;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: rgba(255, 255, 255, 0.5);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateX(5px);
    }
    
    .sidebar .nav-link:hover::before {
        transform: scaleY(1);
    }
    
    .sidebar .nav-link.active {
        background: rgba(255, 255, 255, 0.25);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .sidebar .nav-link.active::before {
        transform: scaleY(1);
        background: #fff;
    }
    
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
    }
    
    /* Logout Link */
    .sidebar .logout-link {
        color: rgba(255, 200, 200, 0.9) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 16px;
    }
    
    .sidebar .logout-link:hover {
        background: rgba(220, 53, 69, 0.2);
        color: #fff !important;
    }
    
    /* Sidebar Animation */
    .sidebar {
        transition: all 0.3s ease;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Badge in sidebar */
    .sidebar .badge {
        font-size: 0.7rem;
        padding: 4px 8px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Main Content Area */
    main {
        background: transparent;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            position: fixed;
            left: -260px;
            top: 0;
            height: 100%;
            z-index: 1050;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
        }
        .sidebar.active {
            left: 0;
        }
    }
    
    /* Smooth transitions */
    * {
        transition: background-color 0.2s ease, color 0.2s ease;
    }
</style>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggleBtn = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');

        if (toggleBtn && sidebar) {
            // Bấm nút 3 gạch: mở/thu sidebar
            toggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
            });

            // Bấm ra ngoài sidebar trên màn hình nhỏ: tự thu lại
            document.addEventListener('click', function (e) {
                if (window.innerWidth > 992) return; // chỉ áp dụng cho màn nhỏ

                var clickInsideSidebar = sidebar.contains(e.target);
                var clickOnToggle = toggleBtn.contains(e.target);

                if (!clickInsideSidebar && !clickOnToggle) {
                    sidebar.classList.remove('active');
                }
            });
        }
    });
  </script>
@stack('scripts')
</body>
</html>
