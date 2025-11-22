<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <div class="row g-4">
        {{-- Mật khẩu hiện tại --}}
        <div class="col-12">
            <label for="update_password_current_password" class="form-label">
                Mật khẩu hiện tại <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text rounded-start-3 border-2"><i class="fas fa-lock"></i></span>
                <input type="password"
                       id="update_password_current_password"
                       name="current_password"
                       class="form-control rounded-end-3 border-2 @error('current_password','updatePassword') is-invalid @enderror"
                       autocomplete="current-password"
                       placeholder="Nhập mật khẩu hiện tại"
                       required
                       style="transition: all 0.3s ease;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" 
                       onblur="this.style.borderColor=''; this.style.boxShadow='';">
                <button type="button" class="btn btn-outline-secondary rounded-end-3"
                        onclick="togglePassword('update_password_current_password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('current_password','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mật khẩu mới --}}
        <div class="col-12">
            <label for="update_password_password" class="form-label">
                Mật khẩu mới <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text rounded-start-3 border-2"><i class="fas fa-key"></i></span>
                <input type="password"
                       id="update_password_password"
                       name="password"
                       class="form-control rounded-end-3 border-2 @error('password','updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Nhập mật khẩu mới"
                       required
                       style="transition: all 0.3s ease;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" 
                       onblur="this.style.borderColor=''; this.style.boxShadow='';">
                <button type="button" class="btn btn-outline-secondary rounded-end-3"
                        onclick="togglePassword('update_password_password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            {{-- Gợi ý độ mạnh --}}
            <div class="password-requirements mt-2">
                <small class="text-muted">Mật khẩu phải chứa ít nhất:</small>
                <ul class="list-unstyled small text-muted mb-0">
                    <li id="length-req"    class="text-muted"><i class="far fa-circle me-1"></i> 8 ký tự trở lên</li>
                    <li id="uppercase-req" class="text-muted"><i class="far fa-circle me-1"></i> 1 chữ hoa</li>
                    <li id="number-req"    class="text-muted"><i class="far fa-circle me-1"></i> 1 số</li>
                    <li id="special-req"   class="text-muted"><i class="far fa-circle me-1"></i> 1 ký tự đặc biệt</li>
                </ul>
            </div>

            @error('password','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Xác nhận mật khẩu --}}
        <div class="col-12">
            <label for="update_password_password_confirmation" class="form-label">
                Xác nhận mật khẩu <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text rounded-start-3 border-2"><i class="fas fa-check-double"></i></span>
                <input type="password"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       class="form-control rounded-end-3 border-2 @error('password_confirmation','updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Nhập lại mật khẩu mới"
                       required
                       style="transition: all 0.3s ease;" 
                       onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';" 
                       onblur="this.style.borderColor=''; this.style.boxShadow='';">
                <button type="button" class="btn btn-outline-secondary rounded-end-3"
                        onclick="togglePassword('update_password_password_confirmation')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password_confirmation','updatePassword')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if (session('status') === 'password-updated')
        <div class="alert alert-success mt-3">
            <i class="fas fa-check-circle me-2"></i>
            Mật khẩu đã được cập nhật thành công.
        </div>
    @endif

    <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
        <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-undo me-1"></i>Đặt lại
        </button>
        <button type="submit" class="btn btn-lg rounded-pill shadow-lg text-white fw-bold" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(245, 87, 108, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(245, 87, 108, 0.3)';">
            <i class="fas fa-save me-2"></i>Cập nhật mật khẩu
        </button>
    </div>
</form>
@push('scripts')
<script>
document.getElementById('update_password_password').addEventListener('input', function () {
    const p = this.value;
    const req = {
        length: p.length >= 8,
        uppercase: /[A-Z]/.test(p),
        number: /[0-9]/.test(p),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(p)
    };
    for (const [key, ok] of Object.entries(req)) {
        const li = document.getElementById(key + '-req');
        li.className = ok ? 'text-success' : 'text-muted';
        li.querySelector('i').className = ok ? 'fas fa-check-circle me-1' : 'far fa-circle me-1';
    }
});

function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
