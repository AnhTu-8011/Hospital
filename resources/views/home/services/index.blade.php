@extends('home.frontend')

@section('title', 'Dịch vụ - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
        <div class="container">
            <div class="text-center mb-5">
                <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">DỊCH VỤ Y TẾ</p>
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Dịch vụ nổi bật</h1>
                <p class="text-muted mb-0">Các dịch vụ chăm sóc sức khỏe toàn diện dành cho bạn và gia đình.</p>
            </div>

            <!-- Box tìm kiếm triệu chứng - Gợi ý dịch vụ -->
            <div class="card border-0 shadow-lg rounded-4 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold mb-2">
                            <i class="bi bi-search-heart me-2"></i>Tìm kiếm theo triệu chứng
                        </h4>
                        <p class="text-white-50 mb-0">Nhập triệu chứng bạn đang gặp phải, hệ thống sẽ gợi ý dịch vụ phù hợp</p>
                        <div class="mt-3">
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @if(isset($symptomSuggestions) && $symptomSuggestions->isNotEmpty())
                                    @foreach($symptomSuggestions as $s)
                                        <button type="button" class="btn btn-sm btn-outline-light rounded-pill symptom-suggestion" data-symptom="{{ $s['name'] }}">
                                            {{ $s['name'] }}
                                            <span class="badge bg-light text-primary ms-1">{{ $s['count'] }}</span>
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <form id="service-search-form" action="{{ route('services.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <select name="department_id" id="department_id" class="form-select form-select-lg rounded-3 border-0 shadow-sm">
                                <option value="">Chọn khoa</option>
                                @if(isset($departments))
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ (isset($selectedDepartmentId) && (string)$selectedDepartmentId === (string)$dept->id) ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" 
                                   name="symptom" 
                                   id="symptom" 
                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm" 
                                   placeholder="Ví dụ: đau đầu, sốt, ho, đau bụng, khó thở..." 
                                   value="{{ $symptomQuery ?? '' }}"
                                   style="transition: all 0.3s ease;" 
                                   onfocus="this.style.boxShadow='0 0 0 0.2rem rgba(255, 255, 255, 0.5)';" 
                                   onblur="this.style.boxShadow='';">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-light btn-lg w-100 rounded-3 shadow-sm fw-bold" style="transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.2)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                                <i class="bi bi-search me-2"></i>Tìm kiếm
                            </button>
                        </div>
                        @if(($symptomQuery ?? null) || ($selectedDepartmentId ?? null))
                            <div class="col-12 text-center">
                                <a href="{{ route('services.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                                    <i class="bi bi-x-circle me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Hiển thị gợi ý dịch vụ, khoa, bác sĩ -->
            @if($symptomQuery ?? null)
                @if($suggestedServices->isNotEmpty())
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                            <div>
                                <h5 class="mb-1 fw-bold">Tìm thấy <strong>{{ $suggestedServices->count() }}</strong> dịch vụ phù hợp</h5>
                                <p class="mb-0 text-muted small">
                                    <i class="bi bi-search me-1"></i>Từ khóa: "<strong>{{ $symptomQuery }}</strong>"
                                    @php
                                        $keywords = array_filter(array_map('trim', explode(',', $symptomQuery)));
                                    @endphp
                                    @if(count($keywords) > 1)
                                        <span class="badge bg-info ms-2">{{ count($keywords) }} từ khóa</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($suggestedDepartments->isNotEmpty())
                            <div class="mb-3">
                                <p class="mb-2 fw-semibold">
                                    <i class="bi bi-hospital me-2"></i>Khoa phù hợp:
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($suggestedDepartments as $dept)
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                            <i class="bi bi-hospital me-1"></i>{{ $dept->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($suggestedDoctors->isNotEmpty())
                            <div>
                                <p class="mb-2 fw-semibold">
                                    <i class="bi bi-person-badge me-2"></i>Bác sĩ phù hợp:
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($suggestedDoctors->take(5) as $doctor)
                                        <span class="badge bg-success rounded-pill px-3 py-2 fs-6">
                                            <i class="bi bi-person-check me-1"></i>{{ $doctor->user->name ?? 'Bác sĩ' }}
                                        </span>
                                    @endforeach
                                    @if($suggestedDoctors->count() > 5)
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">
                                            +{{ $suggestedDoctors->count() - 5 }} bác sĩ khác
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Không tìm thấy dịch vụ nào phù hợp với triệu chứng "<strong>{{ $symptomQuery }}</strong>". 
                        <a href="{{ route('services.index') }}" class="alert-link">Xem tất cả dịch vụ</a>
                    </div>
                @endif
            @endif

            <div>
                @if(isset($services) && $services->count())
                    <div class="row g-4 g-lg-4">
                        @foreach($services as $service)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 h-100 bg-white rounded-4 shadow-sm" style="cursor:pointer; transition: all 0.3s ease;" 
                                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';" 
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';"
                                     data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div style="width: 160px; height: 160px; overflow:hidden; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                @if(!empty($service->image))
                                                    <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" 
                                                         onmouseover="this.style.transform='scale(1.1)'" 
                                                         onmouseout="this.style.transform='scale(1)'">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-primary-subtle h-100">
                                                        <i class="bi bi-heart-pulse text-primary" style="font-size: 3rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-2 text-dark">{{ $service->name }}</h4>
                                        @if(!is_null($service->price))
                                            <p class="text-primary fw-bold mb-0 fs-5">{{ number_format($service->price, 0, ',', '.') }} đ</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-labelledby="serviceModalLabel{{ $service->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 100%); border-radius: 1rem 1rem 0 0 !important;">
                                            <h5 class="modal-title fw-bold text-primary" id="serviceModalLabel{{ $service->id }}">{{ $service->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="text-center mb-4">
                                                <div style="width: 200px; height: 200px; margin: 0 auto; overflow:hidden; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                    @if(!empty($service->image))
                                                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-primary-subtle h-100">
                                                            <i class="bi bi-heart-pulse text-primary" style="font-size: 4rem;"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($service->symptoms->isNotEmpty())
                                                <div class="mb-3">
                                                    <h6 class="fw-bold text-primary mb-2">
                                                        <i class="bi bi-clipboard-pulse me-2"></i>Triệu chứng phù hợp:
                                                    </h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($service->symptoms as $symptom)
                                                            @php
                                                                // Highlight triệu chứng nếu khớp với từ khóa tìm kiếm
                                                                $symptomName = $symptom->symptom_name;
                                                                if ($symptomQuery ?? null) {
                                                                    $keywords = array_filter(array_map('trim', explode(',', $symptomQuery)));
                                                                    foreach ($keywords as $keyword) {
                                                                        $pattern = '/(' . preg_quote($keyword, '/') . ')/i';
                                                                        $symptomName = preg_replace($pattern, '<mark class="bg-warning">$1</mark>', $symptomName);
                                                                    }
                                                                }
                                                            @endphp
                                                            <span class="badge bg-info rounded-pill px-3 py-2">
                                                                <i class="bi bi-check-circle me-1"></i>{!! $symptomName !!}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!empty($service->description))
                                                <div class="bg-light rounded-3 p-3 mb-3">
                                                    <p class="mb-0" style="white-space: pre-line; color: #495057;">{{ $service->description }}</p>
                                                </div>
                                            @endif
                                            @if($service->department)
                                                <div class="mb-3">
                                                    <p class="mb-1">
                                                        <i class="bi bi-hospital text-primary me-2"></i>
                                                        <strong>Khoa:</strong> <span class="text-dark">{{ $service->department->name }}</span>
                                                    </p>
                                                </div>
                                            @endif
                                            @if(!is_null($service->price))
                                                <div class="text-center">
                                                    <p class="mb-1 text-muted small">Giá dịch vụ</p>
                                                    <p class="fw-bold mb-0 fs-4 text-primary">{{ number_format($service->price, 0, ',', '.') }} đ</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                            <a href="{{ route('modal.appointment', ['department_id' => $service->department_id, 'service_id' => $service->id]) }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                                <i class="bi bi-calendar-check me-2"></i>Đặt lịch ngay
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center mb-0 rounded-4 border-0 shadow-sm">
                        <i class="bi bi-info-circle me-2"></i>Chưa có dịch vụ nào được hiển thị.
                    </div>
                @endif
            </div>
        </div>
    </section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý click vào gợi ý triệu chứng
    document.querySelectorAll('.symptom-suggestion').forEach(button => {
        button.addEventListener('click', function() {
            const symptomInput = document.getElementById('symptom');
            const currentValue = symptomInput.value.trim();
            const newSymptom = this.getAttribute('data-symptom');
            
            if (currentValue === '') {
                symptomInput.value = newSymptom;
            } else {
                // Thêm triệu chứng mới vào, cách nhau bằng dấu phẩy
                const symptoms = currentValue.split(',').map(s => s.trim()).filter(s => s !== '');
                if (!symptoms.includes(newSymptom)) {
                    symptoms.push(newSymptom);
                    symptomInput.value = symptoms.join(', ');
                }
            }
            symptomInput.focus();
        });
    });

    // Tự động submit khi chọn khoa
    const deptSelect = document.getElementById('department_id');
    if (deptSelect) {
        deptSelect.addEventListener('change', function() {
            const form = document.getElementById('service-search-form');
            if (form) form.submit();
        });
    }
});
</script>
@endpush
@endsection
