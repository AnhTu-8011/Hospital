<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
