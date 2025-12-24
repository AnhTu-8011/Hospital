@extends('layouts.admin')

@section('title', 'Lịch khám bác sĩ ' . $doctor->user->name)

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                LỊCH KHÁM BÁC SĨ
            </p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-calendar-check me-2 text-primary"></i>
                Lịch khám bác sĩ
            </h1>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Quay lại danh sách
        </a>
    </div>

    {{-- Schedule Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user-md me-2"></i>
                Thông tin bác sĩ - Ngày {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            {{-- Doctor Info --}}
            <div class="bg-light rounded-4 p-4 mb-4">
                <div class="row g-3">
                    {{-- Bác sĩ --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-md text-primary me-3 fs-4"></i>
                            <div>
                                <small class="text-muted d-block">Bác sĩ</small>
                                <strong class="text-dark fs-5">{{ $doctor->user->name }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Chuyên khoa --}}
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building text-primary me-3 fs-4"></i>
                            <div>
                                <small class="text-muted d-block">Chuyên khoa</small>
                                <strong class="text-dark fs-5">{{ $doctor->department->name ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Schedule Stats --}}
            <div class="row g-4">
                {{-- Ca sáng --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body p-4 text-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-sun fa-2x mb-3"></i>
                                    <h5 class="fw-bold mb-2">Ca sáng</h5>
                                    <p class="mb-0 fs-4 fw-bold">{{ $morningCount }}/25 ca đã đặt</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-3 p-3" style="backdrop-filter: blur(10px);">
                                    <i class="fas fa-sun fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ca chiều --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body p-4 text-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-moon fa-2x mb-3"></i>
                                    <h5 class="fw-bold mb-2">Ca chiều</h5>
                                    <p class="mb-0 fs-4 fw-bold">{{ $afternoonCount }}/25 ca đã đặt</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-3 p-3" style="backdrop-filter: blur(10px);">
                                    <i class="fas fa-moon fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
