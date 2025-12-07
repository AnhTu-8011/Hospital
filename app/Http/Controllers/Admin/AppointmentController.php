<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApprovedMail;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn kèm bộ lọc tìm kiếm.
     * 
     * 👉 Mục đích:
     * - Hiển thị danh sách tất cả lịch hẹn trong hệ thống (dành cho admin).
     * - Cho phép admin lọc dữ liệu theo nhiều tiêu chí khác nhau (tên bệnh nhân, bác sĩ, bảo hiểm, ngày hẹn, trạng thái).
     * - Kết quả được phân trang và hiển thị trên view `admin.appointments.index`.
     */
    public function index(Request $request)
    {
        // Khởi tạo query gốc với các quan hệ liên quan để tránh N+1 query:
        // - patient.user → thông tin người dùng của bệnh nhân
        // - doctor.user → thông tin người dùng của bác sĩ
        // - service → dịch vụ khám bệnh
        $query = Appointment::with(['patient.user', 'doctor.user', 'service']);

        // 🔍 Lọc theo tên bệnh nhân nếu có nhập từ form tìm kiếm
        if ($request->filled('patient_name')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        // 🔍 Lọc theo tên bác sĩ
        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        // 🔍 Lọc theo mã bảo hiểm của bệnh nhân
        if ($request->filled('insurance_number')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('insurance_number', 'like', '%' . $request->insurance_number . '%');
            });
        }

        // 🔍 Lọc theo ngày hẹn (so sánh theo ngày, không tính thời gian)
        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }

        // 🔍 Lọc theo trạng thái lịch hẹn (pending, confirmed, completed, canceled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sắp xếp kết quả theo ID giảm dần (lịch hẹn mới nhất trước) và phân trang
        $appointments = $query->orderBy('id', 'desc')
                              ->paginate(10)          // mỗi trang 10 lịch hẹn
                              ->withQueryString();    // giữ nguyên query khi chuyển trang

        // Trả dữ liệu sang view `admin.appointments.index`
        // Biến $appointments sẽ được dùng để hiển thị danh sách trong bảng.
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Hiển thị chi tiết lịch hẹn.
     * 
     * ⚠️ Tạm thời chỉ chuyển hướng về danh sách, 
     * tránh lỗi khi dùng Route::resource mà không cần xem chi tiết cụ thể.
     * 
     * → Khi triển khai thực tế, có thể thêm view hiển thị chi tiết lịch hẹn tại đây.
     */
    public function show(Appointment $appointment)
    {
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Đã chuyển về danh sách lịch hẹn.');
    }

    /**
     * Xóa lịch hẹn.
     * 
     * - Nhận vào ID lịch hẹn cần xóa.
     * - Tìm và xóa bản ghi trong bảng `appointments`.
     * - Sau khi xóa, chuyển hướng về danh sách kèm thông báo thành công.
     */
    public function destroy($id)
    {
        // Tìm lịch hẹn theo ID, nếu không có sẽ tự động báo lỗi 404
        $appointment = Appointment::findOrFail($id);

        // Thực hiện xóa lịch hẹn
        $appointment->delete();

        // Quay về danh sách với thông báo thành công
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Xóa lịch hẹn thành công!');
    }

    /**
     * Xác nhận lịch hẹn (thay đổi trạng thái thành "confirmed").
     * 
     * 👉 Dùng khi admin muốn xác nhận rằng lịch hẹn đã được phê duyệt.
     */
    public function confirm(Appointment $appointment)
    {
        // Cập nhật trạng thái của lịch hẹn
        $previous = $appointment->status;
        $appointment->update(['status' => 'confirmed']);

        if ($previous !== Appointment::STATUS_CONFIRMED) {
            $appointment->loadMissing(['patient', 'doctor.user', 'service']);
            $to = optional($appointment->patient)->email;
            if ($to) {
                Mail::to($to)->send(new AppointmentApprovedMail($appointment));
            }
        }

        // Quay lại trang trước (back) với thông báo thành công
        return back()->with('success', 'Đã xác nhận lịch hẹn thành công');
    }

    /**
     * Cập nhật trạng thái lịch hẹn (chỉ cho phép trong phạm vi admin).
     * 
     * 👉 Cho phép admin thay đổi trạng thái giữa các giá trị hợp lệ:
     *    - pending (đang chờ)
     *    - confirmed (đã xác nhận)
     *    - completed (đã hoàn thành)
     *    - canceled (đã hủy)
     * 
     * → Validate trước khi cập nhật để tránh giá trị không hợp lệ.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Kiểm tra dữ liệu gửi lên từ form (bắt buộc có trường status)
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $newStatus = $request->input('status');
        $previous = $appointment->status;

        // Cập nhật trạng thái lịch hẹn trong DB
        $appointment->update(['status' => $newStatus]);

        // Nếu chuyển sang trạng thái confirmed và trước đó chưa confirmed → gửi mail xác nhận
        if ($newStatus === Appointment::STATUS_CONFIRMED && $previous !== Appointment::STATUS_CONFIRMED) {
            $appointment->loadMissing(['patient', 'doctor.user', 'service']);
            $to = optional($appointment->patient)->email;
            if ($to) {
                Mail::to($to)->send(new AppointmentApprovedMail($appointment));
            }
        }

        // Nếu admin đổi trạng thái sang "canceled" (và trước đó chưa hủy) → gửi mail thông báo hủy
        if ($newStatus === Appointment::STATUS_CANCELLED && $previous !== Appointment::STATUS_CANCELLED) {
            $this->sendCancellationMail($appointment->fresh(['patient.user', 'service']));
        }

        // Trả thông báo và quay lại trang trước
        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    private function sendCancellationMail(Appointment $appointment)
    {
        $appointment->loadMissing(['patient.user', 'service']);

        $wasPaid = $appointment->payment_status === Appointment::PAYMENT_SUCCESS;

        // Lấy thông tin email bệnh nhân
        $patientEmail = optional($appointment->patient)->email ?? optional(optional($appointment->patient)->user)->email;
        $patientName  = optional($appointment->patient)->name ?? optional(optional($appointment->patient)->user)->name;

        // Tính số tiền sau giảm (giống logic hiển thị ở phía bệnh nhân)
        $basePrice = $appointment->total ?? ($appointment->service->price ?? 0);
        $birthdate = optional($appointment->patient)->birthdate;

        $discount = 0.8; // mặc định giảm 20%
        if ($birthdate && \Carbon\Carbon::parse($birthdate)->format('m') == now()->format('m')) {
            $discount = 0.7; // nếu sinh trong tháng hiện tại → giảm thêm 10%
        }

        $finalPrice = $basePrice * $discount;

        if ($patientEmail) {
            $subject = 'Thông báo hủy lịch hẹn từ bệnh viện #' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT);

            $bodyLines = [];
            $bodyLines[] = 'Xin chào ' . ($patientName ?: 'Quý khách') . ',';
            $bodyLines[] = '';
            $bodyLines[] = 'Lịch hẹn #' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT) . ' của bạn tại bệnh viện đã được hủy bởi bộ phận quản trị.';
            $bodyLines[] = 'Ngày khám dự kiến: ' . $appointment->appointment_date->format('d/m/Y') . '.';

            if ($wasPaid && $finalPrice > 0) {
                $bodyLines[] = '';
                $bodyLines[] = 'Lịch hẹn đã được hủy và bạn đã được hoàn tiền với số tiền khoảng: ' . number_format($finalPrice, 0, ',', '.') . ' đ.';
                $bodyLines[] = 'Thời gian tiền về tài khoản có thể mất vài ngày làm việc tùy ngân hàng/đơn vị thanh toán.';
            }

            $bodyLines[] = '';
            $bodyLines[] = 'Nếu bạn có thắc mắc, vui lòng liên hệ lại bệnh viện để được hỗ trợ thêm.';

            $body = implode("\n", $bodyLines);

            Mail::raw($body, function ($message) use ($patientEmail, $subject, $patientName) {
                $message->to($patientEmail, $patientName ?: null)->subject($subject);
            });
        }
    }
}
