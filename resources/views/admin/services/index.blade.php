@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-primary mb-0">🩺 Danh sách dịch vụ</h4>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary shadow-sm px-3">
        <i class="fas fa-plus-circle me-2"></i> Thêm dịch vụ
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
            <thead class="table-light text-center align-middle">
                <tr>
                    <th>STT</th>
                    <th>Mã dịch vụ</th>
                    <th>Ảnh</th>
                    <th>Tên dịch vụ</th>
                    <th>Gói dịch vụ</th>
                    <th>Giá</th>
                    <th>Khoa</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($services as $service)
                    <tr>
                        <td class="text-center fw-medium">{{ $loop->iteration }}</td>
                        <td class="text-center text-muted">#{{ str_pad($service->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center">
                            @if($service->image)
                                <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $service->name }}</td>
                        <td>
                            @if(!empty($service->description))
                                {{ \Illuminate\Support\Str::limit($service->description, 100) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-success fw-semibold">{{ number_format($service->price, 0, ',', '.') }} đ</td>
                        <td class="text-primary fw-medium">{{ $service->department->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa dịch vụ này không?')" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                            Không có dịch vụ nào được tìm thấy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
