<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light text-dark" style="font-family: 'Inter', sans-serif;">
<div class="d-flex min-vh-100">

    <!-- 🌙 Sidebar -->
    <aside class="sidebar d-flex flex-column flex-shrink-0 bg-white shadow-sm p-3 border-end" style="width: 260px;">
        <!-- Logo -->
        <div class="d-flex align-items-center mb-4 px-2">
            <i class="fas fa-heartbeat text-primary fs-4 me-2"></i>
            <span class="fs-5 fw-bold text-primary">Bệnh viện PHÚC AN</span>
        </div>

        <!-- Admin Info -->
        <div class="text-center border rounded-4 p-3 mb-4 bg-light-subtle">
            <h6 class="fw-semibold mb-0">Administrator</h6>
            <small class="text-muted">👑 Quản trị viên</small>
        </div>

        <!-- Menu -->
        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home me-2"></i> Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.appointments.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-2"></i> Lịch hẹn
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.doctors.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md me-2"></i> Bác sĩ
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.departments.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="fas fa-building me-2"></i> Khoa phòng
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.services.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope me-2"></i> Dịch vụ
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.test-types.index') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('admin.test-types.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i> Loại xét nghiệm
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.lab_tests.index') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('admin.lab_tests.*') ? 'active' : '' }}">
                    <i class="fas fa-vials me-2"></i> Xét nghiệm
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Người dùng
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <div class="border-top pt-3 mt-auto">
            <a href="#" class="nav-link text-danger d-flex align-items-center"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </aside>

    <!-- 🌤 Main content -->
    <main class="flex-grow-1 d-flex flex-column">
        <!-- Content -->
        <div class="flex-grow-1 p-4">
            <div class="bg-white border rounded-4 shadow-sm p-4">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
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
    .nav-link {
        color: #555;
        font-weight: 500;
        padding: 10px 14px;
        border-radius: 8px;
        transition: all 0.25s ease;
    }
    .nav-link:hover {
        background: #f4f7ff;
        color: #0d6efd;
    }
    .nav-link.active {
        background: #0d6efd;
        color: #fff !important;
    }
    .sidebar {
        transition: all 0.3s ease;
    }
    @media (max-width: 992px) {
        .sidebar {
            position: fixed;
            left: -260px;
            top: 0;
            height: 100%;
            z-index: 1050;
        }
        .sidebar.active {
            left: 0;
        }
    }
</style>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
