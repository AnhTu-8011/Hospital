@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('doctor.dashboard') }}" class="text-decoration-none text-dark">Bảng điều khiển</a> / Lịch sử khám
        </h1>
        <div>
            <button class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm ms-2" onclick="window.location.reload()">
                <i class="fas fa-sync-alt fa-sm text-white-50"></i> Làm mới
            </button>
        </div>
    </div>

    <!-- Bảng lịch sử khám -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">📋 Lịch sử khám bệnh</h6>
        </div>
        <div class="card-body">
            @php
                $completedRecords = $medicalRecords->filter(function($record) {
                    return ($record->appointment->status ?? null) === 'completed';
                });
            @endphp
            @if($completedRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Mã khám</th>
                                <th>Bệnh nhân</th>
                                <th>Ngày khám</th>
                                <th>Dịch vụ</th>
                                <th>Chẩn đoán</th>
                                <th>Kết luận bác sĩ</th>
                                <th>Đơn thuốc</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completedRecords as $index => $record)
                                <tr class="{{ ($record->appointment->status ?? '') === 'completed' ? 'table-success' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>#{{ str_pad($record->appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $record->appointment->patient->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($record->appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $record->appointment->service->name ?? 'Không rõ' }}</td>
                                    <td>{{ $record->diagnosis ?? '-' }}</td>
                                    <td>{{ $record->doctor_conclusion ?? '-' }}</td>
                                    <td>
                                        @php
                                            $pres = is_array($record->prescription)
                                                ? $record->prescription
                                                : ($record->prescription ? (array) json_decode($record->prescription, true) : []);
                                        @endphp
                                        @if(!empty($pres))
                                            <ul class="mb-0">
                                                @foreach($pres as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if(($record->appointment->status ?? '') === 'completed')
                                            <span class="badge bg-success">Đã khám</span>
                                        @elseif(($record->appointment->status ?? '') === 'confirmed')
                                            <span class="badge bg-info">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-secondary">Khác</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('doctor.patient.record', $record->appointment->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Chưa có lịch sử khám nào.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
