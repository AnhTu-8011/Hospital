@extends('home.frontend')

@section('title', 'Bệnh viện PHÚC AN | Hệ thống y tế chất lượng cao')

@section('content')
    {{-- HERO SECTION --}}
    <section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">
                        BỆNH VIỆN PHÚC AN
                    </p>
                    <h1 class="fw-bold mb-3" style="font-size: clamp(2.4rem, 3vw + 1rem, 3.4rem); line-height: 1.15;">
                        Chăm Sóc Sức Khỏe
                        <br class="d-none d-md-block" />
                        Toàn Diện
                    </h1>
                    <p class="lead text-muted mb-4">
                        Đồng hành cùng bạn trên hành trình bảo vệ sức khỏe
                        với đội ngũ bác sĩ giàu kinh nghiệm và hệ thống trang thiết bị hiện đại.
                    </p>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <a href="{{ route('modal.appointment') }}" class="btn btn-primary btn-lg d-inline-flex align-items-center px-4 shadow-sm">
                            <i class="bi bi-calendar-check me-2"></i>
                            Đặt lịch khám
                        </a>
                        <a href="{{ route('introduces.index') }}" class="btn btn-outline-primary btn-lg px-4">
                            Tìm hiểu thêm
                        </a>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-4 mt-4 text-muted small">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="bi bi-shield-check me-1"></i> An toàn</span>
                            <span>Chuẩn quy trình Bộ Y tế</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            <span>Hoạt động 24/7</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative mx-auto" style="max-width: 520px;">
                        <div class="position-absolute top-0 start-0 translate-middle bg-white rounded-4 shadow-sm px-3 py-2 d-none d-md-flex align-items-center gap-2" style="z-index: 2;">
                            <span class="badge bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="bi bi-heart-pulse"></i>
                            </span>
                            <div class="small">
                                <div class="fw-semibold">Hơn 10.000+</div>
                                <div class="text-muted">Ca khám mỗi năm</div>
                            </div>
                        </div>
                        <img
                            src="{{ asset('image/doctor-hero.jpg') }}"
                            alt="Bác sĩ tại Bệnh viện Phúc An"
                            class="img-fluid rounded-4 shadow-lg w-100"
                            style="object-fit: cover; min-height: 280px;"
                        >
                        <div class="position-absolute bottom-0 end-0 translate-middle-y bg-white rounded-4 shadow-sm px-3 py-2 d-none d-md-flex align-items-center gap-2" style="z-index: 2;">
                            <i class="bi bi-people text-primary fs-5"></i>
                            <div class="small">
                                <div class="fw-semibold">Đội ngũ bác sĩ giỏi</div>
                                <div class="text-muted">Tận tâm - Chuyên nghiệp</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section id="about" class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <h2 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 2.2vw + .6rem, 2.4rem);">
                        Giới thiệu về Bệnh viện Phúc An
                    </h2>
                    <p class="text-muted mb-3">
                        Bệnh viện Phúc An là hệ thống y tế chất lượng cao, tập trung cung cấp dịch vụ khám
                        chữa bệnh toàn diện với tiêu chuẩn an toàn, hiện đại và thân thiện với người bệnh.
                    </p>
                    <p class="text-muted mb-3">
                        Với đội ngũ bác sĩ giàu kinh nghiệm cùng hệ thống trang thiết bị tiên tiến, chúng tôi
                        cam kết mang đến giải pháp chăm sóc sức khỏe tối ưu cho bạn và gia đình.
                    </p>
                    <ul class="list-unstyled text-muted mb-3">
                        <li class="d-flex align-items-start mb-2">
                            <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                            <span>Quy trình khám chữa bệnh rõ ràng, minh bạch.</span>
                        </li>
                        <li class="d-flex align-items-start mb-2">
                            <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                            <span>Không gian khám chữa bệnh hiện đại, thoải mái.</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="bi bi-check-circle text-primary me-2 mt-1"></i>
                            <span>Hỗ trợ đặt lịch và tư vấn trực tuyến nhanh chóng.</span>
                        </li>
                    </ul>
                    <a href="{{ route('services.index') }}" class="btn btn-outline-primary px-4 mt-2">
                        Xem các dịch vụ
                    </a>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="position-relative mx-auto" style="max-width: 520px;">
                        <div class="ratio ratio-4x3 rounded-4 overflow-hidden shadow-sm bg-light">
                            <img
                                src="{{ asset('image/hospital-intro.jpg') }}"
                                alt="Cơ sở vật chất Bệnh viện Phúc An"
                                class="w-100 h-100"
                                style="object-fit: cover;"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- WHY CHOOSE US --}}
    <section class="py-5 py-lg-6 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2">Tại sao chọn chúng tôi?</h2>
                <p class="text-muted mb-0">Những giá trị khác biệt giúp Bệnh viện Phúc An trở thành lựa chọn của bạn.</p>
            </div>
            <div class="row g-4 g-lg-5 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="h-100 text-center bg-white rounded-4 shadow-sm px-3 py-4">
                        <div class="mb-3 text-primary fs-2">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Đội ngũ bác sĩ giỏi</h5>
                        <p class="text-muted small mb-0">Chuyên môn cao, liên tục đào tạo và cập nhật kiến thức.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h-100 text-center bg-white rounded-4 shadow-sm px-3 py-4">
                        <div class="mb-3 text-primary fs-2">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Cơ sở hiện đại</h5>
                        <p class="text-muted small mb-0">Trang thiết bị chẩn đoán và điều trị đạt chuẩn quốc tế.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h-100 text-center bg-white rounded-4 shadow-sm px-3 py-4">
                        <div class="mb-3 text-primary fs-2">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Dịch vụ toàn diện</h5>
                        <p class="text-muted small mb-0">Từ phòng khám tổng quát đến các chuyên khoa sâu.</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="h-100 text-center bg-white rounded-4 shadow-sm px-3 py-4">
                        <div class="mb-3 text-primary fs-2">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <h5 class="fw-semibold mb-1">Chăm sóc tận tâm</h5>
                        <p class="text-muted small mb-0">Đặt trải nghiệm và sự an tâm của bệnh nhân lên hàng đầu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @auth
        @php
            $adminRoleId = \App\Models\Role::where('name', 'admin')->value('id');
            $admin = $adminRoleId ? \App\Models\User::where('role_id', $adminRoleId)->first() : null;
        @endphp
        @include('chat.user', ['receiverId' => $admin->id ?? null])
    @endauth
    <!-- @include('chat.ai_chat') -->

@endsection