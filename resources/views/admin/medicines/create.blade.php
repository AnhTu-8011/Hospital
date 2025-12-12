@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">QUẢN LÝ THUỐC</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-pills me-2 text-primary"></i>Thêm thuốc mới
            </h1>
        </div>
        <div>
            <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i>Thông tin thuốc
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.medicines.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên thuốc <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-3 @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="form-control rounded-3 @error('slug') is-invalid @enderror" placeholder="Tự sinh nếu để trống">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Hoạt chất (generic name)</label>
                        <input type="text" name="generic_name" value="{{ old('generic_name') }}" class="form-control rounded-3 @error('generic_name') is-invalid @enderror">
                        @error('generic_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên biệt dược (brand name)</label>
                        <input type="text" name="brand_name" value="{{ old('brand_name') }}" class="form-control rounded-3 @error('brand_name') is-invalid @enderror">
                        @error('brand_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Phân loại</label>
                        <input type="text" name="category" value="{{ old('category') }}" class="form-control rounded-3 @error('category') is-invalid @enderror" placeholder="Giảm đau, kháng sinh...">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Dạng bào chế</label>
                        <input type="text" name="dosage_form" value="{{ old('dosage_form') }}" class="form-control rounded-3 @error('dosage_form') is-invalid @enderror" placeholder="Viên, siro, tiêm...">
                        @error('dosage_form')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Hàm lượng</label>
                        <input type="text" name="strength" value="{{ old('strength') }}" class="form-control rounded-3 @error('strength') is-invalid @enderror" placeholder="500mg, 250mg/5ml...">
                        @error('strength')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Đơn vị tính</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" class="form-control rounded-3 @error('unit') is-invalid @enderror" placeholder="viên, chai, hộp...">
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá (VNĐ)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control rounded-3 @error('price') is-invalid @enderror">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tồn kho</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control rounded-3 @error('stock') is-invalid @enderror">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tồn tối thiểu</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 10) }}" class="form-control rounded-3 @error('min_stock') is-invalid @enderror">
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nhà sản xuất</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="form-control rounded-3 @error('manufacturer') is-invalid @enderror">
                        @error('manufacturer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Xuất xứ</label>
                        <input type="text" name="origin" value="{{ old('origin') }}" class="form-control rounded-3 @error('origin') is-invalid @enderror">
                        @error('origin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Mã vạch</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}" class="form-control rounded-3 @error('barcode') is-invalid @enderror">
                        @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-check mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="is_prescription" name="is_prescription" {{ old('is_prescription') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_prescription">
                                Thuốc kê đơn
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Chỉ định</label>
                        <textarea name="indications" rows="2" class="form-control rounded-3 @error('indications') is-invalid @enderror">{{ old('indications') }}</textarea>
                        @error('indications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Chống chỉ định</label>
                        <textarea name="contraindications" rows="2" class="form-control rounded-3 @error('contraindications') is-invalid @enderror">{{ old('contraindications') }}</textarea>
                        @error('contraindications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tác dụng phụ</label>
                        <textarea name="side_effects" rows="2" class="form-control rounded-3 @error('side_effects') is-invalid @enderror">{{ old('side_effects') }}</textarea>
                        @error('side_effects')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tương tác thuốc</label>
                        <textarea name="interactions" rows="2" class="form-control rounded-3 @error('interactions') is-invalid @enderror">{{ old('interactions') }}</textarea>
                        @error('interactions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Liều lượng khuyến cáo</label>
                        <textarea name="dosage" rows="2" class="form-control rounded-3 @error('dosage') is-invalid @enderror">{{ old('dosage') }}</textarea>
                        @error('dosage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Hướng dẫn sử dụng</label>
                        <textarea name="usage" rows="2" class="form-control rounded-3 @error('usage') is-invalid @enderror">{{ old('usage') }}</textarea>
                        @error('usage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Ghi chú thêm</label>
                        <textarea name="note" rows="2" class="form-control rounded-3 @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border rounded-pill px-4">Làm mới</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save me-2"></i>Lưu thuốc
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
