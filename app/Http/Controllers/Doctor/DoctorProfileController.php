<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DoctorProfileController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa hồ sơ bác sĩ.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();
        $departments = Department::all();

        return view('doctor.profile', compact('user', 'doctor', 'departments'));
    }

    /**
     * Cập nhật thông tin hồ sơ bác sĩ.
     * - Cập nhật thông tin trong bảng users.
     * - Cập nhật thông tin trong bảng doctors.
     * - Xử lý upload avatar và ảnh giấy phép hành nghề.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cập nhật User (nếu user có các trường này)
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if (!empty($doctor->avatar) && Storage::disk('public')->exists($doctor->avatar)) {
                Storage::disk('public')->delete($doctor->avatar);
            }

            // Lưu ảnh mới
            $avatarPath = $request->file('avatar')->store('doctors/avatar', 'public');
            $doctor->avatar = $avatarPath;
        }

        // Xử lý upload ảnh giấy phép hành nghề
        if ($request->hasFile('license_image')) {
            // Xóa ảnh cũ nếu có
            if (!empty($doctor->license_image) && Storage::disk('public')->exists($doctor->license_image)) {
                Storage::disk('public')->delete($doctor->license_image);
            }

            // Lưu ảnh mới
            $path = $request->file('license_image')->store('licenses', 'public');
            $doctor->license_image = $path;
        }

        // Cập nhật Doctor
        $doctor->update([
            'department_id' => $validated['department_id'],
            'specialization' => $validated['specialization'],
            'license_number' => $validated['license_number'],
            'description' => $validated['description'] ?? $doctor->description,
            'birth_date' => $validated['birth_date'] ?? $doctor->birth_date,
            'avatar' => $doctor->avatar,
            'license_image' => $doctor->license_image,
        ]);

        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Cập nhật mật khẩu bác sĩ.
     * - Yêu cầu nhập mật khẩu hiện tại để xác thực.
     * - Mật khẩu mới phải được xác nhận và tối thiểu 6 ký tự.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác!']);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Cập nhật mật khẩu thành công!');
    }
}
