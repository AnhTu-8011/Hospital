@extends('layouts.admin')

@section('title', 'Quản lý khoa phòng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ KHOA PHÒNG</p>
        <h4 class="fw-bold text-dark mb-0">🏥 Danh sách khoa phòng</h4>
    </div>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
        <i class="fas fa-plus-circle me-2"></i> Thêm khoa
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
            <i class="fas fa-building text-primary me-2"></i>
            <h6 class="fw-bold mb-0 text-dark">Danh sách khoa phòng</h6>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                        <th class="text-center fw-semibold py-3" style="width: 120px;">Mã khoa</th>
                        <th class="text-center fw-semibold py-3" style="width: 100px;">Ảnh</th>
                        <th class="fw-semibold py-3">Tên khoa</th>
                        <th class="fw-semibold py-3">Mô tả</th>
                        <th class="text-center fw-semibold py-3" style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                        <tr class="table-row-hover" style="transition: all 0.2s ease;">
                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    #{{ str_pad($department->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                        <td class="text-center">
                            @if($department->image)
                                    <img src="{{ asset('storage/'.$department->image) }}" alt="{{ $department->name }}" 
                                         class="rounded-3 shadow-sm" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                    <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-hospital text-muted"></i>
                                    </div>
                            @endif
                        </td>
                            <td class="fw-semibold text-dark">{{ $department->name }}</td>
                            <td class="text-muted">
                            @if(!empty($department->description))
                                {{ \Illuminate\Support\Str::limit($department->description, 100) }}
                            @else
                                    <span class="text-muted fst-italic">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.departments.edit', $department) }}" 
                                       class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" 
                                       title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" 
                                                onclick="return confirm('Bạn có chắc muốn xóa khoa này?')"
                                                title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                                </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <div class="py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                                    <p class="mb-0 fw-semibold">Chưa có khoa nào được thêm.</p>
                                </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<style>
.table-row-hover:hover {
    background-color: #f8f9ff !important;
    transform: scale(1.01);
}
</style>
@endsection
