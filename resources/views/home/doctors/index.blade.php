@extends('home.frontend')

@section('title', 'Đội ngũ bác sĩ - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Đội ngũ bác sĩ</h1>
                <p class="text-muted mb-0">Các chuyên gia hàng đầu, tận tâm và giàu kinh nghiệm tại Bệnh viện Phúc An.</p>
            </div>

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
                            <div class="col-md-3 col-sm-6">
                                <div class="card border text-center p-3 h-100 bg-white rounded-4">
                                    <img src="{{ $avatar }}" alt="{{ $name }}" class="doctor-img" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px;">
                                    <h5 class="fw-bold mb-1 mt-3">{{ $name }}</h5>
                                    <p class="text-muted small mb-1">{{ $deptName }}</p>
                                    @if($spec)
                                        <p class="text-muted small mb-3">Chuyên môn: {{ $spec }}</p>
                                    @endif
                                    <a href="#" class="text-primary small fw-semibold" data-bs-toggle="modal" data-bs-target="#doctorModal{{ $doc->id }}">
                                        <i class="fas fa-info-circle me-1"></i> Hồ sơ chi tiết
                                    </a>
                                </div>
                            </div>

                            <div class="modal fade" id="doctorModal{{ $doc->id }}" tabindex="-1" aria-labelledby="doctorModalLabel{{ $doc->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="max-width:90%;">
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
                @else
                    <div class="alert alert-info text-center mb-0">Chưa có bác sĩ nào được hiển thị.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
