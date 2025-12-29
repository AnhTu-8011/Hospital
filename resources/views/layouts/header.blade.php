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
                        <a class="nav-link text-white {{ request()->routeIs('advisor.index') ? 'active fw-semibold' : '' }}" href="{{ route('advisor.index') }}" style="color: #ffffff !important; padding: 10px 14px; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.15)';" onmouseout="this.style.background='transparent';">Tư vấn triệu chứng</a>
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
                            $upcomingAppointments = collect();
                            if (Auth::check() && Auth::user()->patient) {
                                // Đếm tổng số lịch hẹn trước khi limit
                                $pendingAppointmentsCount = \App\Models\Appointment::where('patient_id', Auth::user()->patient->id)
                                    ->whereDate('appointment_date', '>=', \Carbon\Carbon::today())
                                    ->whereIn('status', ['pending','confirmed'])
                                    ->count();
                                
                                // Lấy tất cả lịch hẹn để hiển thị (không giới hạn hoặc giới hạn lớn hơn)
                                $upcomingAppointments = \App\Models\Appointment::with(['doctor.user', 'doctor.department', 'service'])
                                    ->where('patient_id', Auth::user()->patient->id)
                                    ->whereDate('appointment_date', '>=', \Carbon\Carbon::today())
                                    ->whereIn('status', ['pending','confirmed'])
                                    ->orderBy('appointment_date', 'asc')
                                    ->orderBy('id', 'asc')
                                    ->get(); // Bỏ limit để hiển thị tất cả
                            }
                        @endphp

                        {{-- Notifications --}}
                        <div class="position-relative me-2 d-none d-lg-inline"
                             onmouseover="var d=this.querySelector('.notification-dropdown'); if(d){d.style.display='block';} this.querySelector('a').style.transform='scale(1.1)';"
                             onmouseout="var d=this.querySelector('.notification-dropdown'); if(d){d.style.display='none';} this.querySelector('a').style.transform='scale(1)';">
                            <a href="{{ route('appointments.index') }}" class="btn btn-link position-relative text-white" title="Lịch hẹn của tôi" style="color: #ffffff !important; transition: all 0.3s ease;">
                                <i class="fas fa-bell fa-lg" style="color: #ffffff !important;"></i>
                                @if($pendingAppointmentsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                        {{ $pendingAppointmentsCount }}
                                    </span>
                                @endif
                            </a>

                            @if($pendingAppointmentsCount > 0)
                                <div class="notification-dropdown position-absolute end-0 mt-2 bg-white text-dark rounded-3 shadow-lg p-3"
                                     style="min-width: 320px; max-width: 400px; max-height: 500px; display: none; z-index: 1050; overflow: hidden; flex-direction: column;">
                                    <div class="fw-semibold mb-3 d-flex align-items-center" style="flex-shrink: 0;">
                                        <i class="fas fa-calendar-check me-2 text-primary"></i>
                                        Lịch hẹn sắp tới ({{ $pendingAppointmentsCount }})
                                    </div>
                                    <ul class="list-unstyled mb-0 small" style="max-height: 350px; overflow-y: auto; overflow-x: hidden; padding-right: 8px;">
                                        @foreach($upcomingAppointments as $appointment)
                                            <li class="mb-3 p-2 rounded" style="background-color: #f8f9fa; transition: all 0.2s ease;" 
                                                onmouseover="this.style.backgroundColor='#e9ecef';" 
                                                onmouseout="this.style.backgroundColor='#f8f9fa';">
                                                <a href="{{ route('appointments.show', $appointment->id) }}" class="text-decoration-none text-dark d-block">
                                                    {{-- Mã lịch hẹn và trạng thái --}}
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-primary rounded-pill px-2 py-1">
                                                            #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                                        </span>
                                                        @if($appointment->status === 'pending')
                                                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Chờ duyệt</span>
                                                        @elseif($appointment->status === 'confirmed')
                                                            <span class="badge bg-success rounded-pill px-2 py-1">Đã duyệt</span>
                                                        @else
                                                            <span class="badge bg-secondary rounded-pill px-2 py-1">{{ ucfirst($appointment->status) }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    {{-- Ngày khám và ca khám --}}
                                                    <div class="fw-semibold mb-1">
                                                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                                        Ngày khám: {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                                    </div>
                                                    
                                                    @if(!empty($appointment->medical_examination))
                                                        <div class="mb-1">
                                                            <i class="fas fa-clock me-1 text-info"></i>
                                                            {{ $appointment->medical_examination }}
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Bác sĩ --}}
                                                    @if($appointment->doctor && $appointment->doctor->user)
                                                        <div class="mb-1">
                                                            <i class="fas fa-user-md me-1 text-success"></i>
                                                            BS. {{ $appointment->doctor->user->name }}
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Khoa --}}
                                                    @if($appointment->doctor && $appointment->doctor->department)
                                                        <div class="mb-1 text-muted small">
                                                            <i class="fas fa-building me-1"></i>
                                                            {{ $appointment->doctor->department->name }}
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Dịch vụ --}}
                                                    @if($appointment->service)
                                                        <div class="mb-1 text-muted small">
                                                            <i class="fas fa-stethoscope me-1"></i>
                                                            {{ $appointment->service->name }}
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Trạng thái thanh toán --}}
                                                    @if($appointment->payment_status === 'success')
                                                        <div class="mt-1">
                                                            <span class="badge bg-success rounded-pill px-2 py-1">
                                                                <i class="fas fa-check-circle me-1"></i>Đã thanh toán
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="mt-1">
                                                            <span class="badge bg-danger rounded-pill px-2 py-1">
                                                                <i class="fas fa-exclamation-circle me-1"></i>Chưa thanh toán
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if(!$loop->last)
                                                        <hr class="my-2" style="border-top: 1px solid #dee2e6;">
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-3 text-end border-top pt-2" style="flex-shrink: 0;">
                                        <a href="{{ route('appointments.index') }}" class="text-primary small fw-semibold">
                                            <i class="fas fa-arrow-right me-1"></i>Xem tất cả lịch hẹn
                                        </a>
                                    </div>
                                </div>
                                
                                {{-- Custom Scrollbar Style --}}
                                <style>
                                    .notification-dropdown ul::-webkit-scrollbar {
                                        width: 8px;
                                    }
                                    
                                    .notification-dropdown ul::-webkit-scrollbar-track {
                                        background: #f1f1f1;
                                        border-radius: 10px;
                                    }
                                    
                                    .notification-dropdown ul::-webkit-scrollbar-thumb {
                                        background: #888;
                                        border-radius: 10px;
                                    }
                                    
                                    .notification-dropdown ul::-webkit-scrollbar-thumb:hover {
                                        background: #555;
                                    }
                                    
                                    /* Firefox */
                                    .notification-dropdown ul {
                                        scrollbar-width: thin;
                                        scrollbar-color: #888 #f1f1f1;
                                    }
                                </style>
                            @endif
                        </div>

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