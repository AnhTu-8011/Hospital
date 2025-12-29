<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorAppointmentController extends Controller
{
    /**
     * Bác sĩ đánh dấu lịch hẹn đã hoàn thành.
     * 
     * CHỨC NĂNG DÀNH CHO: BÁC SĨ
     * 
     * Mục đích:
     * - Sau khi khám xong cho bệnh nhân, bác sĩ sử dụng chức năng này để đánh dấu lịch hẹn đã hoàn thành
     * - Cập nhật trạng thái lịch hẹn từ "confirmed" (Đã duyệt) → "completed" (Đã khám)
     * 
     * Điều kiện:
     * 1. Người dùng phải là bác sĩ (role = 'doctor')
     * 2. Bác sĩ phải là bác sĩ được phân công cho lịch hẹn này (doctor_id khớp)
     * 3. Lịch hẹn phải ở trạng thái "confirmed" (Đã duyệt) - không thể hoàn thành lịch chưa duyệt
     * 
     * Route: POST /doctor/appointments/{appointment}/complete
     * Middleware: auth:web_doctor (chỉ bác sĩ mới truy cập được)
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment  Lịch hẹn cần đánh dấu hoàn thành
     * @return \Illuminate\Http\RedirectResponse  Redirect về trang trước với thông báo thành công/lỗi
     */
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

