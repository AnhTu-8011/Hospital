@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h4 class="text-primary"><i class="fas fa-edit me-2"></i> Cập nhật loại xét nghiệm</h4>

    <form action="{{ route('admin.test-types.update', $type->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên loại xét nghiệm</label>
            <input type="text" name="name" value="{{ old('name', $type->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Khoa phụ trách</label>
            <select name="department_id" class="form-select">
                <option value="">-- Chọn khoa --</option>
                @foreach($departments as $dep)
                    <option value="{{ $dep->id }}" {{ $type->department_id == $dep->id ? 'selected' : '' }}>
                        {{ $dep->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $type->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.test-types.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
