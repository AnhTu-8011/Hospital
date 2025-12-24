@extends('home.frontend')

@section('title', 'Chuyên khoa - Bệnh viện PHÚC AN')

@section('content')
    {{-- Hero Section --}}
    <section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
        <div class="container">
            {{-- Page Header --}}
            <div class="text-center mb-5">
                <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">
                    CHUYÊN KHOA
                </p>
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">
                    Các chuyên khoa
                </h1>
                <p class="text-muted mb-0">
                    Khám phá các chuyên khoa chính với dịch vụ y tế toàn diện tại Bệnh viện Phúc An.
                </p>
            </div>

            {{-- Search Form --}}
            <div class="card border-0 shadow-sm rounded-4 mb-5" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                <div class="card-body p-4">
                    <form action="{{ route('departments.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-10">
                            <label for="symptom" class="form-label fw-semibold mb-2">
                                <i class="bi bi-search text-primary me-2"></i>
                                Tìm kiếm theo triệu chứng:
                            </label>
                            <input type="text"
                                   name="symptom"
                                   id="symptom"
                                   class="form-control form-control-lg rounded-3 border-2"
                                   placeholder="Nhập triệu chứng bạn đang gặp phải (ví dụ: đau đầu, sốt, ho, đau bụng...)"
                                   value="{{ $query ?? '' }}"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0 0 0.2rem rgba(13, 110, 253, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        </div>
                        <div class="col-md-2">
                            <button type="submit"
                                    class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm"
                                    style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(13, 110, 253, 0.4)';"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(13, 110, 253, 0.3)';">
                                <i class="bi bi-search me-2"></i>
                                Tìm kiếm
                            </button>
                        </div>
                        @if($query ?? null)
                            <div class="col-12">
                                <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Xóa bộ lọc
                                </a>
                                <span class="text-muted ms-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Tìm thấy <strong>{{ $departments->count() }}</strong> khoa phù hợp với triệu chứng "<strong>{{ $query }}</strong>"
                                </span>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Departments List --}}
            <div>
                @if(isset($departments) && $departments->count())
                    <div class="row g-4 g-lg-4">
                        @foreach($departments as $dept)
                            @php
                                $deptServices = isset($services) ? $services->where('department_id', $dept->id) : collect();
                            @endphp

                            {{-- Department Card --}}
                            <div class="col-md-4 col-sm-6">
                                <div class="card border-0 h-100 bg-white rounded-4 shadow-sm"
                                     style="cursor:pointer; transition: all 0.3s ease;"
                                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';"
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';"
                                     data-bs-toggle="modal"
                                     data-bs-target="#departmentModal{{ $dept->id }}">
                                    {{-- Image Section --}}
                                    <div class="bg-primary-subtle d-flex justify-content-center pt-4 pb-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                        <div style="width: 160px; height: 160px; overflow:hidden; border-radius: .75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                            @if(!empty($dept->image))
                                                <img src="{{ asset('storage/'.$dept->image) }}"
                                                     alt="{{ $dept->name }}"
                                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                                     onmouseover="this.style.transform='scale(1.1)'"
                                                     onmouseout="this.style.transform='scale(1)'">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-white h-100">
                                                    <i class="bi bi-hospital text-primary" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Content Section --}}
                                    <div class="p-4">
                                        <h5 class="fw-bold mt-2 mb-3 text-dark">{{ $dept->name }}</h5>
                                        <p class="text-muted small mb-0" style="white-space: pre-line; line-height: 1.6;">
                                            {{ $dept->description ? \Illuminate\Support\Str::limit($dept->description, 120) : 'Chưa có mô tả cho chuyên khoa này.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Department Modal --}}
                            <div class="modal fade"
                                 id="departmentModal{{ $dept->id }}"
                                 tabindex="-1"
                                 aria-labelledby="departmentModalLabel{{ $dept->id }}"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width:90%;">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        {{-- Modal Header --}}
                                        <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 100%); border-radius: 1rem 1rem 0 0 !important;">
                                            <h5 class="modal-title fw-bold text-primary" id="departmentModalLabel{{ $dept->id }}">
                                                {{ $dept->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>

                                        {{-- Modal Body --}}
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                {{-- Description Section --}}
                                                <div class="col-md-5">
                                                    <div class="bg-light rounded-3 p-3 h-100">
                                                        <h6 class="fw-bold mb-3 text-primary">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            Mô tả chuyên khoa
                                                        </h6>
                                                        <p class="mb-0" style="white-space: pre-line; color: #495057; line-height: 1.7;">
                                                            {{ $dept->description ?? 'Chưa có mô tả cho chuyên khoa này.' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- Services List Section --}}
                                                <div class="col-md-7">
                                                    <h6 class="fw-bold mb-3 text-primary">
                                                        <i class="bi bi-list-ul me-2"></i>
                                                        Danh sách dịch vụ của khoa
                                                    </h6>

                                                    @if($deptServices->count())
                                                        <div class="list-group mb-0">
                                                            @foreach($deptServices as $service)
                                                                <div class="list-group-item border-0 rounded-3 mb-2 shadow-sm"
                                                                     style="transition: all 0.3s ease;"
                                                                     onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='0 4px 12px rgba(13, 110, 253, 0.15)';"
                                                                     onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                                                                    <div class="d-flex align-items-start">
                                                                        {{-- Service Image --}}
                                                                        <div class="me-3" style="width:60px; height:60px; flex-shrink:0;">
                                                                            @if(!empty($service->image))
                                                                                <img src="{{ asset('storage/'.$service->image) }}"
                                                                                     alt="{{ $service->name }}"
                                                                                     class="rounded"
                                                                                     style="width: 60px; height: 60px; object-fit: cover; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                                            @else
                                                                                <div class="d-flex align-items-center justify-content-center bg-primary-subtle rounded" style="width: 60px; height: 60px;">
                                                                                    <i class="bi bi-heart-pulse text-primary"></i>
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                        {{-- Service Info --}}
                                                                        <div class="flex-grow-1 d-flex justify-content-between align-items-start">
                                                                            <div class="me-3">
                                                                                <div class="fw-semibold text-dark mb-1">{{ $service->name }}</div>
                                                                                @if(!empty($service->description))
                                                                                    <div class="small text-muted" style="white-space: pre-line; line-height: 1.5;">
                                                                                        {{ \Illuminate\Support\Str::limit($service->description, 80) }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            @if(!is_null($service->price))
                                                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                                                    {{ number_format($service->price, 0, ',', '.') }} đ
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info mb-0 rounded-3 border-0">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            Chưa có dịch vụ nào được gán cho khoa này.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Footer --}}
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                Đóng
                                            </button>
                                            <a href="{{ route('services.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                                <i class="bi bi-arrow-right me-2"></i>
                                                Xem tất cả dịch vụ
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($departments instanceof \Illuminate\Pagination\AbstractPaginator)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $departments->onEachSide(1)->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="alert alert-warning text-center mb-0 rounded-4 border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @if($query ?? null)
                            Không tìm thấy khoa nào phù hợp với triệu chứng "<strong>{{ $query }}</strong>".
                            <a href="{{ route('departments.index') }}" class="alert-link">Xem tất cả các khoa</a>
                        @else
                            Chưa có chuyên khoa nào được hiển thị.
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
