@extends('layouts.admin')

@section('title', 'Thêm khoa mới')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                THÊM MỚI
            </p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>
                Thêm khoa mới
            </h1>
        </div>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Quay lại
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-building me-2"></i>
                Thông tin khoa
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <form action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Tên khoa --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-tag text-primary me-2"></i>
                        Tên khoa <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           class="form-control rounded-3 border-2"
                           required
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Nhập tên khoa">
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        Mô tả
                    </label>
                    <textarea name="description"
                              rows="8"
                              class="form-control rounded-3 border-2"
                              placeholder="Nhập mô tả về khoa..."
                              style="transition: all 0.3s ease;"
                              onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                              onblur="this.style.borderColor=''; this.style.boxShadow='';"></textarea>
                    @error('description')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active">
                            Kích hoạt khoa
                        </label>
                    </div>
                    @error('is_active')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh khoa --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-image text-primary me-2"></i>
                        Ảnh khoa (tùy chọn)
                    </label>
                    <input type="file"
                           name="image"
                           class="form-control rounded-3 border-2"
                           accept="image/*"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <small class="text-muted">Chấp nhận: JPG, PNG, GIF (tối đa 5MB)</small>
                    @error('image')
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
                        <i class="fas fa-save me-2"></i>
                        Lưu khoa
                    </button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
