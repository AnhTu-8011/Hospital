@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">Thêm bác sĩ mới</h1>

    <a href="{{ route('admin.doctors.index') }}" class="px-3 py-2 bg-gray-300 text-black rounded">← Quay lại</a>

    <form action="{{ route('admin.doctors.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Thông tin tài khoản --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block">Tên bác sĩ</label>
                <input type="text" name="name" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label class="block">Email</label>
                <input type="email" name="email" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label class="block">Mật khẩu</label>
                <input type="password" name="password" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label class="block">Số điện thoại</label>
                <input type="text" name="phone" class="border rounded p-2 w-full">
            </div>

            <div>
                <label class="block">Giới tính</label>
                <select name="gender" class="border rounded p-2 w-full">
                    <option value="male">Nam</option>
                    <option value="female">Nữ</option>
                    <option value="other">Khác</option>
                </select>
            </div>

            <div>
                <label class="block">Địa chỉ</label>
                <input type="text" name="address" class="border rounded p-2 w-full">
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
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block">Chuyên môn</label>
                <input type="text" name="specialization" class="border rounded p-2 w-full" required>
            </div>

            <div>
                <label class="block">Số giấy phép hành nghề</label>
                <input type="text" name="license_number" class="border rounded p-2 w-full" required>
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">Lưu</button>
    </form>
@endsection
