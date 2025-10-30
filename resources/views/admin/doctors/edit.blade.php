@extends('layouts.admin')

@section('content')

    <h1 class="text-xl font-bold mb-4">Sửa bác sĩ</h1>

    <a href="{{ route('admin.doctors.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Thông tin tài khoản --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block">Tên bác sĩ</label>
                <input type="text" name="name" class="border rounded p-2 w-full"
                       value="{{ old('name', $doctor->user->name ?? '') }}" required>
            </div>

            <div>
                <label class="block">Email</label>
                <input type="email" name="email" class="border rounded p-2 w-full"
                       value="{{ old('email', $doctor->user->email ?? '') }}" required>
            </div>

            <div>
                <label class="block">Số điện thoại</label>
                <input type="text" name="phone" class="border rounded p-2 w-full"
                       value="{{ old('phone', $doctor->user->phone ?? '') }}">
            </div>

            <div>
                <label class="block">Giới tính</label>
                <select name="gender" class="border rounded p-2 w-full">
                    <option value="male" {{ old('gender', $doctor->user->gender ?? '') == 'male' ? 'selected' : '' }}>Nam</option>
                    <option value="female" {{ old('gender', $doctor->user->gender ?? '') == 'female' ? 'selected' : '' }}>Nữ</option>
                    <option value="other" {{ old('gender', $doctor->user->gender ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="block">Địa chỉ</label>
                <input type="text" name="address" class="border rounded p-2 w-full"
                       value="{{ old('address', $doctor->user->address ?? '') }}">
            </div>
        </div>

        {{-- Thông tin bác sĩ --}}
        <hr class="my-4">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block">Khoa</label>
                <select name="department_id" class="border rounded p-2 w-full" required>
                    <option value="">-- Chọn khoa --</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('department_id', $doctor->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Chuyên môn</label>
                <input type="text" name="specialization" class="border rounded p-2 w-full"
                       value="{{ old('specialization', $doctor->specialization) }}" required>
            </div>

            <div>
                <label class="block">Số giấy phép hành nghề</label>
                <input type="text" name="license_number" class="border rounded p-2 w-full"
                       value="{{ old('license_number', $doctor->license_number) }}" required>
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-green-500 text-black rounded">Cập nhật</button>
    </form>
@endsection
