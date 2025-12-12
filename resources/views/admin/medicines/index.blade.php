@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ THUỐC</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-pills me-2 text-primary"></i>Danh sách thuốc
            </h1>
        </div>
        <div>
            <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Thêm thuốc
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>Danh sách thuốc trong kho
            </h6>
        </div>
        <div class="card-body p-0">
            @if($medicines->count() > 0)
                <div class="table-responsive p-4">
                    <table class="table align-middle table-hover mb-0">
                        <thead style="background: #f8f9ff;">
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>Tên thuốc</th>
                                <th>Hoạt chất</th>
                                <th>Hàm lượng</th>
                                <th>Đơn vị</th>
                                <th class="text-end">Giá</th>
                                <th class="text-center">Tồn</th>
                                <th class="text-center" style="width: 140px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $medicine)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration + ($medicines->currentPage() - 1) * $medicines->perPage() }}</td>
                                    <td class="fw-semibold">{{ $medicine->name }}</td>
                                    <td>{{ $medicine->generic_name }}</td>
                                    <td>{{ $medicine->strength }}</td>
                                    <td>{{ $medicine->unit }}</td>
                                    <td class="text-end">{{ $medicine->price ? number_format($medicine->price, 0, ',', '.') . ' đ' : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $medicine->stock <= $medicine->min_stock ? 'bg-danger' : 'bg-success' }}">
                                            {{ $medicine->stock }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.medicines.edit', $medicine) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.medicines.destroy', $medicine) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa thuốc này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 pb-3">
                    {{ $medicines->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0 mx-4 my-4 rounded-4 border-0 shadow-sm">
                    <div class="text-center py-3">
                        <i class="fas fa-info-circle fa-2x mb-2 text-primary"></i>
                        <p class="mb-0 fw-semibold">Chưa có thuốc nào trong hệ thống.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
