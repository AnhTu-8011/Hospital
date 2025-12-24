<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DoctorAuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập cho bác sĩ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'doctor']);
    }

    /**
     * Xử lý đăng nhập cho bác sĩ.
     * - Sử dụng guard 'web_doctor' để xác thực.
     * - Kiểm tra role phải là 'doctor'.
     * - Kiểm tra user phải có hồ sơ bác sĩ (doctor profile).
     * - Tự động chuyển hướng đến dashboard bác sĩ sau khi đăng nhập thành công.
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

        // Thử đăng nhập với guard web_doctor
        if (Auth::guard('web_doctor')->attempt($credentials, $request->boolean('remember'))) {
            // Tạo lại session để bảo mật
            $request->session()->regenerate();

            $user = Auth::guard('web_doctor')->user();

            // Kiểm tra role phải là doctor
            if (!$user->role || strtolower(trim($user->role->name)) !== 'doctor') {
                Auth::guard('web_doctor')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải bác sĩ.',
                ])->withInput();
            }

            // Kiểm tra user phải có hồ sơ bác sĩ
            if (!$user->doctor) {
                Auth::guard('web_doctor')->logout();

                return back()->withErrors([
                    'email' => 'Không tìm thấy thông tin bác sĩ. Vui lòng liên hệ quản trị viên.',
                ])->withInput();
            }

            // Chuyển hướng đến dashboard bác sĩ
            return redirect()->intended(route('doctor.dashboard'))
                ->with('success', 'Đăng nhập bác sĩ thành công!');
        }

        // Trả về lỗi nếu đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    /**
     * Xử lý đăng xuất cho bác sĩ.
     * - Đăng xuất khỏi guard 'web_doctor'.
     * - Hủy session và regenerate token để bảo mật.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Đăng xuất khỏi guard web_doctor
        Auth::guard('web_doctor')->logout();

        // Hủy session và regenerate token để bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng về trang đăng nhập bác sĩ
        return redirect()->route('doctor.login');
    }
}
