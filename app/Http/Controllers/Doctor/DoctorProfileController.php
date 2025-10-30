<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();
        $departments = Department::all();

        return view('doctor.profile', compact('user', 'doctor', 'departments'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        // ✅ Validate
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date', // ⚡ đổi đúng tên trường
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Cập nhật User (nếu user có các trường này)
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // ✅ Xử lý avatar
        if ($request->hasFile('avatar')) {
            if (!empty($doctor->avatar) && Storage::disk('public')->exists($doctor->avatar)) {
                Storage::disk('public')->delete($doctor->avatar);
            }

            $avatarPath = $request->file('avatar')->store('doctors/avatar', 'public');
            $doctor->avatar = $avatarPath;
        }

        // ảnh hành nghề
        if ($request->hasFile('license_image')) {
            // Xóa ảnh cũ nếu có (đúng disk public)
            if (!empty($doctor->license_image) && Storage::disk('public')->exists($doctor->license_image)) {
                Storage::disk('public')->delete($doctor->license_image);
            }
            $path = $request->file('license_image')->store('licenses', 'public');
            $doctor->license_image = $path;
        }

        // ✅ Cập nhật Doctor
        $doctor->update([
            'department_id' => $validated['department_id'],
            'specialization' => $validated['specialization'],
            'license_number' => $validated['license_number'],
            'description' => $validated['description'] ?? $doctor->description,
            'birth_date' => $validated['birth_date'] ?? $doctor->birth_date, // ⚡ thêm dòng này
            'avatar' => $doctor->avatar,
            'license_image' => $doctor->license_image,
        ]);

        return redirect()->back()->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác!']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Cập nhật mật khẩu thành công!');
    }
}
