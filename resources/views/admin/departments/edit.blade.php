@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">Sửa khoa</h1>

    <a href="{{ route('admin.departments.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.departments.update', $department) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block">Tên khoa</label>
            <input type="text" name="name" value="{{ $department->name }}" class="border rounded p-2 w-full" required>
        </div>

        <div>
            <label class="block">Mô tả</label>
            <textarea name="description" class="border rounded p-2 w-full">{{ $department->description }}</textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">Cập nhật</button>
    </form>
@endsection
