@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn')

{{-- Thanh tìm kiếm --}}
@include('admin.appointments.search')

{{-- Bảng lịch hẹn --}}
<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>Danh sách lịch hẹn
            </h6>
            @php
                $currentStatus = request('status');
                $pendingCount = \App\Models\Appointment::where('status', \App\Models\Appointment::STATUS_PENDING)->count();
            @endphp
            <ul class="nav nav-pills nav-fill bg-white rounded-pill px-1 py-1 mb-0" style="font-size: 0.9rem;">
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-1 {{ $currentStatus === null ? 'active text-white' : 'text-primary' }}"
                       href="{{ route('admin.appointments.index') }}">
                        Tất cả
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-1 {{ $currentStatus === 'pending' ? 'active text-white' : 'text-primary' }}"
                       href="{{ route('admin.appointments.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}">
                        Chờ duyệt @if($pendingCount > 0) ({{ $pendingCount }}) @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-1 {{ $currentStatus === 'confirmed' ? 'active text-white' : 'text-primary' }}"
                       href="{{ route('admin.appointments.index', array_merge(request()->except('page'), ['status' => 'confirmed'])) }}">
                        Đã duyệt
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-3 py-1 {{ $currentStatus === 'canceled' ? 'active text-white' : 'text-primary' }}"
                       href="{{ route('admin.appointments.index', array_merge(request()->except('page'), ['status' => 'canceled'])) }}">
                        Đã hủy
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                        <th class="text-center fw-semibold py-3" style="width: 120px;">Mã lịch hẹn</th>
                        <th class="fw-semibold py-3">Bệnh nhân</th>
                        <th class="fw-semibold py-3">SĐT</th>
                        <th class="fw-semibold py-3">Bảo hiểm</th>
                        <th class="fw-semibold py-3">Bác sĩ</th>
                        <th class="fw-semibold py-3">Dịch vụ</th>
                        <th class="fw-semibold py-3">Ngày hẹn</th>
                        <th class="fw-semibold py-3">Ca khám</th>
                        <th class="fw-semibold py-3">Ghi chú</th>
                        <th class="text-center fw-semibold py-3">Thanh toán</th>
                        <th class="text-center fw-semibold py-3" style="width: 120px;">Trạng thái</th>
                        <th class="text-center fw-semibold py-3" style="width: 180px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="table-row-hover" style="transition: all 0.2s ease;">
                            <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>{{ $appointment->patient->name ?? '-' }}
                            </td>
                            <td class="text-muted">{{ $appointment->patient->phone ?? '-' }}</td>
                            <td>
                                @if($appointment->patient->insurance_number)
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">{{ $appointment->patient->insurance_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-dark">
                                <i class="fas fa-user-md me-2 text-primary"></i>{{ $appointment->doctor->user->name ?? '-' }}
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                    {{ $appointment->service->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-muted text-center">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                            <td class="text-muted small text-center">{{ $appointment->medical_examination ?? 'Chưa xác định' }}</td>
                            <td class="text-muted small">{{ $appointment->note ?? '-' }}</td>

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
                                    <span class="badge bg-info rounded-pill px-3 py-2">Đã hoàn</span>
                                    <div class="text-info fw-semibold small mt-1">
                                        {{ number_format($finalPrice, 0, ',', '.') }} đ
                                    </div>
                                @elseif($appointment->payment_status === 'success')
                                    <span class="badge bg-success rounded-pill px-3 py-2">Thành công</span>
                                    <div class="text-success fw-semibold small mt-1">
                                        {{ number_format($finalPrice, 0, ',', '.') }} đ
                                        @if ($discount == 0.7)
                                            <span class="d-block text-success mt-1">
                                                🎉 Giảm thêm 10%
                                            </span>
                                        @endif
                                    </div>
                                @elseif($appointment->payment_status === 'failed')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Chưa thanh toán</span>
                                    <div class="text-muted small mt-1">
                                        {{ number_format($finalPrice, 0, ',', '.') }} đ
                                    </div>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Không xác định</span>
                                    <div class="text-muted small mt-1">
                                        {{ number_format($finalPrice, 0, ',', '.') }} đ
                                    </div>
                                @endif
                            </td>

                            {{-- Trạng thái --}}
                            <td class="text-center">
                                @if($appointment->status === 'completed')
                                    <span class="badge bg-success rounded-pill px-3 py-2">Đã khám</span>
                                @elseif($appointment->status === 'confirmed')
                                    <span class="badge bg-primary rounded-pill px-3 py-2">Đã duyệt</span>
                                @elseif($appointment->status === 'pending')
                                    <span class="badge bg-warning rounded-pill px-3 py-2">Chờ duyệt</span>
                                @elseif($appointment->status === 'canceled')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Không rõ</span>
                                @endif
                            </td>

                            {{-- Hành động --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    {{-- Cập nhật trạng thái --}}
                                    @if($appointment->status !== 'completed')
                                        <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm rounded-pill" style="width: auto;">
                                                <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                                <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                                                <option value="canceled" {{ $appointment->status === 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" title="Lưu">
                                                <i class="fas fa-save"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Nút xóa --}}
                                    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Bạn có chắc muốn xóa lịch hẹn này không?')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted py-5">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                                    <p class="mb-0 fw-semibold">Không có lịch hẹn nào được tìm thấy.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Phân trang --}}
<div class="d-flex justify-content-center mt-4">
    {{ $appointments->links('pagination::bootstrap-5') }}
</div>

<style>
.table-row-hover:hover {
    background-color: #f8f9ff !important;
    transform: scale(1.01);
}
</style>
@endsection
