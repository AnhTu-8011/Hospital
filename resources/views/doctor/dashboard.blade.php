@extends('layouts.doctor')

@section('content')
    <div class="container-fluid">
        {{-- Page Header + Date Filter --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                    BẢNG ĐIỀU KHIỂN
                </p>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                    Bảng điều khiển bác sĩ
                </h1>
            </div>
            <form method="get" action="{{ route('doctor.dashboard') }}" class="d-flex align-items-center gap-2">
                <label for="date" class="mb-0 fw-semibold text-muted">Chọn ngày:</label>
                <input type="date"
                       id="date"
                       name="date"
                       value="{{ $selectedDate ?? now()->format('Y-m-d') }}"
                       class="form-control rounded-pill border-2"
                       min="{{ now()->format('Y-m-d') }}"
                       max="{{ now()->addDays(7)->format('Y-m-d') }}"
                       style="width: 180px;">
                <button class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-search me-2"></i>
                    Xem
                </button>
            </form>
        </div>

        {{-- Statistics Overview --}}
        <div class="row g-4 mb-4">
            {{-- Total Appointments Card --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(102, 126, 234, 0.3)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)';">
                    <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-white-50 text-uppercase fw-semibold small mb-2" style="opacity: 0.9;">
                                    Tổng số lịch hẹn
                                </p>
                                <h2 class="fw-bold text-white mb-0" style="font-size: 2.5rem;">
                                    {{ $appointments->where('status', 'confirmed')->count() }}
                                </h2>
                                <small class="text-white-50 d-block mt-2">
                                    <i class="fas fa-sun me-1"></i>
                                    Sáng:
                                    <span class="fw-bold text-white">
                                        {{ $appointments->where('medical_examination', 'Ca sáng (07:30 - 11:30)')->where('status', 'confirmed')->count() }}
                                    </span>
                                    &nbsp;|&nbsp;
                                    <i class="fas fa-moon me-1"></i>
                                    Chiều:
                                    <span class="fw-bold text-white">
                                        {{ $appointments->where('medical_examination', 'Ca chiều (13:00 - 17:00)')->where('status', 'confirmed')->count() }}
                                    </span>
                                </small>
                            </div>
                        </div>
                        <p class="text-white-50 small mb-0">
                            Ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="position-absolute bottom-0 end-0" style="opacity: 0.1; z-index: 1;">
                        <i class="fas fa-calendar-day" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </div>

            {{-- Waiting Patients Card --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative"
                     style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(245, 87, 108, 0.3)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)';">
                    <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-white-50 text-uppercase fw-semibold small mb-2" style="opacity: 0.9;">
                                    Bệnh nhân chờ khám
                                </p>
                                <h2 class="fw-bold text-white mb-0" style="font-size: 2.5rem;">
                                    {{ $appointments->where('status', 'confirmed')->count() }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 end-0" style="opacity: 0.1; z-index: 1;">
                        <i class="fas fa-hourglass-half" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </div>

            {{-- Completed Patients Card --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative"
                     style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(79, 172, 254, 0.3)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.15)';">
                    <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="text-white-50 text-uppercase fw-semibold small mb-2" style="opacity: 0.9;">
                                    Bệnh nhân đã khám
                                </p>
                                <h2 class="fw-bold text-white mb-0" style="font-size: 2.5rem;">
                                    {{ $appointments->where('status', 'completed')->count() }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute bottom-0 end-0" style="opacity: 0.1; z-index: 1;">
                        <i class="fas fa-check-circle" style="font-size: 8rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Morning Appointments (07:30 - 11:30) --}}
        <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-sun me-2"></i>
                    Lịch hẹn ca sáng (07:30 - 11:30)
                </h6>
            </div>
            <div class="card-body p-0">
                @php
                    $morningAppointments = $appointments
                        ->where('medical_examination', 'Ca sáng (07:30 - 11:30)')
                        ->where('status', 'confirmed');
                @endphp
                @if($morningAppointments->count() > 0)
                    <div class="table-responsive p-4">
                        <table class="table align-middle table-hover mb-0">
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <tr>
                                    <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Mã lịch hẹn</th>
                                    <th class="fw-semibold py-3">Bệnh nhân</th>
                                    <th class="fw-semibold py-3">Số điện thoại</th>
                                    <th class="fw-semibold py-3">Dịch vụ</th>
                                    <th class="fw-semibold py-3">Ngày hẹn</th>
                                    <th class="fw-semibold py-3">Ghi chú</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Trạng thái</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($morningAppointments as $index => $appointment)
                                    <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <i class="fas fa-user me-2 text-primary"></i>
                                            {{ $appointment->patient->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-muted">{{ $appointment->patient->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                                {{ $appointment->service->name ?? 'Không rõ' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-muted small">{{ $appointment->note ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($appointment->status === 'confirmed')
                                                <span class="badge bg-warning rounded-pill px-3 py-2">Đã duyệt</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="badge bg-success rounded-pill px-3 py-2">Đã khám</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('doctor.patient.record', $appointment->id) }}"
                                               class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                Khám
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0 mx-4 my-4 rounded-4 border-0 shadow-sm">
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle fa-2x mb-2 text-primary"></i>
                            <p class="mb-0 fw-semibold">
                                Không có lịch hẹn ca sáng ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Afternoon Appointments (13:00 - 17:00) --}}
        <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-moon me-2"></i>
                    Lịch hẹn ca chiều (13:00 - 17:00)
                </h6>
            </div>
            <div class="card-body p-0">
                @php
                    $afternoonAppointments = $appointments
                        ->where('medical_examination', 'Ca chiều (13:00 - 17:00)')
                        ->where('status', 'confirmed');
                @endphp
                @if($afternoonAppointments->count() > 0)
                    <div class="table-responsive p-4">
                        <table class="table align-middle table-hover mb-0">
                            <thead style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                <tr>
                                    <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Mã lịch hẹn</th>
                                    <th class="fw-semibold py-3">Bệnh nhân</th>
                                    <th class="fw-semibold py-3">Số điện thoại</th>
                                    <th class="fw-semibold py-3">Dịch vụ</th>
                                    <th class="fw-semibold py-3">Ngày hẹn</th>
                                    <th class="fw-semibold py-3">Ghi chú</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Trạng thái</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($afternoonAppointments as $index => $appointment)
                                    <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold text-dark">
                                            <i class="fas fa-user me-2 text-primary"></i>
                                            {{ $appointment->patient->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-muted">{{ $appointment->patient->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                                {{ $appointment->service->name ?? 'Không rõ' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="text-muted small">{{ $appointment->note ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($appointment->status === 'confirmed')
                                                <span class="badge bg-warning rounded-pill px-3 py-2">Đã duyệt</span>
                                            @elseif($appointment->status === 'completed')
                                                <span class="badge bg-success rounded-pill px-3 py-2">Đã khám</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('doctor.patient.record', $appointment->id) }}"
                                               class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                Khám
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0 mx-4 my-4 rounded-4 border-0 shadow-sm">
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle fa-2x mb-2 text-primary"></i>
                            <p class="mb-0 fw-semibold">
                                Không có lịch hẹn ca chiều ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .table-row-hover:hover {
            background-color: #f8f9ff !important;
            transform: scale(1.01);
        }
    </style>
@endsection
