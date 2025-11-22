@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">CHỈNH SỬA</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-edit me-2 text-primary"></i>Sửa khoa
            </h1>
        </div>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-building me-2"></i>Thông tin khoa
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.departments.update', $department) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-tag text-primary me-2"></i>Tên khoa <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" value="{{ $department->name }}" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập tên khoa">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-file-alt text-primary me-2"></i>Mô tả
                    </label>
                    <textarea name="description" rows="8" class="form-control rounded-3 border-2" placeholder="Nhập mô tả về khoa..." style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ $department->description }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-image text-primary me-2"></i>Ảnh khoa
                    </label>
                    @if($department->image)
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Ảnh hiện tại:</p>
                            <img src="{{ asset('storage/'.$department->image) }}" alt="{{ $department->name }}" 
                                 class="rounded-4 shadow-sm border border-2" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                    @else
                        <p class="text-muted small mb-3">Chưa có ảnh.</p>
                    @endif
                    <input type="file" name="image" class="form-control rounded-3 border-2" accept="image/*" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                    <a href="{{ route('admin.departments.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
