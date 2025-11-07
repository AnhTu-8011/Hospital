@extends('layouts.profile')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin chi tiết lịch hẹn</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-file-invoice me-1"></i> Xuất hóa đơn
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Mã lịch hẹn:</div>
                        <div class="col-md-8">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ngày giờ đặt lịch:</div>
                        <div class="col-md-8">{{ $appointment->created_at ?? ''}}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ngày khám:</div>
                        <div class="col-md-8">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ca khám:</div>
                        <div class="col-md-8">{{ $appointment->medical_examination ?? '---' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Bác sĩ:</div>
                        <div class="col-md-8">{{ $appointment->doctor->user->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Khoa/Phòng:</div>
                        <div class="col-md-8">{{ $appointment->doctor->department->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Dịch vụ:</div>
                        <div class="col-md-8">{{ $appointment->service->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Ghi chú:</div>
                        <div class="col-md-8">{{ $appointment->note ?? 'Không có ghi chú' }}</div>
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
                    @endphp

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            Tổng giá {{ $discount == 0.7 ? '70%' : '80%' }}:
                        </div>
                        <div class="col-md-8">
                            {{ number_format($finalPrice, 0, ',', '.') }} đ
                        </div>
                    </div>
                    @if ($discount == 0.7)
                        <p class="text-success fw-semibold mt-2">
                            🎉 Bạn được giảm thêm 10% vì sinh trong tháng {{ Carbon::parse($birthdate)->format('m') }}!
                        </p>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Trạng thái:</div>
                        <div class="col-md-8">
                            @if($appointment->status === 'pending')
                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @elseif($appointment->status === 'confirmed')
                                <span class="badge bg-success">Đã duyệt</span>
                            @elseif($appointment->status === 'completed')
                                <span class="badge bg-primary">Đã khám</span>
                            @elseif($appointment->status === 'canceled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $appointment->status ?? 'Không xác định' }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Trạng thái thanh toán:</div>
                        <div class="col-md-8">
                            @if($appointment->status === 'canceled' && $appointment->payment_status === 'success')
                                <span class="badge bg-info text-dark">Đã hoàn</span>
                            @elseif($appointment->payment_status === 'success')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @else
                                <span class="badge bg-danger">Chưa thanh toán</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            @if($appointment->status == 'pending')
                                <form action="{{ route('appointments.cancel', $appointment->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('{{ $appointment->payment_status === 'success' ? ('Lịch hẹn đã được thanh toán. Khi hủy sẽ hoàn tiền ' . number_format($finalPrice, 0, ',', '.') . ' đ. Bạn có chắc chắn muốn hủy?') : 'Bạn có chắc chắn muốn hủy lịch hẹn này?' }}')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times me-1"></i> Hủy lịch hẹn
                                    </button>
                                </form>
                                
                                @if($appointment->payment_status != 'success')
                                    <a href="{{ route('payment.checkout', $appointment->id) }}" class="btn btn-primary">
                                        <i class="fas fa-credit-card me-1"></i> Thanh toán ngay
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection