 <header class="main-header shadow-sm bg-white">
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container">
            {{-- BRAND --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="d-inline-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white" style="width: 32px; height: 32px;">
                        <i class="bi bi-hospital"></i>
                    </span>
                    <span class="fw-bold text-primary" style="letter-spacing: .06em; text-transform: uppercase; font-size: .9rem;">
                        BỆNH VIỆN PHÚC AN
                    </span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                {{-- CENTER NAV LINKS --}}
                <ul class="navbar-nav mx-lg-auto align-items-lg-center mb-2 mb-lg-0 gap-lg-1">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('introduces.index') ? 'active fw-semibold' : '' }}" href="{{ route('introduces.index') }}">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('services.index') ? 'active fw-semibold' : '' }}" href="{{ route('services.index') }}">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.index') ? 'active fw-semibold' : '' }}" href="{{ route('departments.index') }}">Chuyên khoa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctors.index') ? 'active fw-semibold' : '' }}" href="{{ route('doctors.index') }}">Đội ngũ bác sĩ</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a
                            href="{{ route('modal.appointment') }}"
                            class="btn btn-primary d-flex align-items-center px-3 py-2 rounded-pill shadow-sm"
                            style="font-weight: 600; gap: 6px;"
                        >
                            <i class="bi bi-calendar-check"></i>
                            <span>Đặt lịch khám</span>
                        </a>
                    </li>

                    @auth
                        {{-- MOBILE USER LINKS --}}
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

                {{-- RIGHT AUTH / USER AREA --}}
                <div class="d-flex align-items-center ms-lg-3">
                    @guest
                        {{-- Desktop login button --}}
                        <div class="d-none d-lg-flex">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </div>

                        {{-- Mobile auth dropdown --}}
                        <div class="dropdown d-lg-none ms-2">
                            <button class="btn btn-outline-primary rounded-pill dropdown-toggle" type="button" id="mobileAuthDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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

                        {{-- Notifications --}}
                        <a href="{{ route('appointments.index') }}" class="btn btn-link position-relative me-2 d-none d-lg-inline" title="Lịch hẹn của tôi">
                            <i class="fas fa-bell fa-lg"></i>
                            @if($pendingAppointmentsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $pendingAppointmentsCount }}
                                </span>
                            @endif
                        </a>

                        {{-- Desktop user dropdown --}}
                        <div class="dropdown d-none d-lg-block">
                            @php
                                $patient = Auth::user()->patient ?? null;
                                $avatarUrl = ($patient && !empty($patient->avatar))
                                    ? asset('storage/' . $patient->avatar)
                                    : asset('images/default-avatar.png');
                            @endphp
                            <button class="btn btn-outline-primary rounded-pill dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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

                        {{-- Mobile user icon (can open offcanvas / future menu) --}}
                        <div class="d-lg-none ms-1">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>