@extends('home.frontend')

@section('title', 'Tư vấn triệu chứng - Bệnh viện PHÚC AN')

@section('content')
<section class="py-5 py-lg-6" style="background: linear-gradient(135deg, #e3f2ff 0%, #f6fbff 40%, #ffffff 100%);">
  <div class="container">
    <div class="text-center mb-5">
      <p class="text-uppercase text-primary fw-semibold mb-2" style="letter-spacing: .08em;">TƯ VẤN SỨC KHỎE</p>
      <h1 class="fw-bold mb-2" style="font-size: clamp(2rem, 2.4vw + .6rem, 2.6rem);">Tư vấn theo triệu chứng</h1>
      <p class="text-muted mb-0">Luồng: Nhập triệu chứng → Gợi ý bệnh → Gợi ý khoa → Gợi ý dịch vụ → Đặt lịch</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-7 order-2 order-lg-1">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="p-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex align-items-center text-white">
              <i class="bi bi-clipboard-pulse fs-4 me-2"></i>
              <div>
                <div class="fw-bold">Nhập triệu chứng của bạn</div>
                <small class="text-white-50">Có thể nhập nhiều, cách nhau bằng dấu phẩy</small>
              </div>
            </div>
          </div>
          <div class="card-body p-4">
            <form action="{{ route('advisor.index') }}" method="GET" class="row g-3" id="advisor-form">
              <div class="col-12">
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-white border-2"><i class="bi bi-search text-primary"></i></span>
                  <input type="text" name="symptom" value="{{ $symptomQuery ?? '' }}" class="form-control rounded-end-3 border-2" placeholder="Ví dụ: đau đầu, sốt, ho, đau bụng..." autofocus>
                </div>
                <div class="mt-2">
                  <div class="small text-muted">
                    Cách nhập: nhập <strong>1 hoặc nhiều</strong> triệu chứng, <strong>cách nhau bằng dấu phẩy</strong>.
                    Ví dụ: <em>đau đầu, sốt, ho</em>.
                    Hệ thống sẽ lần lượt <strong>gợi ý bệnh</strong> liên quan, <strong>gợi ý khoa</strong> phù hợp và <strong>gợi ý dịch vụ</strong> tương ứng để bạn đặt lịch.
                  </div>
                </div>
              </div>
              <div class="col-12 d-grid d-md-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold px-4">
                  <i class="bi bi-magic me-2"></i>Tư vấn ngay
                </button>
                <a href="{{ route('modal.appointment') }}" class="btn btn-outline-primary btn-lg rounded-3 px-4">
                  <i class="bi bi-calendar-check me-2"></i>Đặt lịch trực tiếp
                </a>
              </div>
              <div class="col-12">
                <small class="text-muted">Hệ thống sẽ gợi ý bệnh phù hợp, khoa liên quan, dịch vụ tương ứng và cho phép bạn đặt lịch ngay.</small>
              </div>

        @if(($symptomQuery ?? null))
        <div class="mt-4">
          @if(isset($suggestedDiseases) && $suggestedDiseases->isNotEmpty())
            <div class="alert alert-info rounded-4 border-0 shadow-sm mb-4">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-activity me-2 fs-4"></i>
                <div>
                  <h5 class="mb-1 fw-bold">Gợi ý bệnh liên quan</h5>
                  <p class="mb-0 text-muted small">Từ triệu chứng: "<strong>{{ $symptomQuery }}</strong>"</p>
                </div>
              </div>
              <div class="row g-3">
                @foreach($suggestedDiseases as $disease)
                  <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-white h-100">
                      <div class="d-flex align-items-start justify-content-between">
                        <div>
                          <h6 class="fw-bold mb-1">{{ $disease->name }}</h6>
                          <div class="mb-2">
                            @if($disease->department)
                              <span class="badge bg-primary rounded-pill me-2"><i class="bi bi-hospital me-1"></i>{{ $disease->department->name }}</span>
                            @endif
                      @if($disease->symptoms && $disease->symptoms->count())
                        <div class="mt-2">
                          <small class="text-muted d-block mb-1">Triệu chứng:</small>
                          <div class="d-flex flex-wrap gap-2">
                            @foreach($disease->symptoms as $sym)
                              <span class="badge bg-info text-dark rounded-pill">{{ $sym->symptom_name }}</span>
                            @endforeach
                          </div>
                        </div>
                      @endif
                          </div>
                        </div>
                      </div>
                      @if(!empty($disease->description))
                        <p class="text-muted small mb-0" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">{{ $disease->description }}</p>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          @if(isset($suggestedDepartments) && $suggestedDepartments->isNotEmpty())
            <div class="alert alert-primary rounded-4 border-0 shadow-sm mb-4">
              <p class="mb-2 fw-semibold"><i class="bi bi-hospital me-2"></i>Khoa phù hợp:</p>
              <div class="d-flex flex-wrap gap-2">
                @foreach($suggestedDepartments as $dept)
                  <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ $dept->name }}</span>
                @endforeach
              </div>
            </div>
          @endif

          @if(isset($suggestedDoctors) && $suggestedDoctors->isNotEmpty())
            <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4">
              <p class="mb-2 fw-semibold"><i class="bi bi-person-badge me-2"></i>Bác sĩ phù hợp:</p>
              <div class="d-flex flex-wrap gap-2">
                @foreach($suggestedDoctors->take(8) as $doctor)
                  <span class="badge bg-success rounded-pill px-3 py-2 fs-6">{{ $doctor->user->name ?? 'Bác sĩ' }}</span>
                @endforeach
                @if($suggestedDoctors->count() > 8)
                  <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">+{{ $suggestedDoctors->count() - 8 }} bác sĩ khác</span>
                @endif
              </div>
            </div>
          @endif

          @if(isset($suggestedServices) && $suggestedServices->isNotEmpty())
            <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-3">
              <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>
                  <div class="fw-bold">Tìm thấy {{ $suggestedServices->count() }} dịch vụ phù hợp</div>
                  <small class="text-muted">Bạn có thể chọn dịch vụ và đặt lịch ngay</small>
                </div>
              </div>
            </div>
            <div class="row g-4">
              @foreach($suggestedServices as $service)
                <div class="col-md-4 col-sm-6">
                  <div class="card border-0 h-100 bg-white rounded-4 shadow-sm" style="cursor:pointer; transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 24px rgba(13,110,253,.15)';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,.08)';"
                       data-bs-toggle="modal" data-bs-target="#serviceModal{{ $service->id }}">
                    <div class="card-body text-center p-4">
                      <div class="mb-3 d-flex justify-content-center">
                        <div style="width: 160px; height: 160px; overflow:hidden; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                          @if($service->department && !empty($service->department->image))
                            <img src="{{ asset('storage/'.$service->department->image) }}" alt="{{ $service->department->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform .3s;"
                                 onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)';">
                          @else
                            <div class="d-flex align-items-center justify-content-center bg-primary-subtle h-100">
                              <i class="bi bi-hospital text-primary" style="font-size: 3rem;"></i>
                            </div>
                          @endif
                        </div>
                      </div>
                      <h5 class="fw-bold mb-1 text-dark">{{ $service->name }}</h5>
                      @if($service->department)
                        <div class="mb-2"><span class="badge bg-primary"><i class="bi bi-hospital me-1"></i>{{ $service->department->name }}</span></div>
                      @endif
                      @if(!is_null($service->price))
                        <p class="text-primary fw-bold mb-0">{{ number_format($service->price, 0, ',', '.') }} đ</p>
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
                            @if($service->department && !empty($service->department->image))
                              <img src="{{ asset('storage/'.$service->department->image) }}" alt="{{ $service->department->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                              <div class="d-flex align-items-center justify-content-center bg-primary-subtle h-100">
                                <i class="bi bi-hospital text-primary" style="font-size: 4rem;"></i>
                              </div>
                            @endif
                          </div>
                        </div>
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
            <div class="alert alert-light border-0">Không tìm thấy dịch vụ phù hợp với "{{ $symptomQuery }}"</div>
          @endif
        </div>
        @endif
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-5 order-1 order-lg-2">
        <div class="card border-0 shadow-lg rounded-4 h-100">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-diagram-3 text-primary me-2"></i>Các bước thực hiện</h5>
            <ol class="list-group list-group-numbered list-group-flush">
              <li class="list-group-item d-flex align-items-start">
                <i class="bi bi-clipboard-pulse text-primary me-2 mt-1"></i>
                <div>
                  <div class="fw-semibold">Nhập triệu chứng</div>
                  <small class="text-muted">Có thể nhập nhiều, cách nhau bằng dấu phẩy.</small>
                </div>
              </li>
              <li class="list-group-item d-flex align-items-start">
                <i class="bi bi-activity text-success me-2 mt-1"></i>
                <div>
                  <div class="fw-semibold">Gợi ý bệnh</div>
                  <small class="text-muted">Hệ thống xếp hạng theo mức độ trùng khớp triệu chứng.</small>
                </div>
              </li>
              <li class="list-group-item d-flex align-items-start">
                <i class="bi bi-hospital text-info me-2 mt-1"></i>
                <div>
                  <div class="fw-semibold">Gợi ý khoa</div>
                  <small class="text-muted">Dựa theo bệnh/triệu chứng liên quan.</small>
                </div>
              </li>
              <li class="list-group-item d-flex align-items-start">
                <i class="bi bi-heart-pulse text-warning me-2 mt-1"></i>
                <div>
                  <div class="fw-semibold">Gợi ý dịch vụ</div>
                  <small class="text-muted">Các dịch vụ phù hợp để thăm khám.</small>
                </div>
              </li>
              <li class="list-group-item d-flex align-items-start">
                <i class="bi bi-calendar-check text-danger me-2 mt-1"></i>
                <div>
                  <div class="fw-semibold">Đặt lịch</div>
                  <small class="text-muted">Chọn bác sĩ, ngày khám, ca khám và thanh toán.</small>
                </div>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
