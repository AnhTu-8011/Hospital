<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    // Hiển thị danh sách tất cả bệnh nhân
    public function index()
    {
        // Lấy danh sách bệnh nhân kèm thông tin người dùng (quan hệ user)
        // latest() → sắp xếp theo thời gian tạo mới nhất
        // paginate(10) → hiển thị 10 bản ghi mỗi trang
        $patients = Patient::with('user')->latest()->paginate(10);

        // Trả về view 'admin.patients.index' với biến $patients
        return view('admin.patients.index', compact('patients'));
    }

    // Hiển thị form thêm mới bệnh nhân
    public function create()
    {
        // Trả về giao diện form thêm bệnh nhân
        return view('admin.patients.create');
    }

    // Lưu thông tin bệnh nhân mới vào cơ sở dữ liệu (tạo user và patient)
    public function store(Request $request)
    {
        // Bước 1: Kiểm tra hợp lệ dữ liệu gửi lên từ form
        $request->validate([
            'name' => 'required|string|max:255',        // Tên bắt buộc, tối đa 255 ký tự
            'email' => 'required|email|unique:users',   // Email phải duy nhất trong bảng users
            'phone' => 'nullable|string|max:15',        // Số điện thoại có thể trống, tối đa 15 ký tự
        ]);

        // Bước 2: Tạo bản ghi người dùng trong bảng users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('123456'), // Gán mật khẩu mặc định (nên buộc đổi khi đăng nhập)
            'role_id' => 3,                 // 3 = mã vai trò bệnh nhân (patient)
        ]);

        // Bước 3: Tạo bản ghi bệnh nhân tương ứng trong bảng patients
        // Kết nối với user thông qua khóa ngoại user_id
        Patient::create([
            'user_id' => $user->id,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Quay lại danh sách với thông báo thành công
        return redirect()->route('admin.patients.index')->with('success', 'Thêm bệnh nhân thành công.');
    }

    // Hiển thị form chỉnh sửa thông tin bệnh nhân
    public function edit(Patient $patient)
    {
        // Truyền dữ liệu bệnh nhân sang view edit
        return view('admin.patients.edit', compact('patient'));
    }

    // Cập nhật thông tin bệnh nhân (cập nhật cả patients và users)
    public function update(Request $request, Patient $patient)
    {
        // Bước 1: Kiểm tra hợp lệ dữ liệu
        $request->validate([
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Bước 2: Cập nhật thông tin trong bảng patients
        $patient->update($request->only(['gender', 'birth_date', 'phone', 'address', 'medical_history']));

        // Bước 3: Cập nhật thông tin name và email trong bảng users (liên kết qua quan hệ user)
        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Quay về danh sách với thông báo thành công
        return redirect()->route('admin.patients.index')->with('success', 'Cập nhật bệnh nhân thành công.');
    }

    // Xóa bệnh nhân khỏi hệ thống (xóa cả user và patient)
    public function destroy(Patient $patient)
    {
        // Xóa bản ghi user liên kết trước (đảm bảo không còn quan hệ)
        $patient->user()->delete();

        // Xóa bản ghi patient sau
        $patient->delete();

        // Quay lại danh sách kèm thông báo thành công
        return redirect()->route('admin.patients.index')->with('success', 'Xóa bệnh nhân thành công.');
    }
}
