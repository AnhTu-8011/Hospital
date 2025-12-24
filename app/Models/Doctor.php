<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'specialization',
        'license_number',
        'avatar',
        'description',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Quan hệ 1 bác sĩ thuộc về 1 user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ 1 bác sĩ thuộc về 1 khoa phòng.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Quan hệ 1 bác sĩ có nhiều lịch hẹn.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
