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
                    <label class="form-label">Ngày sinh</label>
                    @php
                        $birthdate = $user->patient->birthdate ?? $user->birthdate ?? null;
                        // hiển thị dạng dd/mm/yyyy nếu có giá trị
                        $formattedBirthdate = $birthdate
                            ? (is_string($birthdate)
                                ? \Carbon\Carbon::parse($birthdate)->format('d/m/Y')
                                : $birthdate->format('d/m/Y'))
                            : '';
                    @endphp

                    <div class="row g-2 align-items-center">
                        <div class="col-4">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar text-muted"></i></span>
                                <select id="profile_birth_day" class="form-select">
                                    <option value="">Ngày</option>
                                    @for ($d = 1; $d <= 31; $d++)
                                        <option value="{{ $d }}" {{ old('birth_day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <select id="profile_birth_month" class="form-select">
                                <option value="">Tháng</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('birth_month') == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-4">
                            <input id="profile_birth_year" type="number" min="1900" max="{{ now()->year }}" placeholder="Năm" class="form-control" value="{{ old('birth_year') }}">
                        </div>
                    </div>
                    <input type="hidden" id="birthdate" name="birthdate" value="{{ old('birthdate', $formattedBirthdate) }}">

                    @error('birthdate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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

@push('scripts')
<!-- jQuery & jQuery Mask -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    // Bootstrap form validation
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Avatar preview
    document.getElementById('avatar')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Compose hidden birthdate from day/month/year selectors
    $(document).ready(function(){
        function pad(n){ return (n<10? '0'+n : n); }
        function updateHiddenBirthdate(){
            const d = parseInt($('#profile_birth_day').val(), 10);
            const m = parseInt($('#profile_birth_month').val(), 10);
            const y = parseInt($('#profile_birth_year').val(), 10);
            if(d && m && y){
                $('#birthdate').val(`${y}-${pad(m)}-${pad(d)}`);
            } else {
                // allow clearing birthdate
                if(!d && !m && !y){
                    $('#birthdate').val('');
                }
            }
        }

        // Prefill day/month/year from existing hidden value (accept dd/mm/YYYY or YYYY-MM-DD)
        (function prefill(){
            const oldVal = $('#birthdate').val();
            if(oldVal){
                let y,m,d;
                if(/^\d{2}\/\d{2}\/\d{4}$/.test(oldVal)){
                    const parts = oldVal.split('/');
                    d = parseInt(parts[0],10); m = parseInt(parts[1],10); y = parseInt(parts[2],10);
                } else if(/^\d{4}-\d{2}-\d{2}$/.test(oldVal)){
                    const parts = oldVal.split('-');
                    y = parseInt(parts[0],10); m = parseInt(parts[1],10); d = parseInt(parts[2],10);
                }
                if(y){ $('#profile_birth_year').val(y); }
                if(m){ $('#profile_birth_month').val(m); }
                if(d){ $('#profile_birth_day').val(d); }
            }
        })();

        $('#profile_birth_day, #profile_birth_month, #profile_birth_year').on('change keyup', updateHiddenBirthdate);

        // Ensure hidden value composed before submit
        $('form[action="{{ route('profile.update') }}"]').on('submit', function(){
            updateHiddenBirthdate();
        });
    });
</script>
@endpush
