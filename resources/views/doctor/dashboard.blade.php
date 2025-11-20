@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <!-- Error Alert -->
    @if(!empty($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Heading + Date Filter -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('doctor.dashboard') }}" class="text-decoration-none text-dark">Bảng điều khiển</a>
        </h1>
        <form method="get" action="{{ route('doctor.dashboard') }}" class="d-flex align-items-center gap-2">
            <label for="date" class="me-2 mb-0">Chọn ngày:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate ?? now()->format('Y-m-d') }}" class="form-control" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(7)->format('Y-m-d') }}">
            <button class="btn btn-primary">Xem</button>
        </form>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row">
        <!-- Tổng số lịch hẹn hôm nay -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng số lịch hẹn ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointments->where('status', 'confirmed')->count() }}</div>
                        <small class="text-muted">
                            Sáng: 
                            <span class="fw-bold text-primary">
                                {{ $appointments->where('medical_examination', 'Ca sáng (07:30 - 11:30)')->where('status', 'confirmed')->count() }}
                            </span>
                            &nbsp;|&nbsp;
                            Chiều:
                            <span class="fw-bold text-success">
                                {{ $appointments->where('medical_examination', 'Ca chiều (13:00 - 17:00)')->where('status', 'confirmed')->count() }}
                            </span>
                        </small>
                    </div>
                    <i class="fas fa-calendar-day fa-2x text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Số bệnh nhân chờ khám -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 card-hover">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Số bệnh nhân chờ khám
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $appointments->where('status', 'confirmed')->count() }}
                        </div>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                </div>
            </div>
        </div>

        <!-- Số bệnh nhân đã khám -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 card-hover">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Số bệnh nhân đã khám
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $appointments->where('status', 'completed')->count() }}
                        </div>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch hẹn hôm nay - CA SÁNG -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                🕗 Lịch hẹn ca sáng (07:30 - 11:30)
            </h6>
        </div>
        <div class="card-body">
            @php
                $morningAppointments = $appointments
                    ->where('medical_examination', 'Ca sáng (07:30 - 11:30)')
                    ->where('status', 'confirmed');
            @endphp
            @if($morningAppointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Mã lịch hẹn</th>
                                <th>Bệnh nhân</th>
                                <th>Số điện thoại</th>
                                <th>Dịch vụ</th>
                                <th>Ngày hẹn</th>
                                <th>Ca khám</th>
                                <th>Ghi chú</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($morningAppointments as $index => $appointment)
                                <tr class="{{ $appointment->status === 'completed' ? 'table-success' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                                    <td>{{ $appointment->service->name ?? 'Không rõ' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->medical_examination }}</td>
                                    <td>{{ $appointment->note ?? '' }}</td>
                                    <td>
                                        @if($appointment->status === 'confirmed')
                                            <span class="badge bg-info">Đã duyệt</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-success">Đã khám</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctor.patient.record', $appointment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Khám
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Không có lịch hẹn ca sáng ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}.
                </div>
            @endif
        </div>
    </div>

    <!-- Lịch hẹn hôm nay - CA CHIỀU -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                🌇 Lịch hẹn ca chiều (13:00 - 17:00)
            </h6>
        </div>
        <div class="card-body">
            @php
                $afternoonAppointments = $appointments
                    ->where('medical_examination', 'Ca chiều (13:00 - 17:00)')
                    ->where('status', 'confirmed');
            @endphp
            @if($afternoonAppointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Mã lịch hẹn</th>
                                <th>Bệnh nhân</th>
                                <th>Số điện thoại</th>
                                <th>Dịch vụ</th>
                                <th>Ngày hẹn</th>
                                <th>Ca khám</th>
                                <th>Ghi chú</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($afternoonAppointments as $index => $appointment)
                                <tr class="{{ $appointment->status === 'completed' ? 'table-success' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $appointment->patient->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->patient->phone ?? 'N/A' }}</td>
                                    <td>{{ $appointment->service->name ?? 'Không rõ' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->medical_examination }}</td>
                                    <td>{{ $appointment->note ?? '' }}</td>
                                    <td>
                                        @if($appointment->status === 'confirmed')
                                            <span class="badge bg-info">Đã duyệt</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-success">Đã khám</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctor.patient.record', $appointment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Khám
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Không có lịch hẹn ca chiều ngày {{ \Carbon\Carbon::parse($selectedDate ?? now())->format('d/m/Y') }}.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
