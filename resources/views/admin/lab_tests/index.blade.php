@extends('layouts.admin')

@section('title', 'Quản lý xét nghiệm')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                QUẢN LÝ XÉT NGHIỆM
            </p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-vials me-2 text-primary"></i>
                Danh sách xét nghiệm
            </h4>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Lab Tests Table Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-list me-2"></i>
                Danh sách xét nghiệm
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    {{-- Table Header --}}
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th class="text-center fw-semibold py-3" style="width: 60px;">STT</th>
                            <th class="fw-semibold py-3">Tên xét nghiệm</th>
                            <th class="fw-semibold py-3">Khoa</th>
                            <th class="fw-semibold py-3">Hồ sơ khám</th>
                            <th class="fw-semibold py-3">Bệnh nhân</th>
                            <th class="fw-semibold py-3">Ngày yêu cầu</th>
                            <th class="fw-semibold py-3">Bác sĩ</th>
                            <th class="text-center fw-semibold py-3">Trạng thái</th>
                            <th class="text-center fw-semibold py-3">Ảnh kết quả</th>
                            <th class="text-center fw-semibold py-3">Ảnh kết quả phụ</th>
                            <th class="fw-semibold py-3">Ghi chú</th>
                            <th class="text-center fw-semibold py-3" style="width: 180px;">Thao tác</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody>
                        @foreach($labTests as $i => $test)
                            <tr class="table-row-hover" style="transition: all 0.2s ease;">
                                {{-- STT --}}
                                <td class="text-center fw-medium">{{ $i + 1 }}</td>

                                {{-- Tên xét nghiệm --}}
                                <td class="fw-semibold text-dark">
                                    <i class="fas fa-vial text-primary me-2"></i>
                                    {{ $test->test_name }}
                                </td>

                                {{-- Khoa --}}
                                <td>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-3 py-1">
                                        {{ $test->department->name ?? '---' }}
                                    </span>
                                </td>

                                {{-- Hồ sơ khám --}}
                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        #{{ $test->medical_record_id }}
                                    </span>
                                </td>

                                {{-- Bệnh nhân --}}
                                <td class="text-dark">{{ $test->record->patient->name ?? '---' }}</td>

                                {{-- Ngày yêu cầu --}}
                                <td class="text-muted small">
                                    {{ optional($test->created_at)->format('d/m/Y H:i') }}
                                </td>

                                {{-- Bác sĩ --}}
                                <td class="text-muted">
                                    {{ $test->doctor->name ?? $test->doctor->email ?? '---' }}
                                </td>

                                {{-- Trạng thái --}}
                                <td class="text-center">
                                    @if($test->status == 'completed')
                                        <span class="badge bg-success rounded-pill px-3 py-2">Hoàn thành</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill px-3 py-2">Yêu cầu cập nhật ảnh</span>
                                    @endif
                                </td>

                                {{-- Ảnh kết quả --}}
                                <td class="text-center">
                                    @if($test->image)
                                        <img src="{{ asset('storage/'.$test->image) }}"
                                             width="80"
                                             height="80"
                                             class="rounded-3 shadow-sm border border-2 preview-image"
                                             style="object-fit: cover; cursor: pointer;"
                                             data-full-src="{{ asset('storage/'.$test->image) }}">
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>

                                {{-- Ảnh kết quả phụ --}}
                                <td class="text-center">
                                    @if(!empty($test->images) && is_array($test->images) && count($test->images))
                                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                                            @foreach($test->images as $img)
                                                <img src="{{ asset('storage/'.$img) }}"
                                                     width="60"
                                                     height="60"
                                                     style="object-fit:cover; cursor: pointer;"
                                                     class="rounded-3 border border-2 shadow-sm preview-image"
                                                     data-full-src="{{ asset('storage/'.$img) }}">
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>

                                {{-- Ghi chú --}}
                                <td class="text-muted small">{{ $test->note ?? '---' }}</td>

                                {{-- Thao tác --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <a href="{{ route('admin.lab_tests.upload', $test->id) }}"
                                           class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"
                                           title="@if($test->status == 'requested') Upload kết quả @else Cập nhật ảnh @endif">
                                            <i class="fas fa-upload me-1"></i>
                                            @if($test->status == 'requested') Upload @else Cập nhật @endif
                                        </a>
                                        <form action="{{ route('admin.lab_tests.destroy', $test->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm"
                                                    onclick="return confirm('Xóa xét nghiệm này?')"
                                                    title="Xóa">
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

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $labTests->links('pagination::bootstrap-5') }}
    </div>

    {{-- Custom Styles --}}
    <style>
        .table-row-hover:hover {
            background-color: #f8f9ff !important;
            transform: scale(1.01);
        }
    </style>

    {{-- Image Preview Modal --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h6 class="modal-title">Xem ảnh kết quả</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <img id="previewImage" src="" alt="Ảnh kết quả" class="img-fluid rounded-3 shadow-sm">
                </div>
            </div>
        </div>
    </div>

    {{-- Image Preview Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.preview-image');
            const modalEl = document.getElementById('imagePreviewModal');
            if (!modalEl || !images.length) return;

            const modalImage = document.getElementById('previewImage');
            const bsModal = new bootstrap.Modal(modalEl);

            images.forEach(function (img) {
                img.addEventListener('click', function () {
                    const src = this.getAttribute('data-full-src') || this.getAttribute('src');
                    if (!src) return;
                    modalImage.setAttribute('src', src);
                    bsModal.show();
                });
            });
        });
    </script>
@endsection
