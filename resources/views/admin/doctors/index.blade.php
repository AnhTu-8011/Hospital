@extends('layouts.admin')

@section('title', 'Quản lý bác sĩ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-0">🩺 Danh sách bác sĩ</h4>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary shadow-sm px-3">
        <i class="fas fa-plus-circle me-2"></i> Thêm bác sĩ
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light text-center">
                <tr>
                    <th>STT</th>
                    <th>Mã Bác sĩ</th>
                    <th>Họ tên</th>
                    <th>Chuyên khoa</th>
                    <th>Email</th>
                    <th>Điện thoại</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($doctors as $doctor)
                    <tr>
                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                        <td class="text-center text-muted">#{{ str_pad($doctor->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="fw-semibold">
                            <i class="fas fa-user-md text-primary me-2"></i> {{ $doctor->user->name ?? '-' }}
                        </td>
                        <td class="text-primary fw-medium">{{ $doctor->department->name ?? '-' }}</td>
                        <td>{{ $doctor->user->email ?? '-' }}</td>
                        <td>{{ $doctor->user->phone ?? '-' }}</td>
                        <td class="text-center">
                        {{-- Xem lịch khám hôm nay --}}
                        <a href="{{ route('admin.doctors.schedule', $doctor->id) }}" class="btn btn-sm btn-info me-1">
                            <i class="fas fa-calendar-check me-1"></i> Lịch hôm nay
                        </a>

                        {{-- Sửa --}}
                        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-warning me-1">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- Xóa --}}
                        <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bác sĩ này không?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Chưa có bác sĩ nào được thêm.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
