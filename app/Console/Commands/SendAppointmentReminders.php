<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
        /**
     *
     * Để gửi email nhắc lịch trước 1 ngày, cách chuẩn nhất trong Laravel là:
     * Tạo Mailable cho nội dung email.
     * Tạo Console Command chạy mỗi ngày, quét các lịch hẹn ngày mai và gửi mail.
     * Đăng ký command trong Kernel để Laravel Scheduler gọi tự động.
     */

    protected $signature = 'appointments:send-reminders';

    protected $description = 'Gửi email nhắc lịch khám trước 1 ngày';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['patient', 'service', 'doctor.user'])
            ->whereDate('appointment_date', $tomorrow)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($appointments as $appointment) {
            $email = $appointment->patient->email ?? null;
            if (!$email) {
                continue;
            }

            Mail::to($email)->send(new AppointmentReminderMail($appointment));
        }

        $this->info('Đã gửi nhắc lịch cho ' . $appointments->count() . ' lịch hẹn ngày mai.');
        return 0;
    }
}