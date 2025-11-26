<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     *
     * Để gửi email nhắc lịch trước 1 ngày, cách chuẩn nhất trong Laravel là:
     * Tạo Mailable cho nội dung email.
     * Tạo Console Command chạy mỗi ngày, quét các lịch hẹn ngày mai và gửi mail.
     * Đăng ký command trong Kernel để Laravel Scheduler gọi tự động.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Gửi nhắc lịch mỗi ngày lúc 07:00
        $schedule->command('app:send-appointment-reminder')->dailyAt('12:43');
    }

}
