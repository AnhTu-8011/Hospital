<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorDashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard của bác sĩ.
     * - Hiển thị danh sách lịch hẹn trong ngày được chọn.
     * - Thống kê số lượng lịch hẹn theo trạng thái (pending, confirmed, completed).
     * - Cho phép lọc theo ngày.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor ?? null;

        // Kiểm tra nếu không tìm thấy thông tin bác sĩ
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
     * Hiển thị trang thông tin cá nhân của bác sĩ.
     *
     * @return \Illuminate\View\View
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
     * Hiển thị trang chỉnh sửa thông tin cá nhân.
     *
     * @return \Illuminate\View\View
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
     * Cập nhật thông tin cá nhân của bác sĩ.
     * - Cập nhật thông tin trong bảng users.
     * - Cập nhật thông tin trong bảng doctors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
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
     * Cập nhật mật khẩu bác sĩ.
     * - Yêu cầu nhập mật khẩu hiện tại để xác thực.
     * - Mật khẩu mới phải được xác nhận và tối thiểu 8 ký tự.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Cập nhật mật khẩu mới
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Hiển thị danh sách lịch hẹn của bác sĩ.
     * - Hiển thị tất cả lịch hẹn của bác sĩ hiện tại.
     * - Sắp xếp theo ngày khám giảm dần (mới nhất trước).
     *
     * @return \Illuminate\View\View
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
