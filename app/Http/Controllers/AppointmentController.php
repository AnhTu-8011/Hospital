<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    /**
     * Hiển thị danh sách lịch hẹn của bệnh nhân đang đăng nhập.
     * - Lấy thông tin bệnh nhân tương ứng với user hiện tại.
     * - Nếu user chưa có hồ sơ bệnh nhân → trả về trang chủ kèm thông báo lỗi.
     * - Nếu có, hiển thị danh sách các lịch hẹn (gồm thông tin bác sĩ, dịch vụ, ...).
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Kiểm tra user đã có hồ sơ bệnh nhân hay chưa
        if (!$user || !$user->patient) {
            return redirect()->route('home')
                ->with('error', 'Tài khoản của bạn chưa được liên kết với hồ sơ bệnh nhân.');
        }

        // Lấy danh sách lịch hẹn của bệnh nhân hiện tại
        $appointments = Appointment::where('patient_id', $user->patient->id)
            ->with(['doctor.user', 'service']) // Nạp thêm thông tin liên quan
            ->orderByDesc('appointment_date') // Mới nhất lên trước
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Hiển thị form tạo lịch hẹn mới.
     * - Lấy danh sách bác sĩ, dịch vụ, khoa.
     * - Trả về view form tạo lịch hẹn.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $doctors = Doctor::with('user')->get();
        $services = Service::all();
        $departments = Department::all();

        return view('appointments.create', compact('doctors', 'services', 'departments'));
    }

    /**
     * Lưu lịch hẹn mới vào cơ sở dữ liệu.
     * - Xác thực dữ liệu nhập vào.
     * - Kiểm tra trùng lịch hẹn với bác sĩ.
     * - Dùng transaction để đảm bảo toàn vẹn dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * Bước 1️⃣: Kiểm tra dữ liệu đầu vào
         * - Bắt buộc phải có các thông tin: bệnh nhân, bác sĩ, dịch vụ, ngày khám, ca khám.
         * - Ngày khám không được nhỏ hơn hôm nay.
         * - Ca khám chỉ có thể là "morning" hoặc "afternoon".
         */
        $validator = \Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|in:morning,afternoon',
            'note' => 'nullable|string|max:500',
        ]);

        // Nếu dữ liệu nhập không hợp lệ → quay lại form, hiển thị lỗi.
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /**
         * Bước 2️⃣: Sử dụng transaction để đảm bảo an toàn dữ liệu
         * - Nếu có lỗi trong quá trình tạo lịch hẹn, mọi thao tác sẽ bị rollback (hủy bỏ).
         */
        return DB::transaction(function () use ($request) {
            // Lấy thông tin các đối tượng liên quan
            $doctor = Doctor::with('user')->findOrFail($request->doctor_id);
            $service = Service::findOrFail($request->service_id);
            $patient = Patient::findOrFail($request->patient_id);

            /**
             * Bước 3️⃣: Xác định tên ca khám dựa vào giá trị appointment_time
             * - morning → Ca sáng (07:30 - 11:30)
             * - afternoon → Ca chiều (13:00 - 17:00).
             */
            $medicalExaminationMap = [
                'morning' => 'Ca sáng (07:30 - 11:30)',
                'afternoon' => 'Ca chiều (13:00 - 17:00)',
            ];
            $medicalExamination = $medicalExaminationMap[$request->appointment_time];

            // ✅ Chỉ lưu ngày khám (không cần giờ mặc định)
            $appointmentDate = Carbon::parse($request->appointment_date)->toDateString();

            /**
             * Bước 4️⃣: Kiểm tra giới hạn số ca mỗi buổi
             * - Mỗi bác sĩ trong một ngày chỉ nhận tối đa:
             *   + 25 ca sáng
             *   + 25 ca chiều
             * - Nếu đã đủ → báo lỗi, không cho đặt thêm.
             */
            $existingCount = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $request->appointment_date)
                ->where('medical_examination', $medicalExamination)
                ->whereIn('status', ['pending', 'confirmed']) // chỉ tính các lịch đang chờ hoặc đã xác nhận
                ->count();

            if ($existingCount >= 25) {
                return back()->with(
                    'error',
                    'Buổi '.($request->appointment_time === 'morning' ? 'sáng' : 'chiều').
                    ' ngày '.Carbon::parse($request->appointment_date)->format('d/m/Y').
                    ' của bác sĩ '.$doctor->user->name.' đã đủ 25 ca khám. '.
                    'Vui lòng chọn buổi khác hoặc ngày khác.'
                )->withInput();
            }

            /**
             * Bước 5️⃣: Tạo lịch hẹn mới
             * - Ghi vào bảng appointments
             * - Trạng thái mặc định: "pending" (chờ xác nhận).
             */
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'service_id' => $service->id,
                'appointment_date' => $appointmentDate,
                'status' => 'pending',
                'medical_examination' => $medicalExamination,
                'note' => $request->note,
            ]);

            /**
             * Bước 6️⃣: Trả về thông báo thành công
             * - Redirect về trang danh sách lịch hẹn.
             */
            return redirect()
                ->route('appointments.index')
                ->with('success', 'Đặt lịch thành công! Vui lòng chờ xác nhận.');
        });
    }

    /**
     * Hiển thị chi tiết một lịch hẹn.
     * - Bao gồm thông tin bệnh nhân, bác sĩ, dịch vụ.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])
            ->findOrFail($id);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Hiển thị form chỉnh sửa lịch hẹn.
     * - Dành cho bệnh nhân hoặc admin muốn thay đổi thông tin lịch hẹn.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctors = Doctor::with('user')->get();
        $services = Service::all();

        return view('appointments.edit', compact('appointment', 'doctors', 'services'));
    }

    /**
     * Cập nhật thông tin lịch hẹn.
     * - Kiểm tra dữ liệu hợp lệ.
     * - Cập nhật vào bảng `appointments`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Validate dữ liệu đầu vào
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'note' => 'nullable|string|max:500',
        ]);

        // Cập nhật thông tin lịch hẹn
        $appointment->update($request->only([
            'doctor_id', 'service_id', 'appointment_date', 'status', 'note',
        ]));

        return redirect()->route('appointments.index')->with('success', 'Cập nhật lịch hẹn thành công!');
    }

    /**
     * Hủy lịch hẹn.
     * - Cho phép bệnh nhân tự hủy khi lịch còn ở trạng thái chờ duyệt / đã duyệt (chưa khám).
     * - Không xóa bản ghi, chỉ cập nhật trạng thái để admin vẫn theo dõi được.
     * - Nếu đã thanh toán, coi như ghi nhận hoàn tiền (xử lý chi tiết ở lớp thanh toán hoặc kế toán).
     * - Gửi email thông báo cho bệnh nhân, và tùy chọn gửi cho admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $appointment = Appointment::with(['patient.user'])->findOrFail($id);
        $user = Auth::user();

        // Kiểm tra quyền sở hữu: chỉ bệnh nhân sở hữu hoặc tài khoản khác có quyền cao hơn (admin, v.v.)
        if ($user && $user->patient && $appointment->patient_id !== $user->patient->id) {
            return redirect()->route('appointments.show', $appointment->id)
                ->with('error', 'Bạn không có quyền hủy lịch hẹn này.');
        }

        // Chỉ cho phép hủy khi lịch hẹn đang chờ duyệt hoặc đã duyệt (chưa khám)
        if (!in_array($appointment->status, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])) {
            return redirect()->route('appointments.show', $appointment->id)
                ->with('error', 'Chỉ có thể hủy lịch hẹn đang chờ duyệt hoặc đã duyệt, chưa khám.');
        }

        // Không cho phép hủy nếu đã đến ngày khám hoặc sau đó
        $appointmentDate = Carbon::parse($appointment->appointment_date)->startOfDay();
        $today = now()->startOfDay();

        if ($appointmentDate->lessThanOrEqualTo($today)) {
            return redirect()->route('appointments.show', $appointment->id)
                ->with('error', 'Đã đến ngày khám, không thể hủy lịch hẹn. Vui lòng liên hệ trực tiếp bệnh viện để được hỗ trợ.');
        }

        // Kiểm tra trạng thái thanh toán
        $wasPaid = $appointment->payment_status === Appointment::PAYMENT_SUCCESS;

        // Cập nhật trạng thái lịch hẹn sang "cancelled"
        DB::transaction(function () use ($appointment) {
            $appointment->status = Appointment::STATUS_CANCELLED;
            $appointment->save();
        });

        // Lấy thông tin email và tên bệnh nhân
        $patientEmail = optional($appointment->patient)->email ?? optional(optional($appointment->patient)->user)->email;
        $patientName = optional($appointment->patient)->name ?? optional(optional($appointment->patient)->user)->name;

        // Tính lại số tiền sau giảm giống logic trên giao diện (show.blade.php)
        $basePrice = $appointment->total ?? ($appointment->service->price ?? 0);
        $birthdate = optional($appointment->patient)->birthdate;

        $discount = 0.8; // mặc định giảm 20%

        if ($birthdate && Carbon::parse($birthdate)->format('m') == now()->format('m')) {
            $discount = 0.7; // nếu sinh trong tháng hiện tại → giảm thêm 10%
        }

        $finalPrice = $basePrice * $discount;

        // Gửi email thông báo cho bệnh nhân
        if ($patientEmail) {
            $subject = 'Thông báo hủy lịch hẹn #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT);

            $bodyLines = [];
            $bodyLines[] = 'Xin chào '.($patientName ?: 'Quý khách').',';
            $bodyLines[] = '';
            $bodyLines[] = 'Lịch hẹn #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT).' của bạn tại bệnh viện đã được hủy thành công.';
            $bodyLines[] = 'Ngày khám: '.$appointment->appointment_date->format('d/m/Y').'.';

            if ($wasPaid && $finalPrice > 0) {
                $bodyLines[] = '';
                $bodyLines[] = 'Lịch hẹn đã được hủy và bạn đã được hoàn tiền với số tiền khoảng: '.number_format($finalPrice, 0, ',', '.').' đ.';
                $bodyLines[] = 'Thời gian tiền về tài khoản có thể mất vài ngày làm việc tùy ngân hàng/đơn vị thanh toán.';
            }

            $bodyLines[] = '';
            $bodyLines[] = 'Nếu bạn không thực hiện yêu cầu này, vui lòng liên hệ lại bệnh viện để được hỗ trợ.';

            $body = implode("\n", $bodyLines);

            Mail::raw($body, function ($message) use ($patientEmail, $subject, $patientName) {
                $message->to($patientEmail, $patientName ?: null)->subject($subject);
            });
        }

        // Gửi email thông báo cho admin
        $adminEmail = config('mail.admin_address') ?? null;

        if ($adminEmail) {
            $adminSubject = 'Lịch hẹn #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT).' đã bị bệnh nhân hủy';
            $adminBody = 'Lịch hẹn ID: '.$appointment->id."\n"
                .'Bệnh nhân: '.($patientName ?: 'N/A')."\n"
                .'Trạng thái mới: '.$appointment->status."\n"
                .'Thanh toán: '.$appointment->payment_status.($wasPaid ? ' (đã thanh toán, cần xử lý hoàn tiền nếu chưa xử lý).' : '')."\n";

            Mail::raw($adminBody, function ($message) use ($adminEmail, $adminSubject) {
                $message->to($adminEmail)->subject($adminSubject);
            });
        }

        // Thông báo thành công
        $flashMessage = $wasPaid
            ? 'Đã hủy lịch hẹn và đã hoàn tiền cho bạn (thời gian tiền về tài khoản có thể mất vài ngày tùy ngân hàng).'
            : 'Đã hủy lịch hẹn thành công.';

        return redirect()->route('appointments.index')->with('success', $flashMessage);
    }

    /**
     * Bác sĩ đánh dấu lịch hẹn đã hoàn thành.
     * - Chỉ bác sĩ của lịch hẹn và khi trạng thái là "confirmed" mới được phép cập nhật.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, Appointment $appointment)
    {
        $user = Auth::user();

        // Chỉ cho phép bác sĩ đúng của lịch hẹn
        if (!$user || !$user->hasRole('doctor') || !$user->doctor || $user->doctor->id !== $appointment->doctor_id) {
            return back()->with('error', 'Bạn không có quyền cập nhật lịch hẹn này.');
        }

        // Chỉ cho phép hoàn thành khi lịch hẹn đã được duyệt
        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Chỉ có thể hoàn thành lịch hẹn đã được duyệt.');
        }

        // Cập nhật trạng thái lịch hẹn sang "completed"
        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Đã đánh dấu lịch hẹn là hoàn thành.');
    }

    /**
     * Hiển thị hồ sơ bệnh án sau khi khám.
     * - Dành cho bệnh nhân xem lại chi tiết lịch khám và kết quả (medical_record).
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function viewRecord($id)
    {
        $appointment = \App\Models\Appointment::with(['doctor.user', 'service', 'patient', 'medicalRecord'])
            ->findOrFail($id);

        $record = $appointment->medicalRecord ?? null;
        $patient = $appointment->patient ?? null;

        // Truyền layout riêng dành cho hồ sơ bệnh nhân
        return view('appointments.medical_record', compact('appointment', 'record', 'patient'))
            ->with('layout', 'layouts.profile');
    }
}
