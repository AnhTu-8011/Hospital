<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PatientAuthController extends Controller
{
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'patient']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Dùng guard mặc định 'web' cho bệnh nhân
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            if (!$user->role || strtolower(trim($user->role->name)) !== 'patient') {
                Auth::guard('web')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải bệnh nhân.',
                ])->withInput();
            }

            return redirect()->route('home')
                ->with('success', 'Đăng nhập bệnh nhân thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('patient.login');
    }
}
