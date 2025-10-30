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
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password"
                       id="update_password_current_password"
                       name="current_password"
                       class="form-control @error('current_password','updatePassword') is-invalid @enderror"
                       autocomplete="current-password"
                       placeholder="Nhập mật khẩu hiện tại"
                       required>
                <button type="button" class="btn btn-outline-secondary"
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
                <span class="input-group-text"><i class="fas fa-key"></i></span>
                <input type="password"
                       id="update_password_password"
                       name="password"
                       class="form-control @error('password','updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Nhập mật khẩu mới"
                       required>
                <button type="button" class="btn btn-outline-secondary"
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
                <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                <input type="password"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       class="form-control @error('password_confirmation','updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Nhập lại mật khẩu mới"
                       required>
                <button type="button" class="btn btn-outline-secondary"
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
        <button type="reset" class="btn btn-outline-secondary">
            <i class="fas fa-undo me-1"></i> Đặt lại
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Cập nhật mật khẩu
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
