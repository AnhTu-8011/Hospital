@extends('layouts.profile')

@section('content')
<div class="container-fluid">

    <!-- Tiêu đề -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-notes-medical"></i> Hồ sơ khám bệnh
        </h1>
        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại 
        </a>
    </div>

    <!-- Hồ sơ khám bệnh -->
    <div class="card shadow mb-4" id="printable">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user-md"></i> Thông tin hồ sơ khám bệnh</span>
            <button type="button" class="btn btn-light btn-sm text-primary" onclick="window.print()">
                <i class="fas fa-file-invoice me-1"></i> Xuất hồ sơ khám bệnh
            </button>
        </div>
        <div class="card-body">

            <!-- Thông tin bệnh nhân -->
            <h5 class="text-primary mb-3"><i class="fas fa-user"></i> Thông tin bệnh nhân</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Họ và tên:</strong> {{ optional($patient)->name ?? 'Không rõ' }}</div>
                <div class="col-md-2 mb-3">
                    <strong>Ngày sinh:</strong>
                    {{ optional($patient)->birthdate ? \Carbon\Carbon::parse(optional($patient)->birthdate)->format('d/m/Y') : 'N/A' }}
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Giới tính:</strong>
                    @if(optional($patient)->gender === 'male') Nam
                    @elseif(optional($patient)->gender === 'female') Nữ
                    @else Không rõ
                    @endif
                </div>
                <div class="col-md-3 mb-3"><strong>Số điện thoại:</strong> {{ optional($patient)->phone ?? 'N/A' }}</div>
                <div class="col-md-4 mb-3"><strong>Số bảo hiểm:</strong> {{ optional($patient)->insurance_number ?? 'Không có' }}</div>
                <div class="col-md-8 mb-3"><strong>Địa chỉ:</strong> {{ optional($patient)->address ?? 'Không rõ' }}</div>
            </div>

            <hr>

            <!-- Thông tin khám bệnh -->
            <h5 class="text-info mb-3"><i class="fas fa-stethoscope"></i> Thông tin khám bệnh</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Bác sĩ phụ trách:</strong> {{ optional(optional($appointment->doctor)->user)->name ?? 'Không rõ' }}</div>
                <div class="col-md-4 mb-3"><strong>Dịch vụ khám:</strong> {{ optional($appointment->service)->name ?? 'Không rõ' }}</div>
                <div class="col-md-4 mb-3"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                <div class="col-md-4 mb-3"><strong>Ca khám:</strong> {{ $appointment->medical_examination ?? 'Không xác định' }}</div>
                <div class="col-md-8 mb-3"><strong>Ghi chú:</strong> {{ $appointment->note ?? 'Không có ghi chú' }}</div>
            </div>

            @php
                $svc = $appointment->service ?? null;
            @endphp
            @if($svc)
                @php
                    // Các mục trong gói dịch vụ mà bác sĩ đã tích (lưu trong description)
                    $selectedItems = [];
                    if (!empty($record) && !empty($record->description)) {
                        $decoded = is_array($record->description)
                            ? $record->description
                            : json_decode($record->description, true);
                        if (is_array($decoded)) {
                            $selectedItems = array_values(array_filter($decoded, function ($v) {
                                return is_string($v) && trim($v) !== '';
                            }));
                        }
                    }
                @endphp
                <div class="mt-2 mb-3">
                    <h6 class="text-primary mb-2"><i class="fas fa-list-ul"></i> Gói dịch vụ bệnh nhân đã chọn</h6>
                    <div class="border rounded p-2">
                        <div class="fw-bold mb-1">{{ $svc->name }}</div>
                        @if(!empty($selectedItems))
                            <ul class="mb-0 small">
                                @foreach($selectedItems as $line)
                                    <li>{{ $line }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="small text-muted">Chưa có hạng mục nào được bác sĩ đánh dấu.</div>
                        @endif
                    </div>
                </div>
            @endif

            <hr>

            <!-- Mô tả bệnh -->
            @if($record)
            @php
                $labTests = \App\Models\LabTest::where('medical_record_id', $record->id)
                    ->where('status', 'completed')
                    ->get();
            @endphp
            @if(!empty($record->image) || (!empty($record->images) && is_array($record->images) && count($record->images)) || ($labTests && $labTests->count()))
            <h5 class="text-warning mb-3"><i class="fas fa-clipboard-list"></i> Ảnh xét nghiệm nếu có</h5>
            <form action="{{ route('doctor.records.update', $record->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Ảnh từ xét nghiệm (admin cập nhật) -->
                @if($labTests && $labTests->count())
                    <div class="mb-2">
                        <label class="form-label fw-bold">Ảnh từ xét nghiệm:</label>
                        @foreach($labTests as $t)
                            @if(!empty($t->image))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$t->image) }}" alt="Ảnh" class="thumb">
                                </div>
                            @endif
                            @if(!empty($t->images) && is_array($t->images))
                            <label class="form-label fw-bold">Ảnh phụ từ xét nghiệm:</label>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    @foreach($t->images as $img)
                                        <img src="{{ asset('storage/'.$img) }}" alt="Ảnh" class="thumb">
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </form>
            @endif
            <hr>
            
            <!-- Chuẩn đoán -->
            <h5 class="text-success mb-3"><i class="fas fa-diagnoses"></i> Chuẩn đoán & Kết luận</h5>

                <!-- Chuẩn đoán -->
                <div class="mb-3">
                    <label for="diagnosis" class="form-label fw-bold">Chuẩn đoán ban đầu:</label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control" rows="3" placeholder="Nhập chuẩn đoán..." readonly disabled>{{ old('diagnosis', optional($record)->diagnosis ?? '') }}</textarea>
                </div>

                <!-- Kết luận -->
                <div class="mb-3">
                    <label for="doctor_conclusion" class="form-label fw-bold">Kết luận bác sĩ:</label>
                    <textarea id="doctor_conclusion" name="doctor_conclusion" class="form-control" rows="3" placeholder="Nhập kết luận bác sĩ..." readonly disabled>{{ old('doctor_conclusion', optional($record)->doctor_conclusion ?? '') }}</textarea>
                </div>

                <!-- Trạng thái lịch hẹn -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Trạng thái lịch hẹn:</label>
                    <select id="status" name="status" class="form-select" disabled>
                        <option value="confirmed" {{ ($appointment->status ?? '') === 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ ($appointment->status ?? '') === 'completed' ? 'selected' : '' }}>Đã khám</option>
                    </select>
                </div>

                <!-- Toa thuốc -->
                <div class="mb-3">
                    <label for="prescription" class="form-label fw-bold">Toa thuốc (mỗi dòng 1 loại thuốc):</label>
                    <textarea id="prescription" name="prescription" class="form-control" rows="4" placeholder="Ví dụ: Paracetamol 500mg - Uống 2 lần/ngày
            Vitamin C 1000mg - Sáng 1 viên" readonly disabled>{{ old('prescription', isset($record->prescription) ? (is_array($record->prescription) ? implode("\n", $record->prescription) : ( $record->prescription ? implode("\n", (array) json_decode($record->prescription, true)) : '')) : '') }}</textarea>
                </div>
            @else
                <div class="alert alert-info">Chưa có hồ sơ khám bệnh.</div>
            @endif

        </div>
    </div>
</div>
<style>
    .thumb { width: 300px; height: 300px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; }
    #imagesPreview img, #imagePreview img { margin-right: 8px; margin-bottom: 8px; }
</style>
@endsection
