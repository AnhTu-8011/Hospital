@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                QUẢN LÝ DỊCH VỤ
            </p>
            <h4 class="fw-bold text-dark mb-0">🩺 Danh sách dịch vụ</h4>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary shadow-sm px-4 rounded-pill">
            <i class="fas fa-plus-circle me-2"></i>
            Thêm dịch vụ
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Services Table Card --}}
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header bg-white border-0 py-3 px-4" style="border-bottom: 2px solid #f0f0f0 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-stethoscope text-primary me-2"></i>
                <h6 class="fw-bold mb-0 text-dark">Danh sách dịch vụ</h6>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    {{-- Table Header --}}
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                            <th class="text-center fw-semibold py-3" style="width: 120px;">Mã DV</th>
                            <th class="text-center fw-semibold py-3" style="width: 100px;">Ảnh</th>
                            <th class="fw-semibold py-3">Tên dịch vụ</th>
                            <th class="fw-semibold py-3">Mô tả</th>
                            <th class="fw-semibold py-3">Triệu chứng</th>
                            <th class="fw-semibold py-3">Giá</th>
                            <th class="fw-semibold py-3">Khoa</th>
                            <th class="text-center fw-semibold py-3" style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody>
                        @forelse ($services as $service)
                            <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                {{-- STT --}}
                                <td class="text-center fw-medium">
                                    @php
                                        $stt = (method_exists($services, 'firstItem') && $services->firstItem())
                                            ? $services->firstItem() + $loop->index
                                            : $loop->iteration;
                                    @endphp
                                    {{ $stt }}
                                </td>

                                {{-- Mã DV --}}
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        #{{ str_pad($service->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- Ảnh --}}
                                <td class="text-center">
                                    @if($service->image)
                                        <img src="{{ asset('storage/'.$service->image) }}"
                                             alt="{{ $service->name }}"
                                             class="rounded-3 shadow-sm"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-stethoscope text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Tên dịch vụ --}}
                                <td class="fw-semibold text-dark">{{ $service->name }}</td>

                                {{-- Mô tả --}}
                                <td class="text-muted">
                                    @if(!empty($service->description))
                                        {{ \Illuminate\Support\Str::limit($service->description, 80) }}
                                    @else
                                        <span class="text-muted fst-italic">-</span>
                                    @endif
                                </td>

                                {{-- Triệu chứng --}}
                                <td>
                                    @if($service->symptoms && $service->symptoms->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($service->symptoms->take(3) as $symptom)
                                                <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 small">
                                                    <i class="fas fa-clipboard-pulse me-1"></i>
                                                    {{ $symptom->symptom_name }}
                                                </span>
                                            @endforeach
                                            @if($service->symptoms->count() > 3)
                                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 small">
                                                    +{{ $service->symptoms->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small">Chưa có</span>
                                    @endif
                                </td>

                                {{-- Giá --}}
                                <td>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-semibold">
                                        {{ number_format($service->price, 0, ',', '.') }} đ
                                    </span>
                                </td>

                                {{-- Khoa --}}
                                <td>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                        {{ $service->department->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- Hành động --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                           class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc muốn xóa dịch vụ này không?')"
                                                    class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm"
                                                    title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                                        <p class="mb-0 fw-semibold">Không có dịch vụ nào được tìm thấy.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($services, 'links'))
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-end">
                        {{ $services->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
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
