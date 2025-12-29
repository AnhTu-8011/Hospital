<?php

namespace App\Console\Commands;

use App\Mail\AppointmentApprovedMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoApprovePaidAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-approve-paid-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động duyệt các lịch hẹn đã thanh toán trong vòng 24 giờ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra các lịch hẹn đã thanh toán trong vòng 24 giờ...');

        // Tìm các lịch hẹn đã thanh toán trong vòng 24 giờ nhưng chưa được duyệt
        $cutoffTime = now()->subHours(24);
        
        $appointments = Appointment::query()
            ->where('created_at', '>=', $cutoffTime)
            ->where('payment_status', Appointment::PAYMENT_SUCCESS)
            ->where('status', Appointment::STATUS_PENDING)
            ->with(['patient.user', 'doctor.user', 'service'])
            ->get();

        $this->info("Tìm thấy {$appointments->count()} lịch hẹn cần tự động duyệt.");

        $approvedCount = 0;
        $errorCount = 0;

        foreach ($appointments as $appointment) {
            try {
                DB::transaction(function () use ($appointment) {
                    $appointment->status = Appointment::STATUS_CONFIRMED;
                    $appointment->save();

                    Log::info('[AutoApprovePaidAppointments] Đã tự động duyệt lịch hẹn', [
                        'appointment_id' => $appointment->id,
                        'patient_id' => $appointment->patient_id,
                        'created_at' => $appointment->created_at,
                        'hours_since_creation' => now()->diffInHours($appointment->created_at),
                    ]);
                });

                // Gửi email xác nhận cho bệnh nhân (nếu có email)
                $patientEmail = optional($appointment->patient)->email 
                    ?? optional(optional($appointment->patient)->user)->email;

                if ($patientEmail) {
                    try {
                        $appointment->loadMissing(['patient', 'doctor.user', 'service']);
                        Mail::to($patientEmail)->send(new AppointmentApprovedMail($appointment));

                        Log::info('[AutoApprovePaidAppointments] Đã gửi email xác nhận', [
                            'appointment_id' => $appointment->id,
                            'email' => $patientEmail,
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('[AutoApprovePaidAppointments] Lỗi khi gửi email', [
                            'appointment_id' => $appointment->id,
                            'email' => $patientEmail,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $approvedCount++;
                $this->line("✓ Đã tự động duyệt lịch hẹn #{$appointment->id}");
            } catch (\Throwable $e) {
                $errorCount++;
                Log::error('[AutoApprovePaidAppointments] Lỗi khi duyệt lịch hẹn', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("✗ Lỗi khi duyệt lịch hẹn #{$appointment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Hoàn thành! Đã tự động duyệt {$approvedCount} lịch hẹn, {$errorCount} lỗi.");
        
        return Command::SUCCESS;
    }
}

