@extends('layouts.profile')

@section('title', 'Lịch hẹn của tôi')

@section('content')
    <div class="container py-4">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                    LỊCH HẸN CỦA TÔI
                </p>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                    Lịch hẹn của tôi
                </h1>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-home me-2"></i>
                Trang chủ
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Appointments Table Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Card Header --}}
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-list me-2"></i>
                    Danh sách lịch hẹn
                </h6>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-0">
                @if($appointments->isEmpty())
                    {{-- Empty State --}}
                    <div class="alert alert-info mb-0 mx-4 my-4 rounded-4 border-0 shadow-sm">
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-primary" style="opacity: 0.5;"></i>
                            <p class="mb-0 fw-semibold">Bạn chưa có lịch hẹn nào.</p>
                        </div>
                    </div>
                @else
                    {{-- Appointments Table --}}
                    <div class="table-responsive p-4">
                        <table class="table align-middle table-hover mb-0">
                            {{-- Table Header --}}
                            <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <tr>
                                    <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                                    <th class="text-center fw-semibold py-3" style="width: 120px;">Mã lịch hẹn</th>
                                    <th class="fw-semibold py-3">Ngày đặt</th>
                                    <th class="fw-semibold py-3">Ngày khám</th>
                                    <th class="fw-semibold py-3">Ca khám</th>
                                    <th class="fw-semibold py-3">Bác sĩ</th>
                                    <th class="fw-semibold py-3">Dịch vụ</th>
                                    <th class="text-center fw-semibold py-3">Trạng thái</th>
                                    <th class="fw-semibold py-3">Ghi chú</th>
                                    <th class="text-center fw-semibold py-3" style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>

                            {{-- Table Body --}}
                            <tbody>
                                @foreach($appointments as $index => $appointment)
                                    <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                        {{-- STT --}}
                                        <td class="text-center fw-medium">{{ $index + 1 }}</td>

                                        {{-- Mã lịch hẹn --}}
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                                #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>

                                        {{-- Ngày đặt --}}
                                        <td class="text-muted small">
                                            {{ $appointment->created_at ? \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') : '---' }}
                                        </td>

                                        {{-- Ngày khám --}}
                                        <td class="text-dark">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </td>

                                        {{-- Ca khám --}}
                                        <td class="text-muted small">{{ $appointment->medical_examination ?? '---' }}</td>

                                        {{-- Bác sĩ --}}
                                        <td class="fw-semibold text-dark">
                                            <i class="fas fa-user-md me-2 text-primary"></i>
                                            {{ $appointment->doctor->user->name ?? 'N/A' }}
                                        </td>

                                        {{-- Dịch vụ --}}
                                        <td>
                                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                                {{ $appointment->service->name ?? 'N/A' }}
                                            </span>
                                        </td>

                                        {{-- Trạng thái --}}
                                        <td class="text-center">
                                            @switch($appointment->status)
                                                @case('pending')
                                                    <span class="badge bg-warning rounded-pill px-3 py-2">Chờ duyệt</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge bg-success rounded-pill px-3 py-2">Đã duyệt</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-info rounded-pill px-3 py-2">Đã Khám</span>
                                                    @break
                                                @case('canceled')
                                                    <span class="badge bg-danger rounded-pill px-3 py-2">Đã hủy</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $appointment->status }}</span>
                                            @endswitch
                                        </td>

                                        {{-- Ghi chú --}}
                                        <td class="text-muted small">{{ $appointment->note ?? '---' }}</td>

                                        {{-- Thao tác --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('appointments.show', $appointment->id) }}"
                                                   class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($appointment->status === 'completed')
                                                    <a href="{{ route('appointments.record', $appointment->id) }}"
                                                       class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"
                                                       title="Hồ sơ khám bệnh">
                                                        <i class="fas fa-notes-medical"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center p-4 border-top">
                        {{ $appointments->appends(request()->all())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        .table-row-hover:hover {
            background-color: #f8f9ff !important;
            transform: scale(1.01);
        }
    </style>
@endsection
