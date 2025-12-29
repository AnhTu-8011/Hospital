<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
