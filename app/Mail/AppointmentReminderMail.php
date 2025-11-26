<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment->loadMissing(['patient', 'doctor.user', 'service']);
    }

    public function build()
    {
        return $this->subject('Nhắc nhở lịch hẹn - Ngày mai')
                    ->view('emails.appointment_reminder');
    }
}
