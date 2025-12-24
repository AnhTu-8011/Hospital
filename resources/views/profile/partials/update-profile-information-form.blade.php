{{-- Email Verification Form (Hidden) --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

{{-- Profile Update Form --}}
<form method="post"
      action="{{ route('profile.update') }}"
      class="needs-validation"
      novalidate
      enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div>
        <div class="row g-3">
            {{-- Avatar Upload --}}
            <div class="text-center mb-4">
                <div class="position-relative d-inline-block">
                    <img id="avatarPreview"
                         src="{{ $user->patient->avatar ? Storage::url($user->patient->avatar) : 'https://cdn-icons-png.flaticon.com/512/147/147144.png' }}"
                         alt="avatar"
                         class="rounded-circle mb-3 border border-3 shadow-lg"
                         width="150"
                         height="150"
                         style="object-fit: cover; border-color: #667eea !important;">
                </div>
                <div class="col-md-6 mx-auto">
                    <input type="file"
                           name="avatar"
                           id="avatar"
                           accept="image/*"
                           class="form-control rounded-3 border-2 mt-2 @error('avatar') is-invalid @enderror"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>
                @error('avatar')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Full Name --}}
            <div class="col-md-6">
                <label for="name" class="form-label">
                    Họ và tên <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <input type="text"
                           id="name"
                           name="name"
                           class="form-control rounded-3 border-2 @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->patient->name ?? $user->name) }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="Nhập họ và tên"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <label for="email" class="form-label">
                    Email <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control rounded-3 border-2 @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}"
                           required
                           autocomplete="username"
                           placeholder="example@email.com"
                           {{ $user->hasVerifiedEmail() ? 'readonly' : '' }}
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                {{-- Email Verification Status --}}
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

            {{-- Phone Number --}}
            <div class="col-md-6">
                <label for="phone" class="form-label">Số điện thoại</label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-phone text-muted"></i>
                    </span>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           class="form-control rounded-end-3 border-2 @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $user->patient->phone ?? $user->phone) }}"
                           placeholder="Nhập số điện thoại"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Birthdate --}}
            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                @php
                    $birthdate = $user->patient->birthdate ?? $user->birthdate ?? null;
                    // Format birthdate as dd/mm/yyyy if exists
                    $formattedBirthdate = $birthdate
                        ? (is_string($birthdate)
                            ? \Carbon\Carbon::parse($birthdate)->format('d/m/Y')
                            : $birthdate->format('d/m/Y'))
                        : '';
                @endphp

                <div class="row g-2 align-items-center">
                    <div class="col-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white rounded-start-3 border-2">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <select id="profile_birth_day"
                                    class="form-select rounded-end-3 border-2"
                                    style="transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                    onblur="this.style.borderColor=''; this.style.boxShadow='';">
                                <option value="">Ngày</option>
                                @for ($d = 1; $d <= 31; $d++)
                                    <option value="{{ $d }}" {{ old('birth_day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <select id="profile_birth_month"
                                class="form-select rounded-3 border-2"
                                style="transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                                onblur="this.style.borderColor=''; this.style.boxShadow='';">
                            <option value="">Tháng</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('birth_month') == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <input id="profile_birth_year"
                               type="number"
                               min="1900"
                               max="{{ now()->year }}"
                               placeholder="Năm"
                               class="form-control rounded-3 border-2"
                               value="{{ old('birth_year') }}"
                               style="transition: all 0.3s ease;"
                               onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                               onblur="this.style.borderColor=''; this.style.boxShadow='';">
                    </div>
                </div>
                <input type="hidden" id="birthdate" name="birthdate" value="{{ old('birthdate', $formattedBirthdate) }}">

                @error('birthdate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Gender --}}
            <div class="col-md-6">
                <label for="gender" class="form-label">Giới tính</label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-venus-mars text-muted"></i>
                    </span>
                    <select id="gender"
                            name="gender"
                            class="form-select rounded-end-3 border-2 @error('gender') is-invalid @enderror"
                            style="transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                            onblur="this.style.borderColor=''; this.style.boxShadow='';">
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

            {{-- Insurance Number --}}
            <div class="col-md-6">
                <label for="insurance_number" class="form-label">Số thẻ BHYT</label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-id-card text-muted"></i>
                    </span>
                    <input type="text"
                           id="insurance_number"
                           name="insurance_number"
                           class="form-control rounded-end-3 border-2 @error('insurance_number') is-invalid @enderror"
                           value="{{ old('insurance_number', $user->patient->insurance_number ?? $user->insurance_number) }}"
                           placeholder="Nhập số thẻ BHYT (nếu có)"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';">
                </div>
                @error('insurance_number')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Address --}}
            <div class="col-12">
                <label for="address" class="form-label">Địa chỉ</label>
                <div class="input-group">
                    <span class="input-group-text bg-white rounded-start-3 border-2">
                        <i class="fas fa-map-marker-alt text-muted"></i>
                    </span>
                    <textarea id="address"
                              name="address"
                              class="form-control rounded-end-3 border-2 @error('address') is-invalid @enderror"
                              rows="2"
                              placeholder="Nhập địa chỉ đầy đủ"
                              style="transition: all 0.3s ease;"
                              onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                              onblur="this.style.borderColor=''; this.style.boxShadow='';">{{ old('address', $user->patient->address ?? $user->address) }}</textarea>
                </div>
                @error('address')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-undo me-1"></i>
                Đặt lại
            </button>
            <button type="submit"
                    class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                <i class="fas fa-save me-2"></i>
                Lưu thay đổi
            </button>
        </div>
    </div>
</form>

{{-- Scripts --}}
@push('scripts')
    {{-- jQuery & jQuery Mask --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
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
        $(document).ready(function() {
            function pad(n) {
                return (n < 10 ? '0' + n : n);
            }

            function updateHiddenBirthdate() {
                const d = parseInt($('#profile_birth_day').val(), 10);
                const m = parseInt($('#profile_birth_month').val(), 10);
                const y = parseInt($('#profile_birth_year').val(), 10);
                if (d && m && y) {
                    $('#birthdate').val(`${y}-${pad(m)}-${pad(d)}`);
                } else {
                    // Allow clearing birthdate
                    if (!d && !m && !y) {
                        $('#birthdate').val('');
                    }
                }
            }

            // Prefill day/month/year from existing hidden value (accept dd/mm/YYYY or YYYY-MM-DD)
            (function prefill() {
                const oldVal = $('#birthdate').val();
                if (oldVal) {
                    let y, m, d;
                    if (/^\d{2}\/\d{2}\/\d{4}$/.test(oldVal)) {
                        const parts = oldVal.split('/');
                        d = parseInt(parts[0], 10);
                        m = parseInt(parts[1], 10);
                        y = parseInt(parts[2], 10);
                    } else if (/^\d{4}-\d{2}-\d{2}$/.test(oldVal)) {
                        const parts = oldVal.split('-');
                        y = parseInt(parts[0], 10);
                        m = parseInt(parts[1], 10);
                        d = parseInt(parts[2], 10);
                    }
                    if (y) {
                        $('#profile_birth_year').val(y);
                    }
                    if (m) {
                        $('#profile_birth_month').val(m);
                    }
                    if (d) {
                        $('#profile_birth_day').val(d);
                    }
                }
            })();

            $('#profile_birth_day, #profile_birth_month, #profile_birth_year').on('change keyup', updateHiddenBirthdate);

            // Ensure hidden value composed before submit
            $('form[action="{{ route('profile.update') }}"]').on('submit', function() {
                updateHiddenBirthdate();
            });
        });
    </script>
@endpush
