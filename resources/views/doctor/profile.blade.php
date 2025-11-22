@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">HỒ SƠ BÁC SĨ</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-user-md me-2 text-primary"></i>Hồ sơ bác sĩ
            </h1>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-lg mb-4 rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-id-card-alt me-2"></i>Thông tin cá nhân & chuyên môn
            </h6>
        </div>
        <div class="card-body p-4">

            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Ảnh đại diện -->
                    <div class="col-md-3 text-center mb-3">
                        <label class="fw-bold d-block mb-3 text-primary">
                            <i class="fas fa-image me-2"></i>Ảnh đại diện
                        </label>
                        <div class="position-relative d-inline-block">
                            <img id="avatarPreview" src="{{ $doctor->avatar ? asset('storage/' . $doctor->avatar) : asset('images/default-avatar.png') }}" 
                                 class="rounded-4 shadow-lg mb-3 border border-3 border-primary" 
                                 width="180" height="180" 
                                 style="object-fit: cover;">
                        </div>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control rounded-3 border-2 mt-2">
                    </div>

                    <div class="col-md-9">
                        <div class="bg-light rounded-4 p-4 mb-4">
                            <h5 class="text-primary mb-4 fw-bold d-flex align-items-center">
                                <i class="fas fa-user me-2"></i>Thông tin cá nhân
                            </h5>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-id-card text-primary me-2"></i>Họ và tên:
                                </label>
                                <input type="text" name="name" class="form-control rounded-3 border-2" value="{{ old('name', $user->name) }}" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">
                                        <i class="fas fa-envelope text-primary me-2"></i>Email:
                                    </label>
                                    <input type="email" name="email" class="form-control rounded-3 border-2 bg-light" value="{{ $user->email }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold mb-2">
                                        <i class="fas fa-phone text-primary me-2"></i>Số điện thoại:
                                    </label>
                                    <input type="text" name="phone" class="form-control rounded-3 border-2" value="{{ old('phone', $user->phone) }}" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-birthday-cake text-primary me-2"></i>Ngày sinh:
                                </label>
                                @php
                                    $birthDate = optional($doctor->birth_date);
                                    $birthDay = old('birth_day', $birthDate ? $birthDate->format('d') : null);
                                    $birthMonth = old('birth_month', $birthDate ? $birthDate->format('m') : null);
                                    $birthYear = old('birth_year', $birthDate ? $birthDate->format('Y') : null);
                                @endphp
                                <div class="d-flex gap-2">
                                    <select name="birth_day" id="birth_day" class="form-select rounded-3 border-2">
                                        <option value="">Ngày</option>
                                        @for ($d = 1; $d <= 31; $d++)
                                            <option value="{{ sprintf('%02d', $d) }}" {{ $birthDay == sprintf('%02d', $d) ? 'selected' : '' }}>{{ $d }}</option>
                                        @endfor
                                    </select>
                                    <select name="birth_month" id="birth_month" class="form-select rounded-3 border-2">
                                        <option value="">Tháng</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ sprintf('%02d', $m) }}" {{ $birthMonth == sprintf('%02d', $m) ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                        @endfor
                                    </select>
                                    <input type="text" name="birth_year" id="birth_year" class="form-control rounded-3 border-2" placeholder="Năm" value="{{ $birthYear }}">
                                </div>
                                <input type="hidden" name="birth_date" id="birth_date" value="{{ old('birth_date', optional($doctor->birth_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">
                                    <i class="fas fa-venus-mars text-primary me-2"></i>Giới tính:
                                </label>
                                <select name="gender" class="form-select rounded-3 border-2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Địa chỉ:
                            </label>
                            <textarea name="address" class="form-control rounded-3 border-2" rows="2" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-light rounded-4 p-4 mb-4">
                    <h5 class="text-success mb-4 fw-bold d-flex align-items-center">
                        <i class="fas fa-briefcase-medical me-2"></i>Thông tin chuyên môn
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-building text-success me-2"></i>Khoa:
                            </label>
                            <select name="department_id" class="form-select rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $doctor->department_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fas fa-award text-success me-2"></i>Chuyên môn:
                            </label>
                            <input type="text" name="specialization" class="form-control rounded-3 border-2" value="{{ old('specialization', $doctor->specialization) }}" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-id-badge text-success me-2"></i>Số giấy phép hành nghề:
                        </label>
                        <input type="text" name="license_number" class="form-control rounded-3 border-2" value="{{ old('license_number', $doctor->license_number) }}" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    </div>

                    <!-- Ảnh giấy phép hành nghề -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-file-image text-success me-2"></i>Ảnh giấy phép hành nghề:
                        </label>
                        @if($doctor->license_image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $doctor->license_image) }}" alt="License" class="img-fluid rounded-4 shadow-sm border border-2" style="max-width: 400px;" id="licensePreview">
                            </div>
                        @else
                            <div class="mb-3">
                                <img src="{{ asset('images/license-placeholder.png') }}" alt="License" class="img-fluid rounded-4 shadow-sm border border-2" style="max-width: 400px;" id="licensePreview">
                            </div>
                        @endif
                        <input type="file" name="license_image" id="license_image" accept="image/*" class="form-control rounded-3 border-2">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-file-alt text-success me-2"></i>Mô tả bản thân:
                        </label>
                        <textarea name="description" class="form-control rounded-3 border-2" rows="6" placeholder="Nhập mô tả về bản thân, kinh nghiệm, chuyên môn... (không giới hạn số ký tự)" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('description', $doctor->description) }}</textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Cập nhật mật khẩu -->
<div class="card border-0 shadow-lg mb-4 mt-4 rounded-4 overflow-hidden">
    <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
            <i class="fas fa-lock me-2"></i>Thay đổi mật khẩu
        </h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('doctor.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Mật khẩu hiện tại -->
                <div class="col-md-4 position-relative">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-key text-warning me-2"></i>Mật khẩu hiện tại:
                    </label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_password" class="form-control rounded-3 border-2" required style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <button type="button" class="btn btn-outline-secondary rounded-end-3 toggle-password" data-target="#current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Mật khẩu mới -->
                <div class="col-md-4 position-relative">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-lock text-warning me-2"></i>Mật khẩu mới:
                    </label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password" class="form-control rounded-3 border-2" required minlength="6" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <button type="button" class="btn btn-outline-secondary rounded-end-3 toggle-password" data-target="#new_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu mới -->
                <div class="col-md-4 position-relative">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-lock-open text-warning me-2"></i>Xác nhận mật khẩu mới:
                    </label>
                    <div class="input-group">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control rounded-3 border-2" required minlength="6" style="transition: all 0.3s ease;" onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        <button type="button" class="btn btn-outline-secondary rounded-end-3 toggle-password" data-target="#new_password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(245, 87, 108, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(245, 87, 108, 0.3)';">
                    <i class="fas fa-key me-2"></i>Cập nhật mật khẩu
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelector('#avatar')?.addEventListener('change', e => preview(e, '#avatarPreview'));
document.querySelector('#license_image')?.addEventListener('change', e => preview(e, '#licensePreview'));

function preview(e, selector) {
    const reader = new FileReader();
    reader.onload = event => document.querySelector(selector).src = event.target.result;
    reader.readAsDataURL(e.target.files[0]);
}
// ghép ngày sinh từ ngày/tháng/năm vào input ẩn birth_date
function updateBirthDate() {
    const day = document.querySelector('#birth_day')?.value;
    const month = document.querySelector('#birth_month')?.value;
    const year = document.querySelector('#birth_year')?.value;
    const target = document.querySelector('#birth_date');

    if (!target) return;

    if (day && month && year && /^\d{4}$/.test(year)) {
        target.value = `${year}-${month}-${day}`;
    } else {
        target.value = '';
    }
}

['#birth_day', '#birth_month', '#birth_year'].forEach(sel => {
    const el = document.querySelector(sel);
    if (el) {
        el.addEventListener('change', updateBirthDate);
        el.addEventListener('input', updateBirthDate);
    }
});

// khởi tạo giá trị birth_date khi load trang
updateBirthDate();
// xem MK
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', () => {
        const input = document.querySelector(button.dataset.target);
        const icon = button.querySelector('i');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
    });
});
</script>
@endpush

@endsection
