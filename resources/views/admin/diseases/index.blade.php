@extends('layouts.admin')

@section('title', 'Quản lý bệnh')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                DANH MỤC
            </p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-virus me-2 text-primary"></i>
                Danh sách bệnh
            </h1>
        </div>
        <a href="{{ route('admin.diseases.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>
            Thêm bệnh
        </a>
    </div>

    {{-- Diseases Table Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>
                Danh sách
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    {{-- Table Header --}}
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tên bệnh</th>
                            <th>Khoa</th>
                            <th>Triệu chứng</th>
                            <th width="160">Thao tác</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody>
                        @forelse($diseases as $disease)
                            <tr>
                                {{-- STT --}}
                                <td>
                                    @php
                                        $stt = (method_exists($diseases, 'firstItem') && $diseases->firstItem())
                                            ? $diseases->firstItem() + $loop->index
                                            : $loop->iteration;
                                    @endphp
                                    {{ $stt }}
                                </td>

                                {{-- Tên bệnh --}}
                                <td class="fw-semibold">{{ $disease->name }}</td>

                                {{-- Khoa --}}
                                <td>
                                    <span class="badge bg-primary">{{ $disease->department->name ?? '-' }}</span>
                                </td>

                                {{-- Triệu chứng --}}
                                <td>
                                    @if($disease->symptoms && $disease->symptoms->count())
                                        <span class="badge bg-info">{{ $disease->symptoms->count() }} triệu chứng</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Thao tác --}}
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.diseases.edit', $disease) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.diseases.destroy', $disease) }}"
                                              method="POST"
                                              onsubmit="return confirm('Xóa bệnh này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Empty State --}}
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Chưa có bản ghi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($diseases, 'links'))
                <div class="card-footer bg-white border-0 py-3 px-4">
                    <div class="d-flex justify-content-end">
                        {{ $diseases->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
