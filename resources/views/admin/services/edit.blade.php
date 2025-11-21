@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">Chỉnh sửa dịch vụ</h1>

    <a href="{{ route('admin.services.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 max-w-3xl">
        @csrf
        @method('PUT')

        <!-- Tên dịch vụ -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Tên dịch vụ</label>
            <input type="text" name="name" value="{{ old('name', $service->name) }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Mô tả -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Gói dịch vụ:</label>
            <textarea name="description" rows="10" class="w-full border rounded px-3 py-2">{{ old('description', $service->description) }}</textarea>
            @php
                $editDescValue = old('description', $service->description ?? '');
                $editDescLines = preg_split('/\r\n|\r|\n/', $editDescValue);
                $editDescLines = array_values(array_filter($editDescLines, function ($line) {
                    return trim($line) !== '';
                }));
            @endphp
            @if(!empty($editDescLines))
                <ul class="mt-2 text-sm">
                    @foreach($editDescLines as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            @endif
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Giá -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Giá</label>
            <input type="number" name="price" value="{{ old('price', $service->price) }}"
                   class="w-full border rounded px-3 py-2" required>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Khoa -->
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Khoa</label>
            <select name="department_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Chọn khoa --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id', $service->department_id) == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
            @error('department_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Ảnh hiện tại</label>
            @if($service->image)
                <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" class="mb-2">
            @else
                <p class="text-sm text-gray-500 mb-2">Chưa có ảnh.</p>
            @endif

            <label class="block text-sm font-medium mb-1">Đổi ảnh (tùy chọn)</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2" accept="image/*">
            @error('image')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">
                Cập nhật dịch vụ
            </button>
        </div>
    </form>
@endsection
