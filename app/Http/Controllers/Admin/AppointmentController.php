<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentApprovedMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn với bộ lọc tìm kiếm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'service']);

        // Áp dụng các bộ lọc tìm kiếm
        $this->applyFilters($query, $request);

        // Lấy danh sách lịch hẹn với phân trang
        $appointments = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Hiển thị chi tiết lịch hẹn (tạm thời chuyển về danh sách).
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Appointment $appointment)
    {
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Đã chuyển về danh sách lịch hẹn.');
    }

    /**
     * Xóa lịch hẹn.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Xóa lịch hẹn thành công!');
    }

    /**
     * Xác nhận lịch hẹn (thay đổi trạng thái thành "confirmed").
     * - Gửi email xác nhận cho bệnh nhân nếu trạng thái thay đổi.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Appointment $appointment)
    {
        $previousStatus = $appointment->status;
        $appointment->update(['status' => Appointment::STATUS_CONFIRMED]);

        // Gửi email xác nhận nếu trạng thái thay đổi
        if ($previousStatus !== Appointment::STATUS_CONFIRMED) {
            $this->sendApprovalEmail($appointment);
        }

        return back()->with('success', 'Đã xác nhận lịch hẹn thành công');
    }

    /**
     * Cập nhật trạng thái lịch hẹn.
     * - Gửi email xác nhận nếu chuyển sang "confirmed".
     * - Gửi email hủy nếu chuyển sang "cancelled".
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $newStatus = $request->input('status');
        $previousStatus = $appointment->status;

        // Cập nhật trạng thái
        $appointment->update(['status' => $newStatus]);

        // Gửi email xác nhận nếu chuyển sang "confirmed"
        if ($newStatus === Appointment::STATUS_CONFIRMED && $previousStatus !== Appointment::STATUS_CONFIRMED) {
            $this->sendApprovalEmail($appointment);
        }

        // Gửi email hủy nếu chuyển sang "cancelled"
        if ($newStatus === Appointment::STATUS_CANCELLED && $previousStatus !== Appointment::STATUS_CANCELLED) {
            $this->sendCancellationMail($appointment->fresh(['patient.user', 'service']));
        }

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    /**
     * Áp dụng các bộ lọc tìm kiếm vào query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        // Lọc theo tên bệnh nhân
        if ($request->filled('patient_name')) {
            $query->whereHas('patient.user', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%'.$request->patient_name.'%');
            });
        }

        // Lọc theo tên bác sĩ
        if ($request->filled('doctor_name')) {
            $query->whereHas('doctor.user', function ($subQuery) use ($request) {
                $subQuery->where('name', 'like', '%'.$request->doctor_name.'%');
            });
        }

        // Lọc theo số bảo hiểm
        if ($request->filled('insurance_number')) {
            $query->whereHas('patient.user', function ($subQuery) use ($request) {
                $subQuery->where('insurance_number', 'like', '%'.$request->insurance_number.'%');
            });
        }

        // Lọc theo ngày khám
        if ($request->filled('appointment_date')) {
            $query->whereDate('appointment_date', $request->appointment_date);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    }

    /**
     * Gửi email xác nhận lịch hẹn.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    private function sendApprovalEmail(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient', 'doctor.user', 'service']);
        $patientEmail = optional($appointment->patient)->email;

        // Gửi email nếu có địa chỉ email
        if ($patientEmail) {
            Mail::to($patientEmail)->send(new AppointmentApprovedMail($appointment));
        }
    }

    /**
     * Gửi email thông báo hủy lịch hẹn.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     */
    private function sendCancellationMail(Appointment $appointment): void
    {
        $appointment->loadMissing(['patient.user', 'service']);

        $patientEmail = $this->getPatientEmail($appointment);
        $patientName = $this->getPatientName($appointment);

        // Không gửi email nếu không có địa chỉ email
        if (!$patientEmail) {
            return;
        }

        // Tính toán thông tin thanh toán
        $wasPaid = $appointment->payment_status === Appointment::PAYMENT_SUCCESS;
        $finalPrice = $this->calculateFinalPrice($appointment);

        // Chuẩn bị nội dung email
        $subject = 'Thông báo hủy lịch hẹn từ bệnh viện #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
        $body = $this->buildCancellationEmailBody($appointment, $patientName, $wasPaid, $finalPrice);

        // Gửi email
        Mail::raw($body, function ($message) use ($patientEmail, $subject, $patientName) {
            $message->to($patientEmail, $patientName ?: null)->subject($subject);
        });
    }

    /**
     * Lấy email của bệnh nhân.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return string|null
     */
    private function getPatientEmail(Appointment $appointment): ?string
    {
        return optional($appointment->patient)->email
            ?? optional(optional($appointment->patient)->user)->email;
    }

    /**
     * Lấy tên của bệnh nhân.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return string|null
     */
    private function getPatientName(Appointment $appointment): ?string
    {
        return optional($appointment->patient)->name
            ?? optional(optional($appointment->patient)->user)->name;
    }

    /**
     * Tính giá cuối cùng sau khi áp dụng giảm giá.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return float
     */
    private function calculateFinalPrice(Appointment $appointment): float
    {
        $basePrice = $appointment->total ?? ($appointment->service->price ?? 0);
        $discount = $this->calculateDiscount($appointment);

        return $basePrice * $discount;
    }

    /**
     * Tính tỷ lệ giảm giá dựa trên tháng sinh của bệnh nhân.
     * - Giảm 30% nếu sinh trong tháng hiện tại.
     * - Giảm 20% mặc định.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return float
     */
    private function calculateDiscount(Appointment $appointment): float
    {
        $birthdate = optional($appointment->patient)->birthdate;

        // Giảm 30% nếu sinh trong tháng hiện tại
        if ($birthdate && Carbon::parse($birthdate)->format('m') === now()->format('m')) {
            return 0.7;
        }

        // Giảm 20% mặc định
        return 0.8;
    }

    /**
     * Xây dựng nội dung email thông báo hủy lịch hẹn.
     *
     * @param  \App\Models\Appointment  $appointment
     * @param  string|null  $patientName
     * @param  bool  $wasPaid
     * @param  float  $finalPrice
     * @return string
     */
    private function buildCancellationEmailBody(
        Appointment $appointment,
        ?string $patientName,
        bool $wasPaid,
        float $finalPrice
    ): string {
        $appointmentId = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
        $appointmentDate = $appointment->appointment_date->format('d/m/Y');

        $lines = [];
        $lines[] = 'Xin chào '.($patientName ?: 'Quý khách').',';
        $lines[] = '';
        $lines[] = "Lịch hẹn #{$appointmentId} của bạn tại bệnh viện đã được hủy bởi bộ phận quản trị.";
        $lines[] = "Ngày khám dự kiến: {$appointmentDate}.";

        // Thêm thông tin hoàn tiền nếu đã thanh toán
        if ($wasPaid && $finalPrice > 0) {
            $lines[] = '';
            $formattedPrice = number_format($finalPrice, 0, ',', '.');
            $lines[] = "Lịch hẹn đã được hủy và bạn đã được hoàn tiền với số tiền khoảng: {$formattedPrice} đ.";
            $lines[] = 'Thời gian tiền về tài khoản có thể mất vài ngày làm việc tùy ngân hàng/đơn vị thanh toán.';
        }

        $lines[] = '';
        $lines[] = 'Nếu bạn có thắc mắc, vui lòng liên hệ lại bệnh viện để được hỗ trợ thêm.';

        return implode("\n", $lines);
    }
}
