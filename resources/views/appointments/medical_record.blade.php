@extends('layouts.profile')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">HỒ SƠ KHÁM BỆNH</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-notes-medical me-2 text-primary"></i>Hồ sơ khám bệnh
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                <i class="fas fa-file-invoice me-2"></i>Xuất hồ sơ khám bệnh
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" id="printable">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user-md me-2"></i>Thông tin hồ sơ khám bệnh
            </h6>
        </div>
        <div class="card-body p-4">

            <!-- Thông tin bệnh nhân -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-primary mb-4 fw-bold d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>Thông tin bệnh nhân
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Họ và tên</small>
                                <strong class="text-dark">{{ optional($patient)->name ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-birthday-cake text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Ngày sinh</small>
                                <strong class="text-dark">{{ optional($patient)->birthdate ? \Carbon\Carbon::parse(optional($patient)->birthdate)->format('d/m/Y') : 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-venus-mars text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Giới tính</small>
                                <strong class="text-dark">
                                    @if(optional($patient)->gender === 'male') Nam
                                    @elseif(optional($patient)->gender === 'female') Nữ
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
                                <strong class="text-dark">{{ optional($patient)->phone ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Số bảo hiểm</small>
                                <strong class="text-dark">{{ optional($patient)->insurance_number ?? 'Không có' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Địa chỉ</small>
                                <strong class="text-dark">{{ optional($patient)->address ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin khám bệnh -->
            <div class="bg-light rounded-4 p-4 mb-4">
                <h5 class="text-info mb-4 fw-bold d-flex align-items-center">
                    <i class="fas fa-stethoscope me-2"></i>Thông tin khám bệnh
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-md text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Bác sĩ phụ trách</small>
                                <strong class="text-dark">{{ optional(optional($appointment->doctor)->user)->name ?? 'Không rõ' }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heartbeat text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Dịch vụ khám</small>
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">{{ optional($appointment->service)->name ?? 'Không rõ' }}</span>
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

            @php
                $svc = $appointment->service ?? null;
            @endphp
            @if($svc)
                @php
                    // Mô tả gói dịch vụ (lấy trực tiếp từ dịch vụ, mỗi dòng một mục)
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
                        <div class="fw-bold mb-3 text-primary fs-5">{{ $svc->name }}</div>
                        @if(!empty($descLines))
                            <ul class="mb-0">
                                @foreach($descLines as $line)
                                    <li class="mb-2 d-flex align-items-start">
                                        <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                        <span class="text-dark">{{ trim($line) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">Chưa có mô tả gói dịch vụ.</div>
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
                <div class="mb-4">
                    <h5 class="text-warning mb-3 fw-bold d-flex align-items-center">
                        <i class="fas fa-clipboard-list me-2"></i>Ảnh xét nghiệm nếu có
                    </h5>
                    @if($labTests && $labTests->count())
                        <div class="mb-2">
                            @foreach($labTests as $t)
                                @if(!empty($t->image))
                                    <div class="mt-3">
                                        <label class="form-label fw-bold mb-2">{{ $t->test_name }} - Ảnh chính:</label>
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$t->image) }}" alt="{{ $t->test_name }}" class="thumb">
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($t->images) && is_array($t->images))
                                    <div class="mt-3">
                                        <label class="form-label fw-bold mb-2">{{ $t->test_name }} - Ảnh phụ:</label>
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
                </div>
            @endif
            
            <!-- Chuẩn đoán -->
                <div class="bg-light rounded-4 p-4 mb-4">
                    <h5 class="text-success mb-4 fw-bold d-flex align-items-center">
                        <i class="fas fa-diagnoses me-2"></i>Chuẩn đoán & Kết luận
                    </h5>

                    <div class="mb-4">
                        <label for="diagnosis" class="form-label fw-semibold mb-2">
                            <i class="fas fa-file-medical text-success me-2"></i>Chuẩn đoán ban đầu:
                        </label>
                        <textarea id="diagnosis" name="diagnosis" class="form-control rounded-3 border-2 bg-light" rows="3" placeholder="Nhập chuẩn đoán..." readonly disabled>{{ old('diagnosis', optional($record)->diagnosis ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="doctor_conclusion" class="form-label fw-semibold mb-2">
                            <i class="fas fa-clipboard-check text-success me-2"></i>Kết luận bác sĩ:
                        </label>
                        <textarea id="doctor_conclusion" name="doctor_conclusion" class="form-control rounded-3 border-2 bg-light" rows="3" placeholder="Nhập kết luận bác sĩ..." readonly disabled>{{ old('doctor_conclusion', optional($record)->doctor_conclusion ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-pills text-success me-2"></i>Toa thuốc:
                        </label>

                        @php
                            $items = ($record && method_exists($record, 'prescriptionItems'))
                                ? $record->prescriptionItems
                                : null;
                        @endphp

                        @if($items && $items->count())
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
                            @php($prescription = $record->prescription ?? [])
                            @if(is_string($prescription))
                                <textarea class="form-control rounded-3 border-2 bg-light" rows="4" readonly disabled>{!! nl2br(e($prescription)) !!}</textarea>
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
            @else
                <div class="alert alert-info rounded-4 border-0 shadow-sm">
                    <div class="text-center py-4">
                        <i class="fas fa-info-circle fa-3x mb-3 text-primary" style="opacity: 0.5;"></i>
                        <p class="mb-0 fw-semibold">Chưa có hồ sơ khám bệnh.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
<style>
    .thumb { 
        width: 200px; 
        height: 200px; 
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
@endsection
