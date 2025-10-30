@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-md"></i> Hồ sơ bác sĩ</h1>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-id-card-alt"></i> Thông tin cá nhân & chuyên môn
        </div>
        <div class="card-body">

            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Ảnh đại diện -->
                    <div class="col-md-3 text-center mb-3">
                        <label class="fw-bold d-block">Ảnh đại diện:</label>
                        <img id="avatarPreview" src="{{ $doctor->avatar ? asset('storage/' . $doctor->avatar) : asset('images/default-avatar.png') }}" 
                             class="img-thumbnail rounded mb-2" width="180" height="180">
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control mt-2">
                    </div>

                    <div class="col-md-9">
                        <h5 class="text-primary mb-3"><i class="fas fa-user"></i> Thông tin cá nhân</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại:</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày sinh:</label>
                                <input type="date" name="birth_date" class="form-control"
                                    value="{{ old('birth_date', optional($doctor->birth_date)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giới tính:</label>
                                <select name="gender" class="form-select">
                                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ:</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <hr>
                        <h5 class="text-success mb-3"><i class="fas fa-briefcase-medical"></i> Thông tin chuyên môn</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Khoa:</label>
                                <select name="department_id" class="form-select" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $doctor->department_id == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Chuyên môn:</label>
                                <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số giấy phép hành nghề:</label>
                            <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $doctor->license_number) }}">
                        </div>

                        <!-- Ảnh giấy phép hành nghề -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Ảnh giấy phép hành nghề:</label>
                            @if($doctor->license_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $doctor->license_image) }}" alt="License" class="img-fluid rounded border" width="300" id="licensePreview">
                                </div>
                            @else
                                <img src="{{ asset('images/license-placeholder.png') }}" alt="License" class="img-fluid rounded border mb-2" width="300" id="licensePreview">
                            @endif
                            <input type="file" name="license_image" id="license_image" accept="image/*" class="form-control mt-2">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả bản thân:</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $doctor->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Cập nhật mật khẩu -->
<div class="card shadow mb-4 mt-4">
    <div class="card-header bg-warning text-dark">
        <i class="fas fa-lock"></i> Thay đổi mật khẩu
    </div>
    <div class="card-body">
        <form action="{{ route('doctor.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Mật khẩu hiện tại -->
                    <div class="col-md-4 mb-3 position-relative">
                        <label class="form-label fw-bold">Mật khẩu hiện tại:</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mật khẩu mới -->
                    <div class="col-md-4 mb-3 position-relative">
                        <label class="form-label fw-bold">Mật khẩu mới:</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Xác nhận mật khẩu mới -->
                    <div class="col-md-4 mb-3 position-relative">
                        <label class="form-label fw-bold">Xác nhận mật khẩu mới:</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required minlength="6">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="#new_password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i> Cập nhật mật khẩu
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
