<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientAuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập cho bệnh nhân.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'patient']);
    }

    /**
     * Xử lý đăng nhập cho bệnh nhân.
     * - Sử dụng guard mặc định 'web' để xác thực.
     * - Kiểm tra role phải là 'patient'.
     * - Tự động chuyển hướng đến trang chủ sau khi đăng nhập thành công.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate dữ liệu đầu vào
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Dùng guard mặc định 'web' cho bệnh nhân
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            // Tạo lại session để bảo mật
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            // Kiểm tra role phải là patient
            if (!$user->role || strtolower(trim($user->role->name)) !== 'patient') {
                Auth::guard('web')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải bệnh nhân.',
                ])->withInput();
            }

            // Chuyển hướng đến trang chủ
            return redirect()->route('home')
                ->with('success', 'Đăng nhập bệnh nhân thành công!');
        }

        // Trả về lỗi nếu đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    /**
     * Xử lý đăng xuất cho bệnh nhân.
     * - Đăng xuất khỏi guard mặc định 'web'.
     * - Hủy session và regenerate token để bảo mật.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Đăng xuất khỏi guard web
        Auth::guard('web')->logout();

        // Hủy session và regenerate token để bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng về trang đăng nhập bệnh nhân
        return redirect()->route('patient.login');
    }
}
