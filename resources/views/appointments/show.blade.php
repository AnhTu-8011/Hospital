@extends('layouts.profile')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
    <div class="container py-4">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                    CHI TIẾT LỊCH HẸN
                </p>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    Thông tin chi tiết lịch hẹn
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại
                </a>
                <button type="button"
                        class="btn btn-primary rounded-pill px-4 shadow-sm"
                        onclick="window.print()">
                    <i class="fas fa-file-invoice me-2"></i>
                    Xuất hóa đơn
                </button>
            </div>
        </div>

        {{-- Appointment Details Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Card Header --}}
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin lịch hẹn
                </h6>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-4">
                {{-- Appointment Information --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <div class="row g-3">
                        {{-- Mã lịch hẹn --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Mã lịch hẹn</small>
                                    <strong class="text-dark fs-6">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                            #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </strong>
                                </div>
                            </div>
                        </div>

                        {{-- Ngày đặt lịch --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-plus text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Ngày đặt lịch</small>
                                    <strong class="text-dark">
                                        {{ $appointment->created_at ? \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') : '---' }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        {{-- Ngày khám --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Ngày khám</small>
                                    <strong class="text-dark">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        {{-- Ca khám --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Ca khám</small>
                                    <strong class="text-dark">{{ $appointment->medical_examination ?? '---' }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Bác sĩ --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-md text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Bác sĩ</small>
                                    <strong class="text-dark">{{ $appointment->doctor->user->name ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Khoa/Phòng --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Khoa/Phòng</small>
                                    <strong class="text-dark">{{ $appointment->doctor->department->name ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Dịch vụ --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-stethoscope text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Dịch vụ</small>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                        {{ $appointment->service->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Ghi chú --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-sticky-note text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Ghi chú</small>
                                    <strong class="text-dark">{{ $appointment->note ?? 'Không có ghi chú' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @php
                    use Carbon\Carbon;

                    $price = $appointment->total ?? ($appointment->service->price ?? 0);
                    $birthdate = $appointment->patient->birthdate ?? null;
                    $discount = 0.8; // mặc định giảm 20%

                    // Nếu sinh trong tháng hiện tại → giảm thêm 10%
                    if ($birthdate && Carbon::parse($birthdate)->format('m') == now()->format('m')) {
                        $discount = 0.7;
                    }

                    $finalPrice = $price * $discount;

                    // Kiểm tra quy tắc 24 giờ: chỉ cho phép hủy trong vòng 24 giờ kể từ khi đặt lịch
                    $createdAt = Carbon::parse($appointment->created_at);
                    $hoursSinceCreation = now()->diffInHours($createdAt, false);
                    
                    // Chỉ cho phép hủy nếu chưa qua 24 giờ kể từ khi đặt lịch
                    $canCancelByDate = $hoursSinceCreation < 24;
                @endphp

                {{-- Price & Status Information --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <div class="row g-3">
                        {{-- Tổng giá --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dollar-sign text-success me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Tổng giá {{ $discount == 0.7 ? '70%' : '80%' }}</small>
                                    <strong class="text-success fs-5">{{ number_format($finalPrice, 0, ',', '.') }} đ</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Trạng thái --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Trạng thái</small>
                                    @if($appointment->status === 'pending')
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Chờ duyệt</span>
                                    @elseif($appointment->status === 'confirmed')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Đã duyệt</span>
                                    @elseif($appointment->status === 'completed')
                                        <span class="badge bg-primary rounded-pill px-3 py-2">Đã khám</span>
                                    @elseif($appointment->status === 'canceled')
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Đã hủy</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $appointment->status ?? 'Không xác định' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Trạng thái thanh toán --}}
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-info me-3 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Trạng thái thanh toán</small>
                                    @if($appointment->status === 'canceled' && $appointment->payment_status === 'success')
                                        <span class="badge bg-info rounded-pill px-3 py-2">Đã hoàn</span>
                                    @elseif($appointment->payment_status === 'success')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Chưa thanh toán</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Special Discount Alert --}}
                    @if ($discount == 0.7)
                        <div class="alert alert-success rounded-3 border-0 mt-3 mb-0">
                            <i class="fas fa-gift me-2"></i>
                            <strong>🎉 Ưu đãi đặc biệt!</strong>
                            Bạn được giảm thêm 10% vì sinh trong tháng {{ Carbon::parse($birthdate)->format('m') }}!
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                @if(in_array($appointment->status, ['pending', 'confirmed']))
                    <div class="d-flex gap-2 mt-4">
                        {{-- Cancel Appointment Form --}}
                        @if($canCancelByDate)
                            <form action="{{ route('appointments.cancel', $appointment->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  id="cancel-appointment-form-{{ $appointment->id }}">
                                @csrf
                                @method('PATCH')
                                <button type="button"
                                        class="btn btn-lg btn-danger rounded-pill px-4 shadow-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#cancelConfirmModal-{{ $appointment->id }}">
                                    <i class="fas fa-times me-2"></i>
                                    Hủy lịch hẹn
                                </button>

                                {{-- Cancel Confirmation Modal --}}
                                <div class="modal fade"
                                     id="cancelConfirmModal-{{ $appointment->id }}"
                                     tabindex="-1"
                                     aria-labelledby="cancelConfirmModalLabel-{{ $appointment->id }}"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cancelConfirmModalLabel-{{ $appointment->id }}">
                                                    Xác nhận hủy lịch hẹn
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($appointment->payment_status === 'success')
                                                    <p class="mb-2">Lịch hẹn này đã được <strong>thanh toán</strong>.</p>
                                                    <p class="mb-2">
                                                        Khi hủy, bạn sẽ được <strong>hoàn tiền khoảng {{ number_format($finalPrice, 0, ',', '.') }} đ</strong>.
                                                    </p>
                                                    <p class="text-muted mb-0">
                                                        Thời gian tiền về tài khoản có thể mất vài ngày làm việc tùy ngân hàng/đơn vị thanh toán.
                                                    </p>
                                                @else
                                                    <p class="mb-0">Bạn có chắc chắn muốn hủy lịch hẹn này?</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Không, quay lại
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    Có, hủy lịch hẹn
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif

                        {{-- Payment Button --}}
                        @if($appointment->payment_status != 'success')
                            <a href="{{ route('payment.checkout', $appointment->id) }}"
                               class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                                <i class="fas fa-credit-card me-2"></i>
                                Thanh toán ngay
                            </a>
                        @endif
                    </div>
                @endif

                {{-- View Medical Record Button --}}
                @if($appointment->status == 'completed')
                    <div class="mt-4">
                        <a href="{{ route('appointments.record', $appointment->id) }}"
                           class="btn btn-lg btn-outline-primary rounded-pill px-4 shadow-sm">
                            <i class="fas fa-notes-medical me-2"></i>
                            Xem bệnh án
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
