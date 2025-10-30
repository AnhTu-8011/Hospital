@extends('layouts.profile')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-calendar-check me-2"></i> Lịch hẹn của tôi
            </h5>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-home me-1"></i> Trang chủ
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($appointments->isEmpty())
                <div class="alert alert-info text-center">Bạn chưa có lịch hẹn nào.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mã lịch hẹn</th>
                                <th>Ngày giờ đặt lịch</th>
                                <th>Ngày khám</th>
                                <th>Ca khám</th>
                                <th>Bác sĩ</th>
                                <th>Dịch vụ</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $index => $appointment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $appointment->created_at ?? ''}}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->medical_examination ?? '---' }}</td>
                                    <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                    <td>
                                        @switch($appointment->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-success">Đã duyệt</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">Đã Khám</span>
                                                @break
                                            @case('canceled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $appointment->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $appointment->note ?? '---' }}</td>
                                    <td>
                                        <a href="{{ route('appointments.show', $appointment->id) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($appointment->status === 'completed')
                                            <a href="{{ route('appointments.record', $appointment->id) }}"
                                               class="btn btn-sm btn-outline-info" title="Hồ sơ khám bệnh">
                                                <i class="fas fa-notes-medical"></i>
                                            </a>
                                        @endif
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('appointments.cancel', $appointment->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này không?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
