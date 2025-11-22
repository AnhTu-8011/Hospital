@extends('layouts.profile')

@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase text-primary fw-semibold mb-1" style="letter-spacing: .08em; font-size: 0.85rem;">THÔNG TIN CÁ NHÂN</p>
            <h1 class="h3 mb-0 fw-bold text-dark">
                <i class="fas fa-user-circle me-2 text-primary"></i>Cập nhật thông tin cá nhân
            </h1>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-user-edit me-2"></i>Thông tin cá nhân
            </h6>
        </div>
        <div class="card-body p-4">
        @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-lock me-2"></i>Thay đổi mật khẩu
            </h6>
        </div>
        <div class="card-body p-4">
        @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h6 class="m-0 font-weight-bold text-white d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>Xóa tài khoản
            </h6>
        </div>
        <div class="card-body p-4">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
