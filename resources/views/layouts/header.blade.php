<header class="main-header shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <nav class="navbar navbar-expand-lg navbar-dark py-3">
        <div class="container">
            {{-- BRAND --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                <div class="d-inline-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-primary shadow-sm" style="width: 40px; height: 40px;">
                        <i class="bi bi-hospital fs-5"></i>
                    </span>
                    <span class="fw-bold text-white" style="letter-spacing: .06em; text-transform: uppercase; font-size: 1rem;">
                        BỆNH VIỆN PHÚC AN
                    </span>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid rgba(255,255,255,0.3) !important;">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                {{-- CENTER NAV LINKS --}}
                <ul class="navbar-nav mx-lg-auto align-items-lg-center mb-2 mb-lg-0 gap-lg-1">
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link text-white {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('introduces.index') ? 'active fw-semibold' : '' }}" href="{{ route('introduces.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('services.index') ? 'active fw-semibold' : '' }}" href="{{ route('services.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('departments.index') ? 'active fw-semibold' : '' }}" href="{{ route('departments.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Chuyên khoa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('doctors.index') ? 'active fw-semibold' : '' }}" href="{{ route('doctors.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Đội ngũ bác sĩ</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a
                            href="{{ route('modal.appointment') }}"
                            class="btn btn-light d-flex align-items-center px-4 py-2 rounded-pill shadow-lg fw-semibold"
                            style="font-weight: 600; gap: 6px; transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                        >
                            <i class="bi bi-calendar-check"></i>
                            <span>Đặt lịch khám</span>
                        </a>
                    </li>

                    @auth
                        {{-- MOBILE USER LINKS --}}
                        <li class="nav-item d-lg-none">
                            <a class="nav-link text-white" href="{{ route('appointments.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">
                                <i class="fas fa-calendar-alt me-1"></i>Lịch hẹn của tôi
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link text-white" href="{{ route('profile.edit') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">
                                <i class="fas fa-user me-1"></i>Thông tin cá nhân
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white text-start w-100" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">
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
                            <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-4 shadow-sm fw-semibold" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </div>

                        {{-- Mobile auth dropdown --}}
                        <div class="dropdown d-lg-none ms-2">
                            <button class="btn btn-light rounded-pill dropdown-toggle" type="button" id="mobileAuthDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> Tài khoản
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0" aria-labelledby="mobileAuthDropdown">
                                <li>
                                    <a class="dropdown-item rounded-3" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2 text-primary"></i> Đăng nhập
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
                        <a href="{{ route('appointments.index') }}" class="btn btn-link position-relative me-2 d-none d-lg-inline text-white" title="Lịch hẹn của tôi" style="color: #ffffff !important; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';">
                            <i class="fas fa-bell fa-lg" style="color: #ffffff !important;"></i>
                            @if($pendingAppointmentsCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
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
                                    : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                            @endphp
                            <button class="btn btn-light rounded-pill dropdown-toggle d-flex align-items-center shadow-sm" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">
                                <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle me-2 border border-2 border-primary" width="32" height="32" style="object-fit: cover;">
                                <span class="fw-semibold">{{ Str::limit(Auth::user()->name, 15) }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0" aria-labelledby="userDropdown" style="margin-top: 10px;">
                                <li class="dropdown-header bg-light rounded-top-4 p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle border border-2 border-primary" width="48" height="48" style="object-fit: cover;">
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
                                <li>
                                    <a class="dropdown-item rounded-3 mx-2" href="{{ route('profile.edit') }}" style="transition: all 0.3s ease;" onmouseover="this.style.background='#f8f9ff'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
                                        <i class="fas fa-user me-2 text-primary"></i>Thông tin cá nhân
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 mx-2 position-relative" href="{{ route('appointments.index') }}" style="transition: all 0.3s ease;" onmouseover="this.style.background='#f8f9ff'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Lịch hẹn của tôi
                                        @if($pendingAppointmentsCount > 0)
                                            <span class="badge bg-danger rounded-pill ms-2">{{ $pendingAppointmentsCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider my-2"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger rounded-3 mx-2" style="transition: all 0.3s ease;" onmouseover="this.style.background='#fff5f5'; this.style.paddingLeft='20px';" onmouseout="this.style.background='transparent'; this.style.paddingLeft='16px';">
                                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        {{-- Mobile user icon --}}
                        <div class="d-lg-none ms-1">
                            <a href="{{ route('profile.edit') }}" class="btn btn-light rounded-pill position-relative">
                                <i class="fas fa-user"></i>
                                @if($pendingAppointmentsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        {{ $pendingAppointmentsCount }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>