@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">THÊM MỚI</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Thêm bệnh
            </h1>
        </div>
        <a href="{{ route('admin.diseases.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-virus me-2"></i>Thông tin bệnh
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.diseases.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-tag text-primary me-2"></i>Tên bệnh <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control rounded-3 border-2 @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-building text-primary me-2"></i>Khoa <span class="text-danger">*</span>
                        </label>
                        <select name="department_id" class="form-select rounded-3 border-2 @error('department_id') is-invalid @enderror" required>
                            <option value="">-- Chọn khoa --</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-image text-primary me-2"></i>Ảnh (tùy chọn)
                        </label>
                        <input type="file" name="image" class="form-control rounded-3 border-2 @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-file-alt text-primary me-2"></i>Mô tả (tùy chọn)
                    </label>
                    <textarea name="description" rows="6" class="form-control rounded-3 border-2 @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-clipboard-pulse text-primary me-2"></i>Triệu chứng liên quan
                    </label>
                    <div id="symptoms-container">
                        <div class="input-group mb-2">
                            <input type="text" name="symptoms[]" class="form-control rounded-3 border-2 symptom-input" placeholder="Nhập triệu chứng (ví dụ: đau đầu, sốt, ho...)">
                            <button type="button" class="btn btn-outline-danger rounded-end-3 remove-symptom" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill mt-2" id="add-symptom">
                        <i class="fas fa-plus me-1"></i>Thêm triệu chứng
                    </button>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-save me-2"></i>Lưu bệnh
                    </button>
                    <a href="{{ route('admin.diseases.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">Hủy</a>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('symptoms-container');
        const addBtn = document.getElementById('add-symptom');

        function updateRemoveButtons() {
            const groups = container.querySelectorAll('.input-group');
            groups.forEach((group, i) => {
                const btn = group.querySelector('.remove-symptom');
                const input = group.querySelector('.symptom-input');
                btn.style.display = (groups.length > 1 || (input && input.value.trim() !== '')) ? 'block' : 'none';
            });
        }

        addBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="text" name="symptoms[]" class="form-control rounded-3 border-2 symptom-input" placeholder="Nhập triệu chứng (ví dụ: đau đầu, sốt, ho...)">
                <button type="button" class="btn btn-outline-danger rounded-end-3 remove-symptom"><i class="fas fa-times"></i></button>
            `;
            container.appendChild(div);
            updateRemoveButtons();
        });

        container.addEventListener('click', (e) => {
            if (e.target.closest('.remove-symptom')) {
                e.target.closest('.input-group').remove();
                updateRemoveButtons();
            }
        });

        container.addEventListener('input', (e) => {
            if (e.target.classList.contains('symptom-input')) updateRemoveButtons();
        });

        updateRemoveButtons();
    });
</script>
@endpush
@endsection
