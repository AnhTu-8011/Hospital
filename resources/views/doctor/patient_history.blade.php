@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">LỊCH SỬ KHÁM</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-history me-2 text-primary"></i>Lịch sử khám bệnh
        </h1>
        </div>
        <div>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm me-2">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Làm mới
            </button>
        </div>
    </div>

    <!-- Bảng lịch sử khám -->
    <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-clipboard-list me-2"></i>Lịch sử khám bệnh
            </h6>
        </div>
        <div class="card-body p-0">
            @php
                $completedRecords = $medicalRecords->filter(function($record) {
                    return ($record->appointment->status ?? null) === 'completed';
                });
            @endphp
            @if($completedRecords->count() > 0)
                <div class="table-responsive p-4">
                    <table class="table align-middle table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <tr>
                                <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                                <th class="text-center fw-semibold py-3" style="width: 120px;">Mã khám</th>
                                <th class="fw-semibold py-3">Bệnh nhân</th>
                                <th class="fw-semibold py-3">Ngày khám</th>
                                <th class="fw-semibold py-3">Dịch vụ</th>
                                <th class="fw-semibold py-3">Chẩn đoán</th>
                                <th class="fw-semibold py-3">Kết luận</th>
                                <th class="fw-semibold py-3">Đơn thuốc</th>
                                <th class="text-center fw-semibold py-3" style="width: 120px;">Trạng thái</th>
                                <th class="text-center fw-semibold py-3" style="width: 120px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completedRecords as $index => $record)
                                <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                    <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                            #{{ str_pad($record->appointment->id, 6, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        <i class="fas fa-user me-2 text-primary"></i>{{ $record->appointment->patient->name ?? 'N/A' }}
                                    </td>
                                    <td class="text-muted">{{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                            {{ $record->appointment->service->name ?? 'Không rõ' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $record->diagnosis ?? '-' }}</td>
                                    <td class="text-muted small">{{ $record->doctor_conclusion ?? '-' }}</td>
                                    <td>
                                        @php
                                            $pres = is_array($record->prescription)
                                                ? $record->prescription
                                                : ($record->prescription ? (array) json_decode($record->prescription, true) : []);
                                        @endphp
                                        @if(!empty($pres))
                                            <ul class="mb-0 small" style="max-width: 200px;">
                                                @foreach($pres as $item)
                                                    <li class="text-muted">{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(($record->appointment->status ?? '') === 'completed')
                                            <span class="badge bg-success rounded-pill px-3 py-2">Đã khám</span>
                                        @elseif(($record->appointment->status ?? '') === 'confirmed')
                                            <span class="badge bg-warning rounded-pill px-3 py-2">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3 py-2">Khác</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('doctor.patient.record', $record->appointment->id) }}" 
                                           class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                            <i class="fas fa-eye me-1"></i>Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0 mx-4 my-4 rounded-4 border-0 shadow-sm">
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-3x mb-3 text-primary" style="opacity: 0.5;"></i>
                        <p class="mb-0 fw-semibold">Chưa có lịch sử khám nào.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table-row-hover:hover {
    background-color: #f8f9ff !important;
    transform: scale(1.01);
}
</style>
@endsection
