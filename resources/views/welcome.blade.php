<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bệnh viện PHÚC AN | Hệ thống y tế chất lượng cao</title>
    <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="top-bar-left">
                    <div class="d-flex flex-wrap">
                        <div class="top-bar-item me-4">
                            <i class="fas fa-phone-alt me-2"></i>
                            <a href="tel:19001234">1900 1234</a>
                        </div>
                        <div class="top-bar-item">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:info@phucan.vn">info@phucan.vn</a>
                        </div>
                    </div>
                </div>
                <div class="top-bar-right">
                    <div class="d-flex align-items-center">
                        <div class="social-links me-3">
                            <a href="#" class="me-2" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="me-2" title="Youtube"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="me-3" title="Twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                        @auth
                        <div class="d-none">
                            <div>User ID: {{ Auth::id() }}</div>
                            <div>User Name: {{ Auth::user()->name }}</div>
                            <div>User Email: {{ Auth::user()->email }}</div>
                            <div>Role: {{ Auth::user()->role ? Auth::user()->role->name : 'No Role' }}</div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.header')

    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Chăm Sóc Sức Khỏe Toàn Diện</h1>
            <p class="lead mb-5">Đồng hành cùng bạn trên hành trình bảo vệ sức khỏe</p>
        </div>
    </section>

    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold text-primary">Giới thiệu về Bệnh viện Phúc An</h2>
                    <div class="divider text-start bg-primary" style="margin-left: 0;"></div>
                    <p class="lead">Bệnh viện Phúc An là hệ thống y tế chất lượng cao, cam kết mang lại dịch vụ khám chữa bệnh hàng đầu với công nghệ tiên tiến và đội ngũ chuyên gia tận tâm.</p>
                    <p>Chúng tôi không ngừng đầu tư vào cơ sở vật chất, trang thiết bị y tế hiện đại và phát triển đội ngũ y bác sĩ chuyên môn cao, nhằm cung cấp dịch vụ chăm sóc sức khỏe toàn diện, an toàn và hiệu quả nhất cho cộng đồng.</p>
                    <a href="#" class="btn btn-outline-primary mt-3">Xem thêm về chúng tôi</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://i.pinimg.com/736x/6c/94/09/6c9409b5a62cb704126bc3c6bcc12fae.jpg" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    @include('modal.appointment')   

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">TẠI SAO CHỌN CHÚNG TÔI?</h2>
                <div class="divider mx-auto bg-primary" style="width: 50px; height: 3px; margin: 15px auto;"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4>Đội ngũ bác sĩ giỏi</h4>
                        <p>Đội ngũ bác sĩ chuyên môn cao, nhiều năm kinh nghiệm</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h4>Cơ sở vật chất hiện đại</h4>
                        <p>Trang thiết bị y tế hiện đại, đạt chuẩn quốc tế</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4>Dịch vụ toàn diện</h4>
                        <p>Đa dạng các dịch vụ khám chữa bệnh, chăm sóc sức khỏe</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-4">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h4>Chăm sóc tận tâm</h4>
                        <p>Đội ngũ nhân viên chuyên nghiệp, tận tình chăm sóc</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">DỊCH VỤ NỔI BẬT</h2>
                <div class="divider mx-auto bg-primary" style="width: 50px; height: 3px; margin: 15px auto;"></div>
                <p>Các dịch vụ chăm sóc sức khỏe toàn diện dành cho bạn và gia đình</p>
            </div>
            <div>
                @if(isset($services) && $services->count())
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="left" data-target="#servicesScroller" style="left:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="right" data-target="#servicesScroller" style="right:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <div class="overflow-auto" id="servicesScroller">
                            <div class="d-flex flex-nowrap gap-3">
                            @foreach($services as $service)
                                <div class="card border-0 shadow-sm" style="min-width: 300px;">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-box mb-3">
                                            <i class="fas fa-heartbeat fa-3x text-primary"></i>
                                        </div>
                                        <h4 class="mb-2">{{ $service->name }}</h4>
                                        <!-- <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit($service->description, 120) }}</p>
                                        @if(!is_null($service->price))
                                            <div class="mt-3 fw-semibold text-primary">{{ number_format($service->price, 0, ',', '.') }} đ</div>
                                        @endif -->
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center mb-0">Chưa có dịch vụ nào được hiển thị.</div>
                @endif
            </div>
        </div>
    </section>

    <section id="departments" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">CÁC CHUYÊN KHOA</h2>
                <div class="divider mx-auto bg-primary" style="width: 50px; height: 3px; margin: 15px auto;"></div>
                <p>Khám phá các chuyên khoa chính với dịch vụ y tế toàn diện</p>
            </div>
            <div>
                @if(isset($departments) && $departments->count())
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="left" data-target="#departmentsScroller" style="left:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="right" data-target="#departmentsScroller" style="right:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <div class="overflow-auto" id="departmentsScroller">
                            <div class="d-flex flex-nowrap gap-3">
                            @foreach($departments as $dept)
                                <div class="card border-0 shadow-sm" style="min-width: 260px;">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 160px; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                        <i class="fas fa-hospital-user text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <div class="p-3">
                                        <h5 class="fw-bold text-primary mt-2 mb-1">{{ $dept->name }}</h5>
                                        <p class="card-text small text-muted mb-2">{{ $dept->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center mb-0">Chưa có chuyên khoa nào được hiển thị.</div>
                @endif
            </div>
        </div>
    </section>

    <section id="doctors" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">ĐỘI NGŨ BÁC SĨ</h2>
                <div class="divider mx-auto bg-primary" style="width: 50px; height: 3px; margin: 15px auto;"></div>
                <p>Các chuyên gia hàng đầu, tận tâm và giàu kinh nghiệm</p>
            </div>
            <div>
                @if(isset($doctors) && $doctors->count())
                    <div class="position-relative">
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="left" data-target="#doctorsScroller" style="left:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary rounded-circle position-absolute" data-scroll="right" data-target="#doctorsScroller" style="right:-10px; top:50%; transform:translateY(-50%); z-index:2; width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <div class="overflow-auto" id="doctorsScroller">
                            <div class="d-flex flex-nowrap gap-3">
                            @foreach($doctors as $doc)
                    @php
                        $avatar = !empty($doc->avatar) ? asset('storage/'.$doc->avatar) : 'https://cdn-icons-png.flaticon.com/512/147/147144.png';
                        $name = $doc->user->name ?? 'Bác sĩ';
                        $deptName = $doc->department->name ?? 'Chưa phân khoa';
                        $spec = $doc->specialization ?? null;
                    @endphp
                            <div class="card border-0 shadow-sm text-center p-3" style="min-width: 260px;">
                                <img src="{{ $avatar }}" alt="{{ $name }}" class="doctor-img" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px;">
                                <h5 class="fw-bold mb-1 mt-3">{{ $name }}</h5>
                                <p class="text-muted small mb-1">{{ $deptName }}</p>
                                @if($spec)
                                    <p class="text-muted small mb-3">Chuyên môn: {{ $spec }}</p>
                                @endif
                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#doctorModal{{ $doc->id }}">
                                    <i class="fas fa-info-circle me-1"></i> Hồ sơ chi tiết
                                </a>
                            </div>
                    <!-- Modal Chi Tiết Bác Sĩ (per doctor) -->
                    <div class="modal fade" id="doctorModal{{ $doc->id }}" tabindex="-1" aria-labelledby="doctorModalLabel{{ $doc->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold" id="doctorModalLabel{{ $doc->id }}">Hồ sơ chi tiết - Bác Sĩ {{ $name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img src="{{ $avatar }}" alt="{{ $name }}" class="img-fluid rounded mb-3" style="height:200px; object-fit:cover;">
                                        </div>
                                        <div class="col-md-8">
                                            <p><strong>Khoa:</strong> {{ $deptName }}</p>
                                            @if($spec)
                                                <p><strong>Chuyên môn:</strong> {{ $spec }}</p>
                                            @endif
                                                            
                                            @if(!empty($doc->birth_date))
                                                <p><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($doc->birth_date)->format('d/m/Y') }}</p>
                                            @else
                                                <p><strong>Ngày sinh:</strong> Chưa cập nhật.</p>
                                            @endif
                                            @if(!empty($doc->description))
                                                <p><strong>Mô tả:</strong> {{ $doc->description }}</p>
                                            @else
                                                <p><strong>Mô tả:</strong> Chưa có mô tả.</p>
                                            @endif
                                            {{-- 🩺 Ảnh giấy phép hành nghề --}}
                                            @if(!empty($doc->license_image))
                                                <div class="mt-3">
                                                    <p class="fw-bold mb-2"><i class="fas fa-id-badge text-primary"></i> Ảnh giấy phép hành nghề:</p>
                                                    <img src="{{ asset('storage/'.$doc->license_image) }}" 
                                                        alt="Giấy phép hành nghề của {{ $name }}" 
                                                        class="img-fluid rounded shadow-sm border"
                                                        style="max-height: 300px; object-fit: contain;">
                                                </div>
                                            @else
                                                <p class="text-muted mt-3"><i class="fas fa-id-badge"></i> Chưa có ảnh giấy phép hành nghề.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center mb-0">Chưa có bác sĩ nào được hiển thị.</div>
                @endif
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
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
    (function(){
        function setup(targetId){
            var scroller = document.querySelector(targetId);
            if(!scroller) return;
            function amount(){
                var first = scroller.querySelector('.card');
                return first ? first.getBoundingClientRect().width + 12 : scroller.clientWidth * 0.9;
            }
            document.querySelectorAll('[data-target="'+targetId+'"]').forEach(function(btn){
                btn.addEventListener('click', function(){
                    var dir = btn.getAttribute('data-scroll');
                    var a = amount();
                    var max = scroller.scrollWidth - scroller.clientWidth;
                    if(dir === 'right'){
                        if(scroller.scrollLeft >= max - 5){ scroller.scrollTo({left:0, behavior:'smooth'}); }
                        else { scroller.scrollBy({left:a, behavior:'smooth'}); }
                    } else {
                        if(scroller.scrollLeft <= 5){ scroller.scrollTo({left:max, behavior:'smooth'}); }
                        else { scroller.scrollBy({left:-a, behavior:'smooth'}); }
                    }
                });
            });
        }
        setup('#servicesScroller');
        setup('#departmentsScroller');
        setup('#doctorsScroller');
    })();
    </script>
    </body>
    </html>