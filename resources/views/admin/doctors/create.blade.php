@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">THÊM MỚI</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-user-plus me-2 text-primary"></i>Thêm bác sĩ mới
            </h1>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user-md me-2"></i>Thông tin bác sĩ
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.doctors.store') }}" method="POST">
                @csrf

                {{-- Thông tin tài khoản --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <h5 class="text-primary mb-4 fw-bold d-flex align-items-center">
                        <i class="fas fa-user me-2"></i>Thông tin tài khoản
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-id-card text-primary me-2"></i>Tên bác sĩ <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập tên bác sĩ">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="example@email.com">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-lock text-primary me-2"></i>Mật khẩu <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="password" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Tối thiểu 6 ký tự">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>Số điện thoại
                            </label>
                            <input type="text" name="phone" class="form-control rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="0123456789">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-venus-mars text-primary me-2"></i>Giới tính
                            </label>
                            <select name="gender" class="form-select rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Địa chỉ
                            </label>
                            <input type="text" name="address" class="form-control rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập địa chỉ">
                        </div>
                    </div>
                </div>

                {{-- Thông tin bác sĩ --}}
                <div class="bg-light rounded-4 p-4 mb-4">
                    <h5 class="text-success mb-4 fw-bold d-flex align-items-center">
                        <i class="fas fa-briefcase-medical me-2"></i>Thông tin chuyên môn
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-building text-success me-2"></i>Khoa <span class="text-danger">*</span>
                            </label>
                            <select name="department_id" class="form-select rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                <option value="">-- Chọn khoa --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-award text-success me-2"></i>Chuyên môn <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="specialization" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Ví dụ: Tim mạch, Nội khoa...">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-id-badge text-success me-2"></i>Số giấy phép hành nghề <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="license_number" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';" placeholder="Nhập số giấy phép">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>Lưu bác sĩ
                    </button>
                    <a href="{{ route('admin.doctors.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
