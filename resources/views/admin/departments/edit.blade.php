@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">Sửa khoa</h1>

    <a href="{{ route('admin.departments.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.departments.update', $department) }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-3xl">
        @csrf @method('PUT')

        <div>
            <label class="block">Tên khoa</label>
            <input type="text" name="name" value="{{ $department->name }}" class="border rounded p-2 w-full" required>
        </div>

        <div>
            <label class="block">Mô tả</label>
            <textarea name="description" rows="10" class="border rounded p-2 w-full">{{ $department->description }}</textarea>
        </div>

        <div>
            <label class="block">Ảnh khoa hiện tại</label>
            @if($department->image)
                <img src="{{ asset('storage/'.$department->image) }}" alt="{{ $department->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" class="mb-2">
            @else
                <p class="text-sm text-gray-500 mb-2">Chưa có ảnh.</p>
            @endif

            <label class="block">Đổi ảnh (tùy chọn)</label>
            <input type="file" name="image" class="border rounded p-2 w-full" accept="image/*">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">Cập nhật</button>
    </form>
@endsection
