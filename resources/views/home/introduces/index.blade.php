@extends('home.frontend')

@section('title', 'Giới thiệu - Bệnh viện PHÚC AN')

@section('content')
<section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
    <div class="container">
        <div class="text-center mb-5">
            <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">VỀ CHÚNG TÔI</p>
            <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Giới thiệu Bệnh viện Phúc An</h1>
            <p class="text-muted mb-0">Bệnh viện PHÚC AN – Nơi chăm sóc sức khỏe chất lượng và tận tâm.</p>
        </div>

        <div class="row align-items-center g-4 g-lg-5 mb-5">
            <div class="col-md-6">
                <div class="bg-white rounded-4 p-4 shadow-sm h-100">
                    <h3 class="fw-bold mb-4 text-primary">
                        <i class="bi bi-bullseye me-2"></i>Sứ mệnh của chúng tôi
                    </h3>
                    <p class="text-muted mb-3" style="line-height: 1.8;">
                        Bệnh viện Phúc An được thành lập với mục tiêu mang đến dịch vụ y tế chất lượng cao,
                        lấy người bệnh làm trung tâm và đặt sức khỏe cộng đồng lên hàng đầu.
                    </p>
                    <p class="text-muted mb-0" style="line-height: 1.8;">
                        Với đội ngũ bác sĩ giàu kinh nghiệm cùng hệ thống trang thiết bị hiện đại,
                        chúng tôi cam kết mang đến dịch vụ khám chữa bệnh an toàn, hiệu quả và nhanh chóng.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative">
                    <img src="{{ asset('image/hospital.jpg') }}"
                        alt="Bệnh viện Phúc An"
                        class="img-fluid rounded-4 shadow-lg w-100"
                        style="height: auto; min-height: 300px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 translate-middle bg-white rounded-4 shadow-sm px-3 py-2 d-none d-md-flex align-items-center gap-2" style="z-index: 2;">
                        <span class="badge bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-heart-pulse"></i>
                        </span>
                        <div class="small">
                            <div class="fw-semibold">Uy tín</div>
                            <div class="text-muted">Hơn 10 năm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 g-lg-5 mb-5">
            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center bg-white rounded-4 shadow-sm" 
                     style="transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                    <div class="mb-3 text-primary" style="font-size: 3rem;">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">Giá trị cốt lõi</h4>
                    <p class="text-muted mb-0" style="line-height: 1.7;">Chất lượng – Tận tâm – Minh bạch – An toàn</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center bg-white rounded-4 shadow-sm" 
                     style="transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                    <div class="mb-3 text-primary" style="font-size: 3rem;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">Đội ngũ bác sĩ</h4>
                    <p class="text-muted mb-0" style="line-height: 1.7;">
                        Quy tụ nhiều chuyên gia đầu ngành, được đào tạo trong và ngoài nước,
                        luôn mang đến sự yên tâm cho bệnh nhân.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 p-4 h-100 text-center bg-white rounded-4 shadow-sm" 
                     style="transition: all 0.3s ease;"
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(13, 110, 253, 0.15)';" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)';">
                    <div class="mb-3 text-primary" style="font-size: 3rem;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h4 class="fw-bold mb-3 text-dark">Cơ sở vật chất</h4>
                    <p class="text-muted mb-0" style="line-height: 1.7;">
                        Trang thiết bị hiện đại, không gian sạch đẹp, đạt chuẩn của Bộ Y tế.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="/lien-he" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm">
                <i class="bi bi-telephone me-2"></i>Liên hệ với chúng tôi
            </a>
        </div>
    </div>
</section>
@endsection
