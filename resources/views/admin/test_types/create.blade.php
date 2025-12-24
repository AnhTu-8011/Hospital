@extends('layouts.admin')

@section('title', 'Thêm loại xét nghiệm mới')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                THÊM MỚI
            </p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>
                Thêm loại xét nghiệm mới
            </h1>
        </div>
        <a href="{{ route('admin.test-types.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Quay lại
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-vial me-2"></i>
                Thông tin loại xét nghiệm
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <form action="{{ route('admin.test-types.store') }}" method="POST">
                @csrf

                {{-- Tên loại xét nghiệm --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-tag text-primary me-2"></i>
                        Tên loại xét nghiệm <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           class="form-control rounded-3 border-2"
                           required
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Nhập tên loại xét nghiệm">
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Khoa phụ trách --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-building text-primary me-2"></i>
                        Khoa phụ trách
                    </label>
                    <select name="department_id"
                            class="form-select rounded-3 border-2"
                            style="transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                            onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
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
                              class="form-control rounded-3 border-2"
                              rows="4"
                              placeholder="Nhập mô tả..."
                              style="transition: all 0.3s ease;"
                              onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                              onblur="this.style.borderColor=''; this.style.boxShadow='';"></textarea>
                    @error('description')
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
                        Lưu
                    </button>
                    <a href="{{ route('admin.test-types.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
