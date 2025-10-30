<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Log;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse(Request $request)
    {
        $user = $request->user();
        Log::info('Register response for user:', [
            'user_id' => $user?->id,
            'role' => $user?->role?->name
        ]);

        if (!$user || !$user->role) {
            Log::warning('User or role not found after registration');
            return redirect()->intended('/');
        }

        switch ($user->role->name) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'doctor':
                return redirect()->intended('/doctor/dashboard');
            case 'leader':
                return redirect()->intended('/leader/dashboard');
            case 'patient':
                return redirect()->intended('/patient/dashboard');
            default:
                return redirect()->intended('/');
        }
    }
}
