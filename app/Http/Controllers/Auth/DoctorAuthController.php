<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DoctorAuthController extends Controller
{
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'doctor']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web_doctor')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web_doctor')->user();

            if (!$user->role || strtolower(trim($user->role->name)) !== 'doctor') {
                Auth::guard('web_doctor')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải bác sĩ.',
                ])->withInput();
            }

            if (!$user->doctor) {
                Auth::guard('web_doctor')->logout();

                return back()->withErrors([
                    'email' => 'Không tìm thấy thông tin bác sĩ. Vui lòng liên hệ quản trị viên.',
                ])->withInput();
            }

            return redirect()->intended(route('doctor.dashboard'))
                ->with('success', 'Đăng nhập bác sĩ thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web_doctor')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('doctor.login');
    }
}
