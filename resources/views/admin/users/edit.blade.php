@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">
                CHỈNH SỬA
            </p>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-user-edit me-2 text-primary"></i>
                Chỉnh sửa người dùng
            </h4>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>
            Quay lại
        </a>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Vui lòng kiểm tra lại thông tin:</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        {{-- Card Header --}}
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                Thông tin người dùng
            </h6>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-4">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="row g-4">
                @csrf
                @method('PUT')

                {{-- Họ và tên --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-id-card text-primary me-2"></i>
                        Họ và tên
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           class="form-control rounded-3 border-2 @error('name') is-invalid @enderror"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Nhập họ và tên">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           class="form-control rounded-3 border-2 @error('email') is-invalid @enderror"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="example@email.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Vai trò --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-user-tag text-primary me-2"></i>
                        Vai trò
                    </label>
                    <select name="role_id"
                            class="form-select rounded-3 border-2 @error('role_id') is-invalid @enderror"
                            style="transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                            onblur="this.style.borderColor=''; this.style.boxShadow='';">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ (int) old('role_id', $user->role_id) === $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Điện thoại --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-phone text-primary me-2"></i>
                        Điện thoại
                    </label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="form-control rounded-3 border-2 @error('phone') is-invalid @enderror"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="0123456789">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Địa chỉ --}}
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        Địa chỉ
                    </label>
                    <input type="text"
                           name="address"
                           value="{{ old('address', $user->address) }}"
                           class="form-control rounded-3 border-2 @error('address') is-invalid @enderror"
                           style="transition: all 0.3s ease;"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)';"
                           onblur="this.style.borderColor=''; this.style.boxShadow='';"
                           placeholder="Nhập địa chỉ">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="col-12 d-flex gap-2 mt-4">
                    <button type="submit"
                            class="btn btn-lg rounded-pill shadow-lg text-white fw-bold"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.3s ease;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(102, 126, 234, 0.3)';">
                        <i class="fas fa-save me-2"></i>
                        Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-lg btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-times me-2"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
