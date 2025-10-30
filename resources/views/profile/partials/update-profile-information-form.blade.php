<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Thông tin cá nhân</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                {{-- Avatar --}}
                <div class="text-center mb-4">
                    <img id="avatarPreview"
                        src="{{ $user->patient->avatar ? Storage::url($user->patient->avatar) : 'https://cdn-icons-png.flaticon.com/512/147/147144.png' }}"
                        alt="avatar"
                        class="rounded-circle mb-2"
                        width="120" height="120"
                        style="object-fit: cover;">
                    <div class="col-md-6 mx-auto">
                        <input type="file"
                            name="avatar"
                            id="avatar"
                            accept="image/*"
                            class="form-control mt-2 @error('avatar') is-invalid @enderror">
                    </div>
                    @error('avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Họ và tên --}}
                <div class="col-md-6">
                    <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                        <input type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->patient->name ?? $user->name) }}"
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Nhập họ và tên">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                        <input type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required 
                            autocomplete="username"
                            placeholder="example@email.com"
                            {{ $user->hasVerifiedEmail() ? 'readonly' : '' }}>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 small text-warning">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Email chưa được xác minh.
                            <button form="send-verification" class="btn btn-link p-0 ms-1 text-warning">
                                Gửi lại email xác minh
                            </button>
                        </div>
                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-1 small text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Đã gửi liên kết xác minh mới đến email của bạn.
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Số điện thoại --}}
                <div class="col-md-6">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-phone text-muted"></i></span>
                        <input type="tel"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->patient->phone ?? $user->phone) }}"
                            placeholder="Nhập số điện thoại">
                    </div>
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày sinh --}}
                <div class="col-md-6">
                    <label for="birthdate" class="form-label">Ngày sinh</label>
                    @php
                        $birthdate = $user->patient->birthdate ?? $user->birthdate ?? null;
                        $formattedBirthdate = $birthdate ? (is_string($birthdate) ? $birthdate : $birthdate->format('Y-m-d')) : '';
                    @endphp
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                        <input type="date"
                            name="birthdate"
                            class="form-control @error('birthdate') is-invalid @enderror"
                            value="{{ old('birthdate', $formattedBirthdate) }}"
                            max="{{ now()->format('Y-m-d') }}">
                    </div>

                {{-- Giới tính --}}
                <div class="col-md-6">
                    <label for="gender" class="form-label">Giới tính</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-venus-mars text-muted"></i></span>
                        <select id="gender" 
                                name="gender" 
                                class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" {{ old('gender', $user->patient->gender ?? $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $user->patient->gender ?? $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $user->patient->gender ?? $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Số thẻ BHYT --}}
                <div class="col-md-6">
                    <label for="insurance_number" class="form-label">Số thẻ BHYT</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-id-card text-muted"></i></span>
                        <input type="text"
                            id="insurance_number"
                            name="insurance_number"
                            class="form-control @error('insurance_number') is-invalid @enderror"
                            value="{{ old('insurance_number', $user->patient->insurance_number ?? $user->insurance_number) }}"
                            placeholder="Nhập số thẻ BHYT (nếu có)">
                    </div>
                    @error('insurance_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Địa chỉ --}}
                <div class="col-12">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <textarea 
                            id="address"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="2"
                            placeholder="Nhập địa chỉ đầy đủ">{{ old('address', $user->patient->address ?? $user->address) }}</textarea>
                    </div>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Đặt lại
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
    .input-group-text {
        min-width: 42px;
        justify-content: center;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .form-control.is-invalid, .was-validated .form-control:invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .was-validated .form-control:valid {
        border-color: #198754;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>
@endpush

@push('scripts')
<script>
    // Enable Bootstrap form validation
    (function () {
        'use strict'
        
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
    document.getElementById('avatar')?.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
