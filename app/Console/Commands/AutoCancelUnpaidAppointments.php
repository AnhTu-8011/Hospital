<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoCancelUnpaidAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-cancel-unpaid-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy các lịch hẹn đã được tạo hơn 24 giờ nhưng chưa thanh toán';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các lịch hẹn chưa thanh toán sau 24 giờ...');

        // Tìm các lịch hẹn đã được tạo hơn 24 giờ nhưng chưa thanh toán
        $cutoffTime = now()->subHours(24);
        
        $appointments = Appointment::query()
            ->where('created_at', '<', $cutoffTime)
            ->where('payment_status', '!=', Appointment::PAYMENT_SUCCESS)
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->with(['patient.user', 'doctor.user', 'service'])
            ->get();

        $this->info("Tìm thấy {$appointments->count()} lịch hẹn cần hủy.");

        $cancelledCount = 0;
        $errorCount = 0;

        foreach ($appointments as $appointment) {
            try {
                DB::transaction(function () use ($appointment) {
                    $appointment->status = Appointment::STATUS_CANCELLED;
                    $appointment->save();

                    Log::info('[AutoCancelUnpaidAppointments] Đã tự động hủy lịch hẹn', [
                        'appointment_id' => $appointment->id,
                        'patient_id' => $appointment->patient_id,
                        'created_at' => $appointment->created_at,
                        'hours_since_creation' => now()->diffInHours($appointment->created_at),
                    ]);
                });

                // Gửi email thông báo cho bệnh nhân (nếu có email)
                $patientEmail = optional($appointment->patient)->email 
                    ?? optional(optional($appointment->patient)->user)->email;
                $patientName = optional($appointment->patient)->name 
                    ?? optional(optional($appointment->patient)->user)->name;

                if ($patientEmail) {
                    try {
                        $subject = 'Thông báo hủy lịch hẹn #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT);
                        
                        $bodyLines = [];
                        $bodyLines[] = 'Xin chào '.($patientName ?: 'Quý khách').',';
                        $bodyLines[] = '';
                        $bodyLines[] = 'Lịch hẹn #'.str_pad($appointment->id, 6, '0', STR_PAD_LEFT).' của bạn tại bệnh viện đã bị tự động hủy do chưa thanh toán trong vòng 24 giờ kể từ khi đặt lịch.';
                        $bodyLines[] = 'Ngày khám dự kiến: '.Carbon::parse($appointment->appointment_date)->format('d/m/Y').'.';
                        $bodyLines[] = '';
                        $bodyLines[] = 'Nếu bạn vẫn muốn khám, vui lòng đặt lịch hẹn mới trên hệ thống.';
                        $bodyLines[] = '';
                        $bodyLines[] = 'Trân trọng,';
                        $bodyLines[] = 'Bệnh viện Phúc An';

                        $body = implode("\n", $bodyLines);

                        Mail::raw($body, function ($message) use ($patientEmail, $subject, $patientName) {
                            $message->to($patientEmail, $patientName ?: null)->subject($subject);
                        });

                        Log::info('[AutoCancelUnpaidAppointments] Đã gửi email thông báo hủy lịch', [
                            'appointment_id' => $appointment->id,
                            'email' => $patientEmail,
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('[AutoCancelUnpaidAppointments] Lỗi khi gửi email', [
                            'appointment_id' => $appointment->id,
                            'email' => $patientEmail,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $cancelledCount++;
                $this->line("✓ Đã hủy lịch hẹn #{$appointment->id}");
            } catch (\Throwable $e) {
                $errorCount++;
                Log::error('[AutoCancelUnpaidAppointments] Lỗi khi hủy lịch hẹn', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("✗ Lỗi khi hủy lịch hẹn #{$appointment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Hoàn thành! Đã hủy {$cancelledCount} lịch hẹn, {$errorCount} lỗi.");
        
        return Command::SUCCESS;
    }
}

