 <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <div class="d-flex flex-column">
                        <span class="h5 fw-bold text-primary mb-0">BỆNH VIỆN PHÚC AN</span>
                    </div>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">Giới thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#departments">Chuyên khoa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#doctors">Đội ngũ bác sĩ</a>
                        </li>
                        @auth
                            <li class="nav-item d-lg-none">
                                <a class="nav-link" href="{{ route('appointments.index') }}">
                                    <i class="fas fa-calendar-alt me-1"></i>Lịch hẹn của tôi
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-1"></i>Thông tin cá nhân
                                </a>
                            </li>
                            <li class="nav-item d-lg-none">
                                <form method="POST" action="{{ route('logout') }}" class="w-100">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link text-start w-100">
                                        <i class="fas fa-sign-out-alt me-1"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        @endauth
                    </ul>

                    <div class="d-flex align-items-center">
                    @guest
                        <!-- Desktop -->
                        <div class="d-none d-lg-flex">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </div>

                        <!-- Mobile -->
                        <div class="dropdown d-lg-none">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="mobileAuthDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> Tài khoản
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileAuthDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                            @php
                                $pendingAppointmentsCount = 0;
                                if (Auth::check() && Auth::user()->patient) {
                                    $pendingAppointmentsCount = \App\Models\Appointment::where('patient_id', Auth::user()->patient->id)
                                        ->whereDate('appointment_date', '>=', \Carbon\Carbon::today())
                                        ->whereIn('status', ['pending','confirmed'])
                                        ->count();
                                }
                            @endphp
                            <a href="{{ route('appointments.index') }}" class="btn btn-link position-relative me-2 d-none d-lg-inline" title="Lịch hẹn của tôi">
                                <i class="fas fa-bell fa-lg"></i>
                                @if($pendingAppointmentsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $pendingAppointmentsCount }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown d-none d-lg-block">
                                @php
                                    $patient = Auth::user()->patient ?? null;
                                    $avatarUrl = ($patient && !empty($patient->avatar))
                                        ? asset('storage/' . $patient->avatar)
                                        : asset('images/default-avatar.png');
                                @endphp
                                <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle me-2" width="28" height="28" style="object-fit: cover;">
                                    <span>{{ Str::limit(Auth::user()->name, 15) }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="dropdown-header">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                                <small class="text-muted">
                                                    {{ Auth::user()->role ? ucfirst(Auth::user()->role->name) : 'Thành viên' }}
                                                </small>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user me-2"></i>Thông tin cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('appointments.index') }}">
                                            <i class="fas fa-calendar-alt me-2"></i>Lịch hẹn của tôi
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            <div class="d-lg-none">
                                <a href="#" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i>
                                </a>
                            </div>
                        @endguest
                    </div>
                </div>
        </div>
    </nav>
    </header>