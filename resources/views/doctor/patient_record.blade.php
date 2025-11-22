@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <!-- Tiêu đề -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">HỒ SƠ KHÁM BỆNH</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-notes-medical me-2 text-primary"></i>Hồ sơ khám bệnh
            </h1>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <!-- Hồ sơ khám bệnh -->
    <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user-md me-2"></i>Thông tin hồ sơ khám bệnh
            </h6>
        </div>
        <div class="card-body p-4">

            <!-- Thông tin bệnh nhân -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-primary mb-3 fw-bold d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>Thông tin bệnh nhân
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Họ và tên</small>
                                <strong class="text-dark">{{ $patient->name ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-birthday-cake text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày sinh</small>
                                <strong class="text-dark">{{ $patient->birthdate ? \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') : 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-venus-mars text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Giới tính</small>
                                <strong class="text-dark">
                                    @if($patient->gender === 'male') Nam
                                    @elseif($patient->gender === 'female') Nữ
                                    @else Không rõ
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Số điện thoại</small>
                                <strong class="text-dark">{{ $patient->phone ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Số bảo hiểm</small>
                                <strong class="text-dark">{{ $patient->insurance_number ?? 'Không có' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Địa chỉ</small>
                                <strong class="text-dark">{{ $patient->address ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin khám bệnh -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-info mb-3 fw-bold d-flex align-items-center">
                    <i class="fas fa-stethoscope me-2"></i>Thông tin khám bệnh
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-md text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Bác sĩ phụ trách</small>
                                <strong class="text-dark">{{ $appointment->doctor->user->name ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heartbeat text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Dịch vụ khám</small>
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">{{ $appointment->service->name ?? 'Không rõ' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày khám</small>
                                <strong class="text-dark">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ca khám</small>
                                <strong class="text-dark">{{ $appointment->medical_examination ?? 'Không xác định' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sticky-note text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ghi chú</small>
                                <strong class="text-dark">{{ $appointment->note ?? 'Không có ghi chú' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('doctor.records.update', $record->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

            <!-- Gói dịch vụ cho lần khám này -->
            @php
                $svc = $appointment->service ?? null;
            @endphp
            @if($svc)
                @php
                    // Tách mô tả gói dịch vụ thành từng dòng
                    $descLines = preg_split('/\r\n|\r|\n/', $svc->description ?? '');
                    $descLines = array_values(array_filter($descLines, function ($line) {
                        return trim($line) !== '';
                    }));
                @endphp
                <div class="mb-4">
                    <h5 class="text-primary mb-3 fw-bold d-flex align-items-center">
                        <i class="fas fa-list-ul me-2"></i>Gói dịch vụ bệnh nhân đã chọn
                    </h5>
                    <div class="bg-primary-subtle border-0 rounded-4 p-4 shadow-sm">
                        <div class="fw-bold mb-3 text-primary fs-5">
                            {{ $svc->name }} 
                        </div>
                        @if(!empty($descLines))
                            <ul class="mb-0 mt-1">
                                @foreach($descLines as $line)
                                    @php $lineTrimmed = trim($line); @endphp
                                    <li class="mb-2 d-flex align-items-start">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <span class="text-dark">{{ $lineTrimmed }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if($svc->department)
                            <div class="mt-3">
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="fas fa-building me-1"></i>Khoa: {{ $svc->department->name }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Mô tả bệnh -->
            @php
                $labTests = \App\Models\LabTest::where('medical_record_id', $record->id)
                    ->where('status', 'completed')
                    ->get();
            @endphp
                @if(!empty($record->image) || (!empty($record->images) && is_array($record->images) && count($record->images)) || ($labTests && $labTests->count()))
                    <h5 class="text-warning mb-3"><i class="fas fa-clipboard-list"></i> Ảnh xét nghiệm nếu có</h5>
                    <!-- Ảnh từ xét nghiệm (admin cập nhật) -->
                    @if($labTests && $labTests->count())
                        <div class="mb-2">
                            @foreach($labTests as $t)
                                @if(!empty($t->image))
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">{{ $t->test_name }} - Ảnh chính:</label>
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$t->image) }}" alt="{{ $t->test_name }}" class="thumb">
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($t->images) && is_array($t->images))
                                    <div class="mt-3">
                                        <label class="form-label fw-bold">{{ $t->test_name }} - Ảnh phụ:</label>
                                        <div class="mt-2 d-flex flex-wrap gap-2">
                                            @foreach($t->images as $img)
                                                <img src="{{ asset('storage/'.$img) }}" alt="{{ $t->test_name }}" class="thumb">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif

            <hr>

            <!-- Chuẩn đoán -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-success mb-4 fw-bold d-flex align-items-center">
                    <i class="fas fa-diagnoses me-2"></i>Chuẩn đoán & Kết luận
                </h5>

                <!-- Chuẩn đoán -->
                <div class="mb-4">
                    <label for="diagnosis" class="form-label fw-semibold mb-2">
                        <i class="fas fa-file-medical text-success me-2"></i>Chuẩn đoán ban đầu:
                    </label>
                    <textarea id="diagnosis" name="diagnosis" class="form-control rounded-3 border-2" rows="3" placeholder="Nhập chuẩn đoán..." style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('diagnosis', $record->diagnosis ?? '') }}</textarea>
                </div>

                <!-- Kết luận -->
                <div class="mb-4">
                    <label for="doctor_conclusion" class="form-label fw-semibold mb-2">
                        <i class="fas fa-clipboard-check text-success me-2"></i>Kết luận bác sĩ:
                    </label>
                    <textarea id="doctor_conclusion" name="doctor_conclusion" class="form-control rounded-3 border-2" rows="3" placeholder="Nhập kết luận bác sĩ..." style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('doctor_conclusion', $record->doctor_conclusion ?? '') }}</textarea>
                </div>

                <!-- Trạng thái lịch hẹn -->
                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold mb-2">
                        <i class="fas fa-info-circle text-success me-2"></i>Trạng thái lịch hẹn:
                    </label>
                    <select id="status" name="status" class="form-select rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="confirmed" {{ ($appointment->status ?? '') === 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="completed" {{ ($appointment->status ?? '') === 'completed' ? 'selected' : '' }}>Đã khám</option>
                    </select>
                </div>

                <!-- Toa thuốc -->
                <div class="mb-4">
                    <label for="prescription" class="form-label fw-semibold mb-2">
                        <i class="fas fa-pills text-success me-2"></i>Toa thuốc (mỗi dòng 1 loại thuốc):
                    </label>
                    <textarea id="prescription" name="prescription" class="form-control rounded-3 border-2" rows="4" placeholder="Ví dụ: Paracetamol 500mg - Uống 2 lần/ngày&#10;Vitamin C 1000mg - Sáng 1 viên" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('prescription', isset($record->prescription) ? (is_array($record->prescription) ? implode("\n", $record->prescription) : ( $record->prescription ? implode("\n", (array) json_decode($record->prescription, true)) : '')) : '') }}</textarea>
                </div>

                <!-- Nút lưu -->
                <div class="text-end">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>Lưu thông tin
                    </button>
                </div>
            </div>
            </form>

            <hr>

            <!-- Yêu cầu xét nghiệm -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-primary mb-4 fw-bold d-flex align-items-center">
                    <i class="fas fa-vial me-2"></i>Yêu cầu xét nghiệm
                </h5>
                @php
                    $testTypes = \App\Models\TestType::with('department')->orderBy('name')->get();
                    $allDepartments = \App\Models\Department::orderBy('name')->get();
                @endphp
                <div id="labTestAlert" class="mb-3" style="display:none;"></div>
                <form id="labTestForm" action="{{ route('doctor.lab_tests.store', ['record' => $record->id]) }}" method="POST" class="row g-4">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-list text-primary me-2"></i>Chọn loại xét nghiệm
                        </label>
                        <select id="testTypeSelect" class="form-select rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
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
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-tag text-primary me-2"></i>Tên xét nghiệm
                        </label>
                        <input type="text" name="test_name" id="testNameInput" class="form-control rounded-3 border-2" placeholder="Nhập tên xét nghiệm" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-building text-primary me-2"></i>Khoa phụ trách
                        </label>
                        <select name="department_id" id="departmentSelect" class="form-select rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            <option value="">-- Chọn khoa --</option>
                            @foreach($allDepartments as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-sticky-note text-primary me-2"></i>Ghi chú
                        </label>
                        <textarea name="note" class="form-control rounded-3 border-2" rows="2" placeholder="Ghi chú thêm (nếu có)" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';"></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                            <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu xét nghiệm
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<style>
    .thumb { 
        width: 140px; 
        height: 140px; 
        object-fit: cover; 
        border-radius: 12px; 
        border: 2px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .thumb:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    #imagesPreview img, #imagePreview img { 
        margin-right: 8px; 
        margin-bottom: 8px; 
    }
</style>
@push('scripts')
<script src="{{ asset('js/patient_record.js') }}"></script>
@endpush
@endsection
