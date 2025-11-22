@extends('layouts.admin')

@section('title', 'Quản lý bác sĩ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ BÁC SĨ</p>
        <h4 class="fw-bold text-dark mb-0">🩺 Danh sách bác sĩ</h4>
    </div>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
        <i class="fas fa-plus-circle me-2"></i> Thêm bác sĩ
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-lg border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 px-4" style="border-bottom: 2px solid #f0f0f0 !important;">
        <div class="d-flex align-items-center">
            <i class="fas fa-user-md text-primary me-2"></i>
            <h6 class="fw-bold mb-0 text-dark">Danh sách bác sĩ</h6>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                        <th class="text-center fw-semibold py-3" style="width: 120px;">Mã BS</th>
                        <th class="fw-semibold py-3">Họ tên</th>
                        <th class="fw-semibold py-3">Chuyên khoa</th>
                        <th class="fw-semibold py-3">Email</th>
                        <th class="fw-semibold py-3">Điện thoại</th>
                        <th class="text-center fw-semibold py-3" style="width: 200px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr class="table-row-hover" style="transition: all 0.2s ease;">
                            <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    #{{ str_pad($doctor->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-user-md text-primary me-2"></i> {{ $doctor->user->name ?? '-' }}
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                    {{ $doctor->department->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $doctor->user->email ?? '-' }}</td>
                            <td class="text-muted">{{ $doctor->user->phone ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('admin.doctors.schedule', $doctor->id) }}" 
                                       class="btn btn-sm btn-info rounded-pill px-3 shadow-sm" 
                                       title="Lịch hôm nay">
                                        <i class="fas fa-calendar-check me-1"></i> Lịch
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                                       class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" 
                                                onclick="return confirm('Bạn có chắc muốn xóa bác sĩ này không?')"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                                    <p class="mb-0 fw-semibold">Chưa có bác sĩ nào được thêm.</p>
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
    {{ $doctors->links('pagination::bootstrap-5') }}
</div>

<style>
.table-row-hover:hover {
    background-color: #f8f9ff !important;
    transform: scale(1.01);
}
</style>
@endsection
