<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Hiển thị trang yêu cầu gửi link đặt lại mật khẩu.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Xử lý yêu cầu gửi link đặt lại mật khẩu.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate email đầu vào
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 2. Gửi link reset mật khẩu
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Chuyển hướng và hiển thị thông báo dựa trên kết quả
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))                     // Thành công
            : back()->withInput($request->only('email'))             // Thất bại
                  ->withErrors(['email' => __($status)]);
    }
}
