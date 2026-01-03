<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorAppointmentController extends Controller
{
    // Bác sĩ đánh dấu lịch hẹn đã hoàn thành (chỉ bác sĩ được phân công mới có quyền)
    public function complete(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Kiểm tra quyền: chỉ cho phép bác sĩ đúng của lịch hẹn
        // - User phải tồn tại
        // - User phải có role và role phải là 'doctor'
        // - User phải có thông tin doctor
        // - Doctor ID của user phải khớp với doctor_id của lịch hẹn
        if (!$user || !$user->role || strtolower(trim($user->role->name)) !== 'doctor' || !$user->doctor || $user->doctor->id !== $appointment->doctor_id) {
            return back()->with('error', 'Bạn không có quyền cập nhật lịch hẹn này.');
        }

        // Kiểm tra trạng thái: chỉ cho phép hoàn thành khi lịch hẹn đã được duyệt
        // Không thể hoàn thành lịch hẹn đang chờ duyệt (pending) hoặc đã hủy (cancelled)
        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Chỉ có thể hoàn thành lịch hẹn đã được duyệt.');
        }

        // Cập nhật trạng thái lịch hẹn sang "completed" (Đã khám)
        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Đã đánh dấu lịch hẹn là hoàn thành.');
    }
}

