@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ LOẠI XÉT NGHIỆM</p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-tags me-2 text-primary"></i>Danh sách loại xét nghiệm
            </h4>
        </div>
        <a href="{{ route('admin.test-types.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Thêm loại xét nghiệm
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>Danh sách loại xét nghiệm
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                            <th class="fw-semibold py-3">Tên loại</th>
                            <th class="fw-semibold py-3">Khoa</th>
                            <th class="fw-semibold py-3">Mô tả</th>
                            <th class="text-center fw-semibold py-3" style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($types as $i => $type)
                        <tr class="table-row-hover" style="transition: all 0.2s ease;">
                            <td class="text-center fw-medium">{{ $i + 1 }}</td>
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-vial text-primary me-2"></i>{{ $type->name }}
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                    {{ $type->department->name ?? '---' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $type->description ?? '---' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.test-types.edit', $type->id) }}" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.test-types.destroy', $type->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Xóa loại này?')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $types->links('pagination::bootstrap-5') }}
    </div>

    <style>
    .table-row-hover:hover {
        background-color: #f8f9ff !important;
        transform: scale(1.01);
    }
    </style>
@endsection
