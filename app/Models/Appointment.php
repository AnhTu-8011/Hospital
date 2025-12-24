<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'service_id',
        'appointment_date',
        'status',
        'payment_status',
        'payment_reference',
        'total_amount',
        'note',
        'medical_examination',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'canceled';

    // Payment status constants
    const PAYMENT_SUCCESS = 'success';

    const PAYMENT_FAILED = 'failed';

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
