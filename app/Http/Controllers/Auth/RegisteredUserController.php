<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
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
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Xử lý yêu cầu đăng ký tài khoản mới.
     * - Luồng xử lý:
     *   1️⃣ Validate dữ liệu đầu vào.
     *   2️⃣ Lấy ID vai trò mặc định cho bệnh nhân.
     *   3️⃣ Tạo người dùng mới.
     *   4️⃣ Tạo hồ sơ bệnh nhân (patient) mặc định.
     *   5️⃣ Gửi sự kiện đã đăng ký.
     *   6️⃣ Đăng nhập người dùng mới.
     *   7️⃣ Chuyển hướng đến trang hồ sơ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Bước 1: Validate dữ liệu đầu vào
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Bước 2: Lấy ID vai trò mặc định cho bệnh nhân
        $patientRoleId = Role::where('name', 'patient')->value('id') ?? 1; // fallback id=1 nếu có

        // Bước 3: Tạo người dùng mới
        $user = User::create([
            'role_id' => $patientRoleId,
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Bước 4: Tạo hồ sơ bệnh nhân (patient) mặc định
        $user->patient()->create([
            'birthdate' => $request->birthdate,
        ]);

        // Bước 5: Gửi sự kiện đã đăng ký (để gửi email xác minh nếu cần)
        event(new Registered($user));

        // Bước 6: Đăng nhập người dùng mới tự động
        Auth::login($user);

        // Bước 7: Chuyển hướng đến trang hồ sơ để hoàn thiện thông tin
        return redirect()->route('profile.edit');
    }
}
