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
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    // Hiển thị danh sách lịch hẹn của bệnh nhân đang đăng nhập
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
            ->orderByDesc('created_at') // Sắp xếp theo mới nhất (thời gian tạo)
            ->orderByDesc('id') // Nếu cùng thời gian thì sắp xếp theo ID
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    // Hiển thị form tạo lịch hẹn mới
    public function create()
    {
        $doctors = Doctor::with('user')->get();
        $services = Service::all();
        $departments = Department::all();

        return view('appointments.create', compact('doctors', 'services', 'departments'));
    }

    // Lưu lịch hẹn mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
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

        // Sử dụng transaction để đảm bảo an toàn dữ liệu
        return DB::transaction(function () use ($request) {
            // Lấy thông tin các đối tượng liên quan
            $doctor = Doctor::with('user')->findOrFail($request->doctor_id);
            $service = Service::findOrFail($request->service_id);
            $patient = Patient::findOrFail($request->patient_id);

            // Xác định tên ca khám dựa vào giá trị appointment_time
            $medicalExaminationMap = [
                'morning' => 'Ca sáng (07:30 - 11:30)',
                'afternoon' => 'Ca chiều (13:00 - 17:00)',
            ];
            $medicalExamination = $medicalExaminationMap[$request->appointment_time];

            // ✅ Chỉ lưu ngày khám (không cần giờ mặc định)
            $appointmentDate = Carbon::parse($request->appointment_date)->toDateString();

            // Kiểm tra thời gian đặt lịch - phải đặt trước ít nhất 5 giờ so với thời gian bắt đầu ca khám
            $appointmentDateTime = Carbon::parse($request->appointment_date);
            
            // Xác định thời gian bắt đầu ca khám
            if ($request->appointment_time === 'morning') {
                $appointmentDateTime->setTime(7, 30, 0); // Ca sáng: 07:30
            } else {
                $appointmentDateTime->setTime(13, 0, 0); // Ca chiều: 13:00
            }
            
            // Tính số giờ từ bây giờ đến thời điểm bắt đầu ca khám
            $hoursUntilAppointment = now()->diffInHours($appointmentDateTime, false);
            
            // Nếu thời gian bắt đầu ca khám đã qua hoặc còn < 5 giờ, không cho đặt
            if ($hoursUntilAppointment < 5) {
                $timeString = $request->appointment_time === 'morning' ? '07:30' : '13:00';
                $dateString = Carbon::parse($request->appointment_date)->format('d/m/Y');
                
                if ($hoursUntilAppointment < 0) {
                    return back()->with(
                        'error',
                        'Không thể đặt lịch cho ca khám đã qua. ' .
                        'Ca '.($request->appointment_time === 'morning' ? 'sáng' : 'chiều').' ngày '.$dateString.' ('.$timeString.') đã bắt đầu hoặc đã qua.'
                    )->withInput();
                } else {
                    $hoursRemaining = round($hoursUntilAppointment, 1);
                    return back()->with(
                        'error',
                        'Bạn phải đặt lịch trước ít nhất 5 giờ so với thời gian bắt đầu ca khám. ' .
                        'Ca '.($request->appointment_time === 'morning' ? 'sáng' : 'chiều').' ngày '.$dateString.' ('.$timeString.') chỉ còn '.$hoursRemaining.' giờ nữa. ' .
                        'Vui lòng chọn ca khám khác hoặc ngày khác.'
                    )->withInput();
                }
            }

            // Kiểm tra giới hạn số ca mỗi buổi (tối đa 25 ca sáng và 25 ca chiều mỗi bác sĩ)
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

            // Tạo lịch hẹn mới với trạng thái pending
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'service_id' => $service->id,
                'appointment_date' => $appointmentDate,
                'status' => 'pending',
                'medical_examination' => $medicalExamination,
                'note' => $request->note,
            ]);

            // Trả về thông báo thành công
            return redirect()
                ->route('appointments.index')
                ->with('success', 'Đặt lịch thành công! Vui lòng chờ xác nhận.');
        });
    }

    // Hiển thị chi tiết một lịch hẹn
    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])
            ->findOrFail($id);

        return view('appointments.show', compact('appointment'));
    }

    // Hủy lịch hẹn (chỉ cho phép trong vòng 5 giờ kể từ khi đặt hoặc thanh toán)
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

        // Kiểm tra trạng thái thanh toán
        $wasPaid = $appointment->payment_status === Appointment::PAYMENT_SUCCESS;

        // Nếu đã thanh toán, kiểm tra quy tắc 5 giờ: chỉ cho phép hủy trong vòng 5 giờ kể từ khi thanh toán
        if ($wasPaid && $appointment->paid_at) {
            $paidAt = Carbon::parse($appointment->paid_at);
            $hoursSincePayment = now()->diffInHours($paidAt, false);
            
            // Nếu đã qua 5 giờ kể từ khi thanh toán, không cho phép hủy
            if ($hoursSincePayment >= 5) {
                $hoursPassed = round($hoursSincePayment, 1);
                
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'Lịch hẹn đã thanh toán không thể hủy sau 5 giờ kể từ khi thanh toán. ' .
                        'Lịch hẹn này đã được thanh toán cách đây ' . $hoursPassed . ' giờ. ' .
                        'Vui lòng liên hệ trực tiếp bệnh viện để được hỗ trợ.');
            }
        } else {
            // Nếu chưa thanh toán, kiểm tra quy tắc 5 giờ: chỉ cho phép hủy trong vòng 5 giờ kể từ khi đặt lịch
            $createdAt = Carbon::parse($appointment->created_at);
            $hoursSinceCreation = now()->diffInHours($createdAt, false);
            
            // Nếu đã qua 5 giờ kể từ khi đặt lịch, không cho phép hủy
            if ($hoursSinceCreation >= 5) {
                $hoursPassed = round($hoursSinceCreation, 1);
                
                return redirect()->route('appointments.show', $appointment->id)
                    ->with('error', 'Bạn chỉ có thể hủy lịch hẹn trong vòng 5 giờ kể từ khi đặt lịch. ' .
                        'Lịch hẹn này đã được đặt cách đây ' . $hoursPassed . ' giờ. ' .
                        'Vui lòng liên hệ trực tiếp bệnh viện để được hỗ trợ.');
            }
        }

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

    // Hiển thị hồ sơ bệnh án sau khi khám
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
