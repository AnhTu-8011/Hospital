@extends('layouts.admin')

@section('title', 'Thêm thuốc mới')

@section('content')
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                    QUẢN LÝ THUỐC
                </p>
                <h1 class="h3 mb-0 fw-bold text-dark">
                    <i class="fas fa-pills me-2 text-primary"></i>
                    Thêm thuốc mới
                </h1>
            </div>
            <div>
                <a href="{{ route('admin.medicines.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Quay lại danh sách
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Card Header --}}
            <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                    <i class="fas fa-plus-circle me-2"></i>
                    Thông tin thuốc
                </h6>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-4">
                <form action="{{ route('admin.medicines.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        {{-- Tên thuốc và Slug --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Tên thuốc <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control rounded-3 @error('name') is-invalid @enderror"
                                   required
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';"
                                   placeholder="Nhập tên thuốc">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text"
                                   name="slug"
                                   value="{{ old('slug') }}"
                                   class="form-control rounded-3 @error('slug') is-invalid @enderror"
                                   placeholder="Tự sinh nếu để trống"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hoạt chất và Tên biệt dược --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hoạt chất (generic name)</label>
                            <input type="text"
                                   name="generic_name"
                                   value="{{ old('generic_name') }}"
                                   class="form-control rounded-3 @error('generic_name') is-invalid @enderror"
                                   placeholder="Nhập hoạt chất"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('generic_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tên biệt dược (brand name)</label>
                            <input type="text"
                                   name="brand_name"
                                   value="{{ old('brand_name') }}"
                                   class="form-control rounded-3 @error('brand_name') is-invalid @enderror"
                                   placeholder="Nhập tên biệt dược"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('brand_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phân loại, Dạng bào chế, Hàm lượng --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phân loại</label>
                            <input type="text"
                                   name="category"
                                   value="{{ old('category') }}"
                                   class="form-control rounded-3 @error('category') is-invalid @enderror"
                                   placeholder="Giảm đau, kháng sinh..."
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Dạng bào chế</label>
                            <input type="text"
                                   name="dosage_form"
                                   value="{{ old('dosage_form') }}"
                                   class="form-control rounded-3 @error('dosage_form') is-invalid @enderror"
                                   placeholder="Viên, siro, tiêm..."
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('dosage_form')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hàm lượng</label>
                            <input type="text"
                                   name="strength"
                                   value="{{ old('strength') }}"
                                   class="form-control rounded-3 @error('strength') is-invalid @enderror"
                                   placeholder="500mg, 250mg/5ml..."
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('strength')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Đơn vị tính, Giá, Tồn kho, Tồn tối thiểu --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Đơn vị tính</label>
                            <input type="text"
                                   name="unit"
                                   value="{{ old('unit') }}"
                                   class="form-control rounded-3 @error('unit') is-invalid @enderror"
                                   placeholder="viên, chai, hộp..."
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Giá (VNĐ)</label>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   value="{{ old('price') }}"
                                   class="form-control rounded-3 @error('price') is-invalid @enderror"
                                   placeholder="Nhập giá"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tồn kho</label>
                            <input type="number"
                                   name="stock"
                                   value="{{ old('stock', 0) }}"
                                   class="form-control rounded-3 @error('stock') is-invalid @enderror"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tồn tối thiểu</label>
                            <input type="number"
                                   name="min_stock"
                                   value="{{ old('min_stock', 10) }}"
                                   class="form-control rounded-3 @error('min_stock') is-invalid @enderror"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nhà sản xuất, Xuất xứ, Mã vạch --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nhà sản xuất</label>
                            <input type="text"
                                   name="manufacturer"
                                   value="{{ old('manufacturer') }}"
                                   class="form-control rounded-3 @error('manufacturer') is-invalid @enderror"
                                   placeholder="Nhập tên nhà sản xuất"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('manufacturer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Xuất xứ</label>
                            <input type="text"
                                   name="origin"
                                   value="{{ old('origin') }}"
                                   class="form-control rounded-3 @error('origin') is-invalid @enderror"
                                   placeholder="Nhập xuất xứ"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('origin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Mã vạch</label>
                            <input type="text"
                                   name="barcode"
                                   value="{{ old('barcode') }}"
                                   class="form-control rounded-3 @error('barcode') is-invalid @enderror"
                                   placeholder="Nhập mã vạch"
                                   style="transition: all 0.3s ease;"
                                   onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                   onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thuốc kê đơn --}}
                        <div class="col-md-4">
                            <div class="form-check mt-4 pt-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       value="1"
                                       id="is_prescription"
                                       name="is_prescription"
                                       {{ old('is_prescription') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_prescription">
                                    Thuốc kê đơn
                                </label>
                            </div>
                        </div>

                        {{-- Chỉ định --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Chỉ định</label>
                            <textarea name="indications"
                                      rows="2"
                                      class="form-control rounded-3 @error('indications') is-invalid @enderror"
                                      placeholder="Nhập chỉ định"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('indications') }}</textarea>
                            @error('indications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chống chỉ định --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Chống chỉ định</label>
                            <textarea name="contraindications"
                                      rows="2"
                                      class="form-control rounded-3 @error('contraindications') is-invalid @enderror"
                                      placeholder="Nhập chống chỉ định"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('contraindications') }}</textarea>
                            @error('contraindications')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tác dụng phụ --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tác dụng phụ</label>
                            <textarea name="side_effects"
                                      rows="2"
                                      class="form-control rounded-3 @error('side_effects') is-invalid @enderror"
                                      placeholder="Nhập tác dụng phụ"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('side_effects') }}</textarea>
                            @error('side_effects')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tương tác thuốc --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tương tác thuốc</label>
                            <textarea name="interactions"
                                      rows="2"
                                      class="form-control rounded-3 @error('interactions') is-invalid @enderror"
                                      placeholder="Nhập tương tác thuốc"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('interactions') }}</textarea>
                            @error('interactions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Liều lượng khuyến cáo --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Liều lượng khuyến cáo</label>
                            <textarea name="dosage"
                                      rows="2"
                                      class="form-control rounded-3 @error('dosage') is-invalid @enderror"
                                      placeholder="Nhập liều lượng khuyến cáo"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('dosage') }}</textarea>
                            @error('dosage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hướng dẫn sử dụng --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Hướng dẫn sử dụng</label>
                            <textarea name="usage"
                                      rows="2"
                                      class="form-control rounded-3 @error('usage') is-invalid @enderror"
                                      placeholder="Nhập hướng dẫn sử dụng"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('usage') }}</textarea>
                            @error('usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Ghi chú thêm --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Ghi chú thêm</label>
                            <textarea name="note"
                                      rows="2"
                                      class="form-control rounded-3 @error('note') is-invalid @enderror"
                                      placeholder="Nhập ghi chú"
                                      style="transition: all 0.3s ease;"
                                      onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                      onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light border rounded-pill px-4">
                            Làm mới
                        </button>
                        <button type="submit"
                                class="btn btn-primary rounded-pill px-4 shadow-sm"
                                style="transition: all 0.3s ease;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                            <i class="fas fa-save me-2"></i>
                            Lưu thuốc
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
