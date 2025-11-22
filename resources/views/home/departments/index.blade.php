@extends('home.frontend')

@section('title', 'Chuyên khoa - Bệnh viện PHÚC AN')

@section('content')
    <section class="py-5 py-lg-6 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Các chuyên khoa</h1>
                <p class="text-muted mb-0">Khám phá các chuyên khoa chính với dịch vụ y tế toàn diện tại Bệnh viện Phúc An.</p>
            </div>

            <div>
                @if(isset($departments) && $departments->count())
                    <div class="row g-4 g-lg-4">
                        @foreach($departments as $dept)
                            @php
                                $deptServices = isset($services) ? $services->where('department_id', $dept->id) : collect();
                            @endphp
                            <div class="col-md-4 col-sm-6">
                                <div class="card border h-100 bg-white rounded-4" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#departmentModal{{ $dept->id }}">
                                    <div class="bg-light d-flex justify-content-center pt-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                        <div style="width: 160px; height: 160px; overflow:hidden; border-radius: .75rem;">
                                            @if(!empty($dept->image))
                                                <img src="{{ asset('storage/'.$dept->image) }}" alt="{{ $dept->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                                    <span class="fw-bold text-secondary text-center">{{ $dept->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <h5 class="fw-bold mt-3 mb-2">{{ $dept->name }}</h5>
                                        <p class="text-muted small mb-0" style="white-space: pre-line;">
                                            {{ $dept->description ? \Illuminate\Support\Str::limit($dept->description, 120) : 'Chưa có mô tả cho chuyên khoa này.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="departmentModal{{ $dept->id }}" tabindex="-1" aria-labelledby="departmentModalLabel{{ $dept->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl" style="max-width:90%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="departmentModalLabel{{ $dept->id }}">{{ $dept->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-5 mb-3 mb-md-0">
                                                    <h6 class="fw-bold mb-2">Mô tả chuyên khoa</h6>
                                                    <p class="mb-0" style="white-space: pre-line;">{{ $dept->description ?? 'Chưa có mô tả cho chuyên khoa này.' }}</p>
                                                </div>
                                                <div class="col-md-7">
                                                    <h6 class="fw-bold mb-2">Danh sách dịch vụ của khoa</h6>
                                                    @if($deptServices->count())
                                                        <ul class="list-group mb-0">
                                                            @foreach($deptServices as $service)
                                                                <li class="list-group-item d-flex align-items-start">
                                                                    <div class="me-3" style="width:60px; height:60px; flex-shrink:0;">
                                                                        @if(!empty($service->image))
                                                                            <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                                                        @else
                                                                            <div class="d-flex align-items-center justify-content-center bg-light" style="width: 60px; height: 60px; border-radius: 8px;">
                                                                                <i class="fas fa-stethoscope text-primary"></i>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex-grow-1 d-flex justify-content-between align-items-start">
                                                                        <div class="me-3">
                                                                            <div class="fw-semibold">{{ $service->name }}</div>
                                                                            @if(!empty($service->description))
                                                                                <div class="small text-muted" style="white-space: pre-line;">{{ $service->description }}</div>
                                                                            @endif
                                                                        </div>
                                                                        @if(!is_null($service->price))
                                                                            <span class="badge bg-primary rounded-pill">{{ number_format($service->price, 0, ',', '.') }} đ</span>
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted mb-0">Chưa có dịch vụ nào được gán cho khoa này.</p>
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
                    <div class="alert alert-info text-center mb-0">Chưa có chuyên khoa nào được hiển thị.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
