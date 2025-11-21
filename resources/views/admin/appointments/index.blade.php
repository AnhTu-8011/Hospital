@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn')

{{-- Thanh tìm kiếm --}}
@include('admin.appointments.search')

{{-- Bảng lịch hẹn --}}
<div class="card shadow-sm border-0 rounded-4 mt-4">
    <div class="card-body table-responsive">
        <table class="table align-middle table-hover table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th>STT</th>
                    <th>Mã Lịch hẹn</th>
                    <th>Bệnh nhân</th>
                    <th>SĐT</th>
                    <th>Bảo hiểm</th>
                    <th>Bác sĩ</th>
                    <th>Dịch vụ</th>
                    <th>Ngày hẹn</th>
                    <th>Ca khám</th>
                    <th>Ghi chú</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                        <td class="text-center text-muted">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="fw-semibold">{{ $appointment->patient->name ?? '-' }}</td>
                        <td>{{ $appointment->patient->phone ?? '-' }}</td>
                        <td>{{ $appointment->patient->insurance_number ?? '-' }}</td>
                        <td>{{ $appointment->doctor->user->name ?? '-' }}</td>
                        <td>{{ $appointment->service->name ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $appointment->medical_examination ?? 'Chưa xác định' }}</td>
                        <td>{{ $appointment->note ?? '-' }}</td>

                        {{-- Thanh toán --}}
                        <td class="text-center">
                            @php
                                $price = $appointment->total ?? ($appointment->service->price ?? 0);
                                $birthdate = $appointment->patient->birthdate ?? null;
                                $discount = 0.8; // mặc định giảm 20%

                                // Nếu sinh trong tháng hiện tại → giảm thêm 10%
                                if ($birthdate && \Carbon\Carbon::parse($birthdate)->format('m') == now()->format('m')) {
                                    $discount = 0.7;
                                }

                                $finalPrice = $price * $discount;
                            @endphp

                            {{-- ✅ Hiển thị trạng thái thanh toán --}}
                            @if($appointment->status === 'canceled' && $appointment->payment_status === 'success')
                                <span class="badge bg-info text-dark">Đã hoàn</span><br>
                                <small class="text-info fw-semibold">
                                    {{ number_format($finalPrice, 0, ',', '.') }} đ
                                </small>
                            @elseif($appointment->payment_status === 'success')
                                <span class="badge bg-success">Thành công</span><br>
                                <small class="text-success fw-semibold">
                                    {{ number_format($finalPrice, 0, ',', '.') }} đ
                                    @if ($discount == 0.7)
                                        <span class="d-block text-success mt-1">
                                            🎉 Giảm thêm 10%
                                        </span>
                                    @endif
                                </small>
                            @elseif($appointment->payment_status === 'failed')
                                <span class="badge bg-danger">Chưa thanh toán</span><br>
                                <small class="text-muted">
                                    {{ number_format($finalPrice, 0, ',', '.') }} đ
                                </small>
                            @else
                                <span class="badge bg-secondary">Không xác định</span><br>
                                <small class="text-muted">
                                    {{ number_format($finalPrice, 0, ',', '.') }} đ
                                </small>
                            @endif
                        </td>

                        {{-- Trạng thái --}}
                        <td class="text-center">
                            @if($appointment->status === 'completed')
                                <span class="badge bg-success">Đã khám</span>
                            @elseif($appointment->status === 'confirmed')
                                <span class="badge bg-primary">Đã duyệt</span>
                            @elseif($appointment->status === 'pending')
                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @elseif($appointment->status === 'canceled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">Không rõ</span>
                            @endif
                        </td>

                        {{-- Hành động --}}
                        <td class="text-center">
                            {{-- Cập nhật trạng thái --}}
                            @if($appointment->status !== 'completed')
                                <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST" class="d-flex align-items-center justify-content-center mb-2 gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm w-auto">
                                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                                        <option value="canceled" {{ $appointment->status === 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Nút xóa --}}
                            <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa lịch hẹn này không?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Không có lịch hẹn nào được tìm thấy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Phân trang --}}
<div class="d-flex justify-content-center mt-4">
    {{ $appointments->links('pagination::bootstrap-5') }}
</div>
@endsection
