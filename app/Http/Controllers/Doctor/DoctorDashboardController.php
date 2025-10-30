<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorDashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard của bác sĩ
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor ?? null;

        if (!$doctor) {
            return view('doctor.dashboard', [
                'error' => 'Không tìm thấy thông tin bác sĩ!',
                'appointments' => collect(),
                'pending' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'total' => 0,
                'selectedDate' => now()->format('Y-m-d'),
            ]);
        }

        // Ngày được chọn (mặc định hôm nay)
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        // Lấy danh sách lịch hẹn trong ngày được chọn
        $appointments = Appointment::with(['patient', 'service', 'doctor.user'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $selectedDate)
            ->orderBy('appointment_date')
            ->get();

        // Thống kê theo trạng thái
        $pending = $appointments->where('status', 'pending')->count();
        $confirmed = $appointments->where('status', 'confirmed')->count();
        $completed = $appointments->where('status', 'completed')->count();

        return view('doctor.dashboard', [
            'appointments' => $appointments,
            'doctor' => $doctor,
            'confirmed' => $confirmed,
            'pending' => $pending,
            'completed' => $completed,
            'total' => $appointments->count(),
            'selectedDate' => $selectedDate,
            'error' => null,
        ]);
    }

    /**
     * Trang thông tin cá nhân của bác sĩ
     */
    public function profile()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        return view('doctor.profile', [
            'user' => $user,
            'doctor' => $doctor,
        ]);
    }

    /**
     * Trang chỉnh sửa thông tin cá nhân
     */
    public function edit()
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        return view('doctor.edit', [
            'user' => $user,
            'doctor' => $doctor,
        ]);
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date',
            'address' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|integer|min:0',
        ]);

        // Cập nhật bảng users
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'address' => $validated['address'],
        ]);

        // Cập nhật bảng doctors
        $user->doctor->update([
            'specialty' => $validated['specialty'],
            'qualification' => $validated['qualification'],
            'experience' => $validated['experience'],
        ]);

        return redirect()->route('doctor.profile')
            ->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Cập nhật mật khẩu bác sĩ
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Danh sách lịch hẹn của bác sĩ
     */
    public function appointments()
    {
        $doctor = Auth::user()->doctor;
        $appointments = $doctor->appointments()
            ->with(['patient.user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('doctor.appointments', [
            'appointments' => $appointments,
        ]);
    }
}
