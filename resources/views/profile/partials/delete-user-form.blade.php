<div class="space-y-6">
    {{-- Delete Account Button --}}
    <button type="button"
            class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
            style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); transition: all 0.3s ease;"
            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(250, 112, 154, 0.4)';"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(250, 112, 154, 0.3)';"
            data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletion">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ __('Xóa tài khoản') }}
    </button>

    {{-- Confirmation Modal --}}
    <div class="modal fade"
         id="confirmUserDeletion"
         tabindex="-1"
         aria-labelledby="confirmUserDeletionLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                {{-- Modal Header --}}
                <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 0.5rem 0.5rem 0 0 !important;">
                    <h5 class="modal-title fw-bold" id="confirmUserDeletionLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('Xác nhận xóa tài khoản') }}
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                {{-- Delete Account Form --}}
                <form method="post"
                      action="{{ route('profile.destroy') }}"
                      class="needs-validation"
                      novalidate>
                    @csrf
                    @method('delete')

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-soft-danger text-danger rounded-circle font-size-24">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <h5 class="font-size-16 text-danger mb-3">
                                {{ __('Bạn có chắc chắn muốn xóa tài khoản?') }}
                            </h5>
                            <p class="text-muted mb-0">
                                {{ __('Tất cả dữ liệu cá nhân, lịch hẹn và thông tin liên quan sẽ bị xóa vĩnh viễn. Hành động này không thể hoàn tác.') }}
                            </p>
                        </div>

                        {{-- Warning Alert --}}
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>{{ __('Cảnh báo:') }}</strong>
                            {{ __('Vui lòng đảm bảo bạn đã sao lưu tất cả dữ liệu quan trọng trước khi tiếp tục.') }}
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="form-group mt-4">
                            <label for="password" class="form-label">
                                {{ __('Nhập mật khẩu để xác nhận') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       placeholder="Nhập mật khẩu của bạn"
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            {{-- Error Display --}}
                            @error('password', 'userDeletion')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer border-top">
                        <button type="button"
                                class="btn btn-outline-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            {{ __('Hủy bỏ') }}
                        </button>
                        <button type="submit"
                                class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
                                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); transition: all 0.3s ease;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(250, 112, 154, 0.4)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(250, 112, 154, 0.3)';">
                            <i class="fas fa-trash-alt me-2"></i>
                            {{ __('Xóa tài khoản') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Styles --}}
@push('styles')
    <style>
        .avatar-lg {
            height: 5rem;
            width: 5rem;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .bg-soft-danger {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }

        .modal-content {
            border: none;
            border-radius: .5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        }

        .modal-header {
            border-top-left-radius: .5rem;
            border-top-right-radius: .5rem;
            padding: 1.25rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
        }

        .btn-close {
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
            opacity: .8;
        }

        .btn-close:hover {
            opacity: 1;
        }
    </style>
@endpush

{{-- Scripts --}}
@push('scripts')
    <script>
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endpush
