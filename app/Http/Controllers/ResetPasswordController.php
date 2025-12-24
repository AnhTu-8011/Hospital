<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Hiển thị form reset mật khẩu với token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Xử lý reset mật khẩu từ form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Reset mật khẩu và mã hóa
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        // Trả về thông báo thành công hoặc lỗi
        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
