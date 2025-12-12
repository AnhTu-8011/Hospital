@extends('layouts.admin')

@section('title', 'Chi tiết hồ sơ bệnh án')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">CHI TIẾT HỒ SƠ BỆNH ÁN</p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-notes-medical me-2 text-primary"></i>Hồ sơ #{{ str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT) }}
            </h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.medical-records.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-primary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-user me-2"></i>Thông tin bệnh nhân
                </div>
                <div class="card-body">
                    @php($patient = $medicalRecord->patient ?? optional($medicalRecord->appointment)->patient)
                    <p class="mb-2"><strong>Họ tên:</strong> {{ $patient->name ?? '-' }}</p>
                    <p class="mb-2"><strong>Số điện thoại:</strong> {{ $patient->phone ?? '-' }}</p>
                    <p class="mb-2"><strong>Mã bảo hiểm:</strong> {{ $patient->insurance_number ?? '-' }}</p>
                    <p class="mb-0"><strong>Ngày sinh:</strong>
                        @if(!empty($patient->birthdate))
                            {{ \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-info text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-calendar-check me-2"></i>Thông tin lịch hẹn
                </div>
                <div class="card-body">
                    @php($appointment = $medicalRecord->appointment)
                    <p class="mb-2"><strong>Mã lịch hẹn:</strong>
                        @if($appointment)
                            #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                        @else
                            -
                        @endif
                    </p>
                    <p class="mb-2"><strong>Bác sĩ phụ trách:</strong> {{ optional(optional($appointment)->doctor->user ?? null)->name ?? '-' }}</p>
                    <p class="mb-2"><strong>Dịch vụ:</strong> {{ optional(optional($appointment)->service ?? null)->name ?? '-' }}</p>
                    <p class="mb-0"><strong>Ngày khám:</strong>
                        @if(optional($appointment)->appointment_date)
                            {{ $appointment->appointment_date->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-secondary text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-stethoscope me-2"></i>Khám lâm sàng / Mô tả
                </div>
                <div class="card-body">
                    <p class="mb-0">{!! nl2br(e($medicalRecord->description ?? 'Chưa cập nhật')) !!}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-warning text-dark rounded-top-4 fw-semibold">
                    <i class="fas fa-diagnoses me-2"></i>Chẩn đoán
                </div>
                <div class="card-body">
                    <p class="mb-0">{!! nl2br(e($medicalRecord->diagnosis ?? 'Chưa cập nhật')) !!}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-success text-white rounded-top-4 fw-semibold">
                    <i class="fas fa-notes-medical me-2"></i>Kết luận bác sĩ
                </div>
                <div class="card-body">
                    <p class="mb-0">{!! nl2br(e($medicalRecord->doctor_conclusion ?? 'Chưa cập nhật')) !!}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-light rounded-top-4 fw-semibold">
                    <i class="fas fa-pills me-2"></i>Toa thuốc
                </div>
                <div class="card-body">
                    @if($items->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Thuốc</th>
                                        <th>Liều dùng</th>
                                        <th>Số lần/ngày</th>
                                        <th>Thời gian</th>
                                        <th>Số lượng</th>
                                        <th>Đơn vị</th>
                                        <th>Cách dùng</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{ optional($item->medicine)->name ?? '-' }}</td>
                                            <td>{{ $item->dosage ?? '-' }}</td>
                                            <td>{{ $item->frequency ?? '-' }}</td>
                                            <td>{{ $item->duration ?? '-' }}</td>
                                            <td>{{ $item->quantity ?? '-' }}</td>
                                            <td>{{ $item->unit ?? '-' }}</td>
                                            <td>{{ $item->usage ?? '-' }}</td>
                                            <td>{{ $item->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @php($prescription = $medicalRecord->prescription ?? [])
                        @if(is_string($prescription))
                            <p class="mb-0">{!! nl2br(e($prescription)) !!}</p>
                        @elseif(is_array($prescription) && count($prescription))
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Thuốc</th>
                                            <th>Liều dùng</th>
                                            <th>Số lần/ngày</th>
                                            <th>Thời gian</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prescription as $item)
                                            <tr>
                                                <td>{{ $item['drug_name'] ?? '-' }}</td>
                                                <td>{{ $item['dosage'] ?? '-' }}</td>
                                                <td>{{ $item['frequency'] ?? '-' }}</td>
                                                <td>{{ $item['duration'] ?? '-' }}</td>
                                                <td>{{ $item['note'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="mb-0 text-muted">Chưa có toa thuốc.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
