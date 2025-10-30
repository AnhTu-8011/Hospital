@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-primary">Chỉnh sửa người dùng</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light border">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="row g-3">
    @csrf
    @method('PUT')

    <div class="col-md-6">
        <label class="form-label">Họ và tên</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Vai trò</label>
        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror">
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ (int) old('role_id', $user->role_id) === $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Địa chỉ</label>
        <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Lưu thay đổi
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Hủy</a>
    </div>
</form>
@endsection
