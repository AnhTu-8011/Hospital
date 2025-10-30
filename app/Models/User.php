<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Patient;
use App\Models\Role;
use App\Models\Doctor;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'birthdate',
        'gender',
        'address',
        'insurance_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // ❌ bỏ hashed nếu bạn dùng bcrypt() trong controller
        'birthdate' => 'date',
    ];

    // Quan hệ với bệnh nhân
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Quan hệ với vai trò (Role)
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Quan hệ với hồ sơ bác sĩ
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }
}
