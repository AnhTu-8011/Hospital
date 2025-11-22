<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
        /**
     *
     * Để gửi email nhắc lịch trước 1 ngày, cách chuẩn nhất trong Laravel là:
     * Tạo Mailable cho nội dung email.
     * Tạo Console Command chạy mỗi ngày, quét các lịch hẹn ngày mai và gửi mail.
     * Đăng ký command trong Kernel để Laravel Scheduler gọi tự động.
     */
    use Queueable, SerializesModels;

    public $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('Nhắc lịch khám tại Bệnh viện Phúc An')
                    ->view('emails.appointment_reminder');
    }
}