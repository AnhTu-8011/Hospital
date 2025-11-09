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

    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Top Navbar */
        .navbar {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
        }

        .navbar .navbar-brand {
            color: #fff;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .navbar .nav-link {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            margin-right: 1rem;
            transition: color 0.3s ease;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: #fff;
        }

        .navbar .dropdown-menu {
            font-size: 0.9rem;
        }

        /* Dashboard cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .card h6 {
            font-weight: 600;
        }

        .icon {
            font-size: 2rem;
            opacity: 0.8;
        }

        .content-wrapper {
            padding: 2rem;
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('doctor.dashboard') }}"><i class="fa-solid fa-house me-1"></i> Bảng điều khiển</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctor.patient.history') }}">
                            <i class="fa-solid fa-calendar-check me-1"></i> Lịch sử khám
                        </a>
                    </li>
                </ul>

                    <!-- User -->
                    <li class="nav-item dropdown">
                        @php
                            $doctor = \App\Models\Doctor::where('user_id', Auth::id())->first();
                            $avatarUrl = $doctor && $doctor->avatar 
                                ? asset('storage/' . $doctor->avatar) 
                                : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                        @endphp

                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            <img src="{{ $avatarUrl }}" alt="avatar" width="35" class="rounded-circle me-2" style="object-fit: cover;">
                            <span>{{ Auth::user()->name ?? 'Bác sĩ' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('doctor.profile.edit') }}">Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
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
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
