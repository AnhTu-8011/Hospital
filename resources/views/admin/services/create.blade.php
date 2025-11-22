@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">THÊM MỚI</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Thêm dịch vụ mới
            </h1>
        </div>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-stethoscope me-2"></i>Thông tin dịch vụ
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-tag text-primary me-2"></i>Tên dịch vụ <span class="text-danger">*</span>
                    </label>
            <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control rounded-3 border-2 @error('name') is-invalid @enderror" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập tên dịch vụ">
            @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-file-alt text-primary me-2"></i>Gói dịch vụ:
                    </label>
                    <textarea name="description" rows="8" class="form-control rounded-3 border-2 @error('description') is-invalid @enderror" placeholder="Nhập mô tả gói dịch vụ (mỗi dòng một mục)..." style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('description') }}</textarea>
            @php
                $createDescLines = preg_split('/\r\n|\r|\n/', old('description', ''));
                $createDescLines = array_values(array_filter($createDescLines, function ($line) {
                    return trim($line) !== '';
                }));
            @endphp
            @if(!empty($createDescLines))
                        <div class="bg-primary-subtle rounded-3 p-3 mt-2">
                            <small class="text-muted d-block mb-2">Xem trước:</small>
                            <ul class="mb-0 small">
                    @foreach($createDescLines as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
                        </div>
            @endif
            @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-dollar-sign text-primary me-2"></i>Giá <span class="text-danger">*</span>
                        </label>
            <input type="number" name="price" value="{{ old('price') }}"
                               class="form-control rounded-3 border-2 @error('price') is-invalid @enderror" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập giá dịch vụ">
            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-building text-primary me-2"></i>Khoa <span class="text-danger">*</span>
                        </label>
                        <select name="department_id" class="form-select rounded-3 border-2 @error('department_id') is-invalid @enderror" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                <option value="">-- Chọn khoa --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
                    </div>
        </div>

        <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-image text-primary me-2"></i>Ảnh dịch vụ (tùy chọn)
                    </label>
                    <input type="file" name="image" class="form-control rounded-3 border-2 @error('image') is-invalid @enderror" accept="image/*" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <small class="text-muted">Chấp nhận: JPG, PNG, GIF (tối đa 5MB)</small>
            @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">
                <i class="fas fa-clipboard-pulse text-primary me-2"></i>Triệu chứng liên quan (tùy chọn)
            </label>
            <div id="symptoms-container">
                <div class="input-group mb-2">
                    <input type="text" name="symptoms[]" class="form-control rounded-3 border-2 symptom-input" placeholder="Nhập triệu chứng (ví dụ: đau đầu, sốt, ho...)" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    <button type="button" class="btn btn-outline-danger rounded-end-3 remove-symptom" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-2" id="add-symptom">
                <i class="fas fa-plus me-1"></i>Thêm triệu chứng
            </button>
            <small class="text-muted d-block mt-2">Thêm các triệu chứng mà dịch vụ này có thể điều trị. Hệ thống sẽ sử dụng để gợi ý dịch vụ phù hợp cho bệnh nhân.</small>
        </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>Lưu dịch vụ
            </button>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('symptoms-container');
    const addBtn = document.getElementById('add-symptom');
    
    // Hiển thị nút xóa cho input đầu tiên nếu có giá trị
    updateRemoveButtons();
    
    addBtn.addEventListener('click', function() {
        const newInput = document.createElement('div');
        newInput.className = 'input-group mb-2';
        newInput.innerHTML = `
            <input type="text" name="symptoms[]" class="form-control rounded-3 border-2 symptom-input" placeholder="Nhập triệu chứng (ví dụ: đau đầu, sốt, ho...)" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
            <button type="button" class="btn btn-outline-danger rounded-end-3 remove-symptom">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newInput);
        updateRemoveButtons();
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-symptom')) {
            e.target.closest('.input-group').remove();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const inputs = container.querySelectorAll('.input-group');
        inputs.forEach((input, index) => {
            const removeBtn = input.querySelector('.remove-symptom');
            const inputField = input.querySelector('.symptom-input');
            
            // Hiển thị nút xóa nếu có nhiều hơn 1 input hoặc input có giá trị
            if (inputs.length > 1 || (inputField && inputField.value.trim() !== '')) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    // Cập nhật khi người dùng nhập vào input
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('symptom-input')) {
            updateRemoveButtons();
        }
    });
});
</script>
@endpush
@endsection
