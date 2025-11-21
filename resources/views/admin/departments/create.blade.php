@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">Thêm khoa mới</h1>

    <a href="{{ route('admin.departments.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.departments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-3xl">
        @csrf

        <div>
            <label class="block">Tên khoa</label>
            <input type="text" name="name" class="border rounded p-2 w-full" required>
        </div>

        <div>
            <label class="block">Mô tả</label>
            <textarea name="description" rows="10" class="border rounded p-2 w-full"></textarea>
        </div>

        <div>
            <label class="block">Ảnh khoa (tùy chọn)</label>
            <input type="file" name="image" class="border rounded p-2 w-full" accept="image/*">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">Lưu</button>
    </form>
@endsection
