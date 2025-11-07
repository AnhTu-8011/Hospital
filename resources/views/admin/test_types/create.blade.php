@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-primary"><i class="fas fa-plus-circle me-2"></i> Thêm loại xét nghiệm mới</h4>

    <form action="{{ route('admin.test-types.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tên loại xét nghiệm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Khoa phụ trách</label>
            <select name="department_id" class="form-select">
                <option value="">-- Chọn khoa --</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.test-types.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
