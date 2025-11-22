@extends('home.frontend')

@section('title', 'Dịch vụ - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Dịch vụ nổi bật</h1>
                <p class="text-muted mb-0">Các dịch vụ chăm sóc sức khỏe toàn diện dành cho bạn và gia đình.</p>
            </div>

            <div>
                @if(isset($services) && $services->count())
                    <div class="row g-4 g-lg-4">
                        @foreach($services as $service)
                            <div class="col-md-4 col-sm-6">
                                <div class="card border h-100 bg-white rounded-4" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3 d-flex justify-content-center">
                                            <div style="width: 160px; height: 160px; overflow:hidden; border-radius: 0.75rem;">
                                                @if(!empty($service->image))
                                                    <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                                        <span class="fw-semibold text-secondary">{{ $service->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <h4 class="mb-2">{{ $service->name }}</h4>
                                        @if(!is_null($service->price))
                                            <p class="text-primary fw-bold mb-0">{{ number_format($service->price, 0, ',', '.') }} đ</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1" aria-labelledby="serviceModalLabel{{ $service->id }}" aria-hidden="true">
                                <div class="modal-dialog" style="max-width:600px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="serviceModalLabel{{ $service->id }}">{{ $service->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-center mb-3">
                                                <div style="width: 200px; height: 200px; margin: 0 auto; overflow:hidden; border-radius: 0.75rem;">
                                                    @if(!empty($service->image))
                                                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                                            <span class="fw-semibold text-secondary">{{ $service->name }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!empty($service->description))
                                                <p style="white-space: pre-line;">{{ $service->description }}</p>
                                            @endif
                                            @if(!is_null($service->price))
                                                <p class="fw-bold mb-0">Giá: <span class="text-primary">{{ number_format($service->price, 0, ',', '.') }} đ</span></p>
                                            @endif
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
                    <div class="alert alert-info text-center mb-0">Chưa có dịch vụ nào được hiển thị.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
