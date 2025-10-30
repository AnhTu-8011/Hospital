<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Patient;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Hiển thị trang đăng ký tài khoản.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý yêu cầu đăng ký tài khoản mới.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Lấy ID vai trò mặc định cho bệnh nhân
        $patientRoleId = Role::where('name', 'patient')->value('id') ?? 1; // fallback id=1 nếu có

        // 3. Tạo người dùng mới
        $user = User::create([
            'role_id' => $patientRoleId,
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 4. Tạo hồ sơ bệnh nhân (patient) mặc định
        $user->patient()->create([
            'birthdate' => $request->birthdate,
        ]);

        // 5. Gửi sự kiện đã đăng ký
        event(new Registered($user));

        // 6. Đăng nhập người dùng mới
        Auth::login($user);

        // 7. Chuyển hướng đến trang hồ sơ
        return redirect()->route('profile.edit');
    }
}
