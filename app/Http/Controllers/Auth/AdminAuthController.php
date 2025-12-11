<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'admin']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Thử đăng nhập với guard web_admin
        if (Auth::guard('web_admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web_admin')->user();
            if (!$user->role || strtolower(trim($user->role->name)) !== 'admin') {
                Auth::guard('web_admin')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải quản trị viên.',
                ])->withInput();
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập quản trị viên thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web_admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
