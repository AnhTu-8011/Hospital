@extends('layouts.admin')

@section('title', 'Quản lý khoa phòng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-0">🏥 Danh sách khoa phòng</h4>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary shadow-sm px-3">
        <i class="fas fa-plus-circle me-2"></i> Thêm khoa
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
        <table class="table align-middle table-hover table-bordered">
            <thead class="table-light text-center">
                <tr>
                    <th>STT</th>
                    <th>Mã khoa</th>
                    <th>Tên khoa</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                        <td class="text-center text-muted">#{{ str_pad($department->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="fw-semibold">{{ $department->name }}</td>
                        <td>{{ $department->description ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa khoa này?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Chưa có khoa nào được thêm.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
