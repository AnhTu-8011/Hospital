@extends('layouts.admin')

@section('title', 'Upload kết quả xét nghiệm')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                UPLOAD KẾT QUẢ
            </p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-upload me-2 text-primary"></i>
                Upload kết quả xét nghiệm
            </h1>
            <p class="text-muted mb-0 mt-2">
                Xét nghiệm: <strong>{{ $test->test_name }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.lab_tests.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Quay lại
        </a>
    </div>

    {{-- Upload Form Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-cloud-upload-alt me-2"></i>
                Tải lên kết quả xét nghiệm
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <form action="{{ route('admin.lab_tests.saveUpload', $test->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Ảnh chính --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-image text-primary me-2"></i>
                        Ảnh chính <span class="text-danger">*</span>
                    </label>
                    <input type="file"
                           name="image"
                           class="form-control rounded-3 border-2"
                           required
                           accept="image/*"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <small class="text-muted">Chọn ảnh kết quả xét nghiệm chính</small>
                    @error('image')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh phụ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-images text-primary me-2"></i>
                        Ảnh phụ (nhiều ảnh)
                    </label>
                    <input type="file"
                           name="images[]"
                           class="form-control rounded-3 border-2"
                           multiple
                           accept="image/*"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc (Ctrl+Click hoặc Cmd+Click)</small>
                    @error('images')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="d-flex gap-2 mt-4">
                    <button type="submit"
                            class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-upload me-2"></i>
                        Tải lên
                    </button>
                    <a href="{{ route('admin.lab_tests.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
