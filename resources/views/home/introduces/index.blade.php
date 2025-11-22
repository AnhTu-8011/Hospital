@extends('home.frontend')

@section('title', 'Giới thiệu - Bệnh viện PHÚC AN')

@section('content')
<section class="py-5 py-lg-6 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Giới thiệu Bệnh viện Phúc An</h1>
            <p class="text-muted mb-0">Bệnh viện PHÚC AN – Nơi chăm sóc sức khỏe chất lượng và tận tâm.</p>
        </div>

        <div class="row align-items-center g-4 g-lg-5 mb-5">
            <div class="col-md-6">
                <h3 class="fw-bold mb-3">Sứ mệnh của chúng tôi</h3>
                <p>
                    Bệnh viện Phúc An được thành lập với mục tiêu mang đến dịch vụ y tế chất lượng cao,
                    lấy người bệnh làm trung tâm và đặt sức khỏe cộng đồng lên hàng đầu.
                </p>
                <p>
                    Với đội ngũ bác sĩ giàu kinh nghiệm cùng hệ thống trang thiết bị hiện đại,
                    chúng tôi cam kết mang đến dịch vụ khám chữa bệnh an toàn, hiệu quả và nhanh chóng.
                </p>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('image/hospital.jpg') }}"
                    alt="Bệnh viện Phúc An"
                    class="img-fluid rounded shadow"
                    style="width:100%; max-width:700px; height:auto;">
            </div>
        </div>

        <div class="row g-4 g-lg-5 mb-5">
            <div class="col-md-4">
                <div class="card border p-4 h-100 text-center bg-light rounded-4">
                    <h4 class="fw-bold mb-3">Giá trị cốt lõi</h4>
                    <p>Chất lượng – Tận tâm – Minh bạch – An toàn</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 text-center bg-light rounded-4">
                    <h4 class="fw-bold mb-3">Đội ngũ bác sĩ</h4>
                    <p>
                        Quy tụ nhiều chuyên gia đầu ngành, được đào tạo trong và ngoài nước,
                        luôn mang đến sự yên tâm cho bệnh nhân.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-4 h-100 text-center bg-light rounded-4">
                    <h4 class="fw-bold mb-3">Cơ sở vật chất</h4>
                    <p>
                        Trang thiết bị hiện đại, không gian sạch đẹp, đạt chuẩn của Bộ Y tế.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="/lien-he" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">Liên hệ với chúng tôi</a>
        </div>
    </div>
</section>
@endsection
