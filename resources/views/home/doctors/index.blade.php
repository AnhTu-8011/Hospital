@extends('home.frontend')

@section('title', 'Đội ngũ bác sĩ - Bệnh viện PHÚC AN')

@section('content')
    {{-- Hero Section --}}
    <section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
        <div class="container">
            {{-- Page Header --}}
            <div class="text-center mb-5">
                <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">
                    ĐỘI NGŨ Y BÁC SĨ
                </p>
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">
                    Đội ngũ bác sĩ
                </h1>
                <p class="text-muted mb-0">
                    Các chuyên gia hàng đầu, tận tâm và giàu kinh nghiệm tại Bệnh viện Phúc An.
                </p>
            </div>

            {{-- Search Form --}}
            <div class="mb-4">
                <form action="{{ route('doctors.index') }}" method="get" class="row g-2 justify-content-center">
                    <div class="col-12 col-md-6">
                        <input type="text"
                               name="q"
                               value="{{ $q ?? '' }}"
                               class="form-control form-control-lg rounded-pill"
                               placeholder="Tìm kiếm theo tên bác sĩ...">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            <i class="bi bi-search me-1"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </form>
                @if(!empty($q))
                    <p class="text-center text-muted mt-2">
                        Kết quả cho từ khóa: <strong>"{{ $q }}"</strong>
                    </p>
                @endif
            </div>

            {{-- Doctors List --}}
            <div>
                @if(isset($doctors) && $doctors->count())
                    <div class="row g-4 g-lg-4">
                        @foreach($doctors as $doc)
                            @php
                                $avatar = !empty($doc->avatar) ? asset('storage/'.$doc->avatar) : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                                $name = $doc->user->name ?? 'Bác sĩ';
                                $deptName = $doc->department->name ?? 'Chưa phân khoa';
                                $spec = $doc->specialization ?? null;
                            @endphp

                            {{-- Doctor Card --}}
                            <div class="col-md-3 col-sm-6">
                                <div class="card border-0 text-center p-3 h-100 bg-white rounded-4 shadow-sm"
                                     style="transition: all 0.3s ease;"
                                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';"
                                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                                    {{-- Avatar Section --}}
                                    <div class="position-relative mb-3">
                                        <img src="{{ $avatar }}"
                                             alt="{{ $name }}"
                                             class="doctor-img rounded-4"
                                             style="width: 100%; height: 200px; object-fit: cover; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s ease;"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                Chuyên nghiệp
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Doctor Info --}}
                                    <h5 class="fw-bold mb-1 text-dark">{{ $name }}</h5>
                                    <p class="text-primary small mb-1 fw-semibold">
                                        <i class="bi bi-hospital me-1"></i>
                                        {{ $deptName }}
                                    </p>
                                    @if($spec)
                                        <p class="text-muted small mb-3">
                                            <i class="bi bi-award me-1"></i>
                                            {{ $spec }}
                                        </p>
                                    @endif

                                    {{-- View Profile Button --}}
                                    <a href="#"
                                       class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                       data-bs-toggle="modal"
                                       data-bs-target="#doctorModal{{ $doc->id }}">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Hồ sơ chi tiết
                                    </a>
                                </div>
                            </div>

                            {{-- Doctor Modal --}}
                            <div class="modal fade"
                                 id="doctorModal{{ $doc->id }}"
                                 tabindex="-1"
                                 aria-labelledby="doctorModalLabel{{ $doc->id }}"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:90%;">
                                    <div class="modal-content rounded-4 border-0 shadow-lg">
                                        {{-- Modal Header --}}
                                        <div class="modal-header border-0 pb-0" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 100%); border-radius: 1rem 1rem 0 0 !important;">
                                            <h5 class="modal-title fw-bold text-primary" id="doctorModalLabel{{ $doc->id }}">
                                                <i class="bi bi-person-badge me-2"></i>
                                                Hồ sơ chi tiết - Bác Sĩ {{ $name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>

                                        {{-- Modal Body --}}
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                {{-- Avatar Section --}}
                                                <div class="col-md-4 text-center">
                                                    <div class="position-relative">
                                                        <img src="{{ $avatar }}"
                                                             alt="{{ $name }}"
                                                             class="img-fluid rounded-4 mb-3 shadow-sm"
                                                             style="width:100%; max-height: 300px; object-fit: contain;">
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <span class="badge bg-primary rounded-pill px-3 py-2">
                                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                                Bác sĩ
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Doctor Details --}}
                                                <div class="col-md-8">
                                                    {{-- Basic Info --}}
                                                    <div class="bg-light rounded-3 p-3 mb-3">
                                                        <p class="mb-2">
                                                            <i class="bi bi-hospital text-primary me-2"></i>
                                                            <strong>Khoa:</strong>
                                                            <span class="text-dark">{{ $deptName }}</span>
                                                        </p>
                                                        @if($spec)
                                                            <p class="mb-2">
                                                                <i class="bi bi-award text-primary me-2"></i>
                                                                <strong>Chuyên môn:</strong>
                                                                <span class="text-dark">{{ $spec }}</span>
                                                            </p>
                                                        @endif
                                                        @if(!empty($doc->birth_date))
                                                            <p class="mb-0">
                                                                <i class="bi bi-calendar3 text-primary me-2"></i>
                                                                <strong>Ngày sinh:</strong>
                                                                <span class="text-dark">{{ \Carbon\Carbon::parse($doc->birth_date)->format('d/m/Y') }}</span>
                                                            </p>
                                                        @else
                                                            <p class="mb-0">
                                                                <i class="bi bi-calendar3 text-primary me-2"></i>
                                                                <strong>Ngày sinh:</strong>
                                                                <span class="text-muted">Chưa cập nhật.</span>
                                                            </p>
                                                        @endif
                                                    </div>

                                                    {{-- Description --}}
                                                    @if(!empty($doc->description))
                                                        <div class="mb-3">
                                                            <h6 class="fw-bold text-primary mb-2">
                                                                <i class="bi bi-file-text me-2"></i>
                                                                Giới thiệu
                                                            </h6>
                                                            <div class="bg-light rounded-3 p-3">
                                                                <p class="mb-0" style="white-space: pre-line; color: #495057; line-height: 1.7;">
                                                                    {{ $doc->description }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info rounded-3 border-0 mb-3">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            Chưa có mô tả.
                                                        </div>
                                                    @endif

                                                    {{-- License Image --}}
                                                    @if(!empty($doc->license_image))
                                                        <div class="mt-3">
                                                            <h6 class="fw-bold text-primary mb-3">
                                                                <i class="bi bi-id-card text-primary me-2"></i>
                                                                Ảnh giấy phép hành nghề
                                                            </h6>
                                                            <div class="text-center">
                                                                <img src="{{ asset('storage/'.$doc->license_image) }}"
                                                                     alt="Giấy phép hành nghề của {{ $name }}"
                                                                     class="img-fluid rounded-3 shadow-sm border"
                                                                     style="max-height: 300px; object-fit: contain;">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info rounded-3 border-0 mt-3">
                                                            <i class="bi bi-id-card me-2"></i>
                                                            Chưa có ảnh giấy phép hành nghề.
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
                                            <a href="{{ route('modal.appointment') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                                <i class="bi bi-calendar-check me-2"></i>
                                                Đặt lịch khám
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($doctors instanceof \Illuminate\Pagination\AbstractPaginator)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $doctors->onEachSide(1)->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="alert alert-info text-center mb-0 rounded-4 border-0 shadow-sm">
                        <i class="bi bi-info-circle me-2"></i>
                        Chưa có bác sĩ nào được hiển thị.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
