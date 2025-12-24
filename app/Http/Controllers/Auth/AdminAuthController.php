<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập cho quản trị viên.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', ['role' => 'admin']);
    }

    /**
     * Xử lý đăng nhập cho quản trị viên.
     * - Sử dụng guard 'web_admin' để xác thực.
     * - Kiểm tra role phải là 'admin'.
     * - Tự động chuyển hướng đến dashboard admin sau khi đăng nhập thành công.
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

        // Thử đăng nhập với guard web_admin
        if (Auth::guard('web_admin')->attempt($credentials, $request->boolean('remember'))) {
            // Tạo lại session để bảo mật
            $request->session()->regenerate();

            $user = Auth::guard('web_admin')->user();

            // Kiểm tra role phải là admin
            if (!$user->role || strtolower(trim($user->role->name)) !== 'admin') {
                Auth::guard('web_admin')->logout();

                return back()->withErrors([
                    'email' => 'Tài khoản này không phải quản trị viên.',
                ])->withInput();
            }

            // Chuyển hướng đến dashboard admin
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập quản trị viên thành công!');
        }

        // Trả về lỗi nếu đăng nhập thất bại
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput();
    }

    /**
     * Xử lý đăng xuất cho quản trị viên.
     * - Đăng xuất khỏi guard 'web_admin'.
     * - Hủy session và regenerate token để bảo mật.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Đăng xuất khỏi guard web_admin
        Auth::guard('web_admin')->logout();

        // Hủy session và regenerate token để bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng về trang đăng nhập admin
        return redirect()->route('admin.login');
    }
}
