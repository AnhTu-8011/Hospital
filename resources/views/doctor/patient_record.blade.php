@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <!-- Tiêu đề -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-notes-medical"></i> Hồ sơ khám bệnh
        </h1>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Hồ sơ khám bệnh -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-user-md"></i> Thông tin hồ sơ khám bệnh
        </div>
        <div class="card-body">

            <!-- Thông tin bệnh nhân -->
            <h5 class="text-primary mb-3"><i class="fas fa-user"></i> Thông tin bệnh nhân</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Họ và tên:</strong> {{ $patient->name ?? 'Không rõ' }}</div>
                <div class="col-md-2 mb-3">
                    <strong>Ngày sinh:</strong>
                    {{ $patient->birthdate ? \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') : 'N/A' }}
                </div>
                <div class="col-md-3 mb-3">
                    <strong>Giới tính:</strong>
                    @if($patient->gender === 'male') Nam
                    @elseif($patient->gender === 'female') Nữ
                    @else Không rõ
                    @endif
                </div>
                <div class="col-md-3 mb-3"><strong>Số điện thoại:</strong> {{ $patient->phone ?? 'N/A' }}</div>
                <div class="col-md-4 mb-3"><strong>Số bảo hiểm:</strong> {{ $patient->insurance_number ?? 'Không có' }}</div>
                <div class="col-md-8 mb-3"><strong>Địa chỉ:</strong> {{ $patient->address ?? 'Không rõ' }}</div>
            </div>

            <hr>

            <!-- Thông tin khám bệnh -->
            <h5 class="text-info mb-3"><i class="fas fa-stethoscope"></i> Thông tin khám bệnh</h5>
            <div class="row">
                <div class="col-md-4 mb-3"><strong>Bác sĩ phụ trách:</strong> {{ $appointment->doctor->user->name ?? 'Không rõ' }}</div>
                <div class="col-md-4 mb-3"><strong>Dịch vụ khám:</strong> {{ $appointment->service->name ?? 'Không rõ' }}</div>
                <div class="col-md-4 mb-3"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                <div class="col-md-4 mb-3"><strong>Ca khám:</strong> {{ $appointment->medical_examination ?? 'Không xác định' }}</div>
                <div class="col-md-8 mb-3"><strong>Ghi chú:</strong> {{ $appointment->note ?? 'Không có ghi chú' }}</div>
            </div>

            <hr>

            <!-- Yêu cầu xét nghiệm -->
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="fas fa-vial me-1"></i> Yêu cầu xét nghiệm</h5>
                @php
                    $testTypes = \App\Models\TestType::with('department')->orderBy('name')->get();
                    $allDepartments = \App\Models\Department::orderBy('name')->get();
                @endphp
                <form action="{{ route('doctor.lab_tests.store', ['record' => $record->id]) }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Chọn loại xét nghiệm</label>
                        <select id="testTypeSelect" class="form-select">
                            <option value="" data-name="" data-dept="">-- Chọn loại --</option>
                            @foreach($testTypes as $tt)
                                <option value="{{ $tt->id }}" data-name="{{ $tt->name }}" data-dept="{{ $tt->department_id }}">
                                    {{ $tt->name }} @if($tt->department) ({{ $tt->department->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Chọn để tự động điền Tên xét nghiệm và Khoa.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tên xét nghiệm</label>
                        <input type="text" name="test_name" id="testNameInput" class="form-control" placeholder="Nhập tên xét nghiệm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Khoa phụ trách</label>
                        <select name="department_id" id="departmentSelect" class="form-select" required>
                            <option value="">-- Chọn khoa --</option>
                            @foreach($allDepartments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Gửi yêu cầu xét nghiệm
                        </button>
                    </div>
                </form>
            </div>

            <hr>

            <!-- Mô tả bệnh -->
            @php
                $labTests = \App\Models\LabTest::where('medical_record_id', $record->id)
                    ->where('status', 'completed')
                    ->get();
            @endphp
            <form action="{{ route('doctor.records.update', $record->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @if(!empty($record->image) || (!empty($record->images) && is_array($record->images) && count($record->images)) || ($labTests && $labTests->count()))
                    <h5 class="text-warning mb-3"><i class="fas fa-clipboard-list"></i> Ảnh xét nghiệm nếu có</h5>
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
                @endif

            <hr>

            <!-- Gói dịch vụ cho lần khám này -->
            @php
                $svc = $appointment->service ?? null;
            @endphp
            @if($svc)
                @php
                    $descLines = preg_split('/\r\n|\r|\n/', $svc->description ?? '');
                    $descLines = array_values(array_filter($descLines, function ($line) {
                        return trim($line) !== '';
                    }));
                    $selectedItems = [];
                    if (!empty($record->description)) {
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
                <div class="mb-4">
                    <h5 class="text-primary mb-3"><i class="fas fa-list-ul"></i> Gói dịch vụ bệnh nhân đã chọn</h5>
                    <div class="border rounded p-3">
                        <div class="fw-bold mb-2">
                            {{ $svc->name }} 
                        </div>
                        @if(!empty($descLines))
                            <ul class="mb-0 mt-1 small list-unstyled">
                                @foreach($descLines as $idx => $line)
                                    @php
                                        $isChecked = in_array($line, $selectedItems, true);
                                    @endphp
                                    <li class="mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="service_items[]" value="{{ $line }}" id="svc_item_{{ $svc->id }}_{{ $idx }}" {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="svc_item_{{ $svc->id }}_{{ $idx }}">{{ $line }}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if($svc->department)
                            <div class="mt-1 small text-muted">Khoa: {{ $svc->department->name }}</div>
                        @endif
                    </div>
                </div>
            @endif

            <hr>

            <!-- Chuẩn đoán -->
            <h5 class="text-success mb-3"><i class="fas fa-diagnoses"></i> Chuẩn đoán & Kết luận</h5>

                <!-- Chuẩn đoán -->
                <div class="mb-3">
                    <label for="diagnosis" class="form-label fw-bold">Chuẩn đoán ban đầu:</label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control" rows="3" placeholder="Nhập chuẩn đoán...">{{ old('diagnosis', $record->diagnosis ?? '') }}</textarea>
                </div>

                <!-- Kết luận -->
                <div class="mb-3">
                    <label for="doctor_conclusion" class="form-label fw-bold">Kết luận bác sĩ:</label>
                    <textarea id="doctor_conclusion" name="doctor_conclusion" class="form-control" rows="3" placeholder="Nhập kết luận bác sĩ...">{{ old('doctor_conclusion', $record->doctor_conclusion ?? '') }}</textarea>
                </div>

                <!-- Trạng thái lịch hẹn -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Trạng thái lịch hẹn:</label>
                    <select id="status" name="status" class="form-select">
                        <option value="confirmed" {{ ($appointment->status ?? '') === 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ ($appointment->status ?? '') === 'completed' ? 'selected' : '' }}>Đã khám</option>
                    </select>
                </div>

                <!-- Toa thuốc -->
                <div class="mb-3">
                    <label for="prescription" class="form-label fw-bold">Toa thuốc (mỗi dòng 1 loại thuốc):</label>
                    <textarea id="prescription" name="prescription" class="form-control" rows="4" placeholder="Ví dụ: Paracetamol 500mg - Uống 2 lần/ngày
            Vitamin C 1000mg - Sáng 1 viên">{{ old('prescription', isset($record->prescription) ? (is_array($record->prescription) ? implode("\n", $record->prescription) : ( $record->prescription ? implode("\n", (array) json_decode($record->prescription, true)) : '')) : '') }}</textarea>
                </div>

                <!-- Nút lưu -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Lưu thông tin
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<style>
    .thumb { width: 140px; height: 140px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; }
    #imagesPreview img, #imagePreview img { margin-right: 8px; margin-bottom: 8px; }
</style>
@push('scripts')
<script src="{{ asset('js/patient_record.js') }}"></script>
@endpush
@endsection
