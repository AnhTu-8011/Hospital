<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị trang đăng nhập.
     * Phương thức này chỉ đơn giản là trả về view "auth.login"
     * chứa form đăng nhập cho người dùng nhập email/mật khẩu.
     */
    public function create(Request $request): View
    {
        $role = $request->query('role');

        return view('auth.login', compact('role'));
    }
    /**
     * Xử lý yêu cầu đăng nhập.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->role) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản chưa được gán vai trò. Vui lòng liên hệ quản trị viên.',
                ])->withInput();
            }

            $roleName = strtolower(trim($user->role->name));

            // Kiểm tra trường hợp đăng nhập bằng link role cụ thể (admin / doctor / patient)
            $requestedRole = strtolower((string) $request->input('requested_role'));
            if ($requestedRole && $requestedRole !== $roleName) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Bạn đang dùng link đăng nhập ' . $requestedRole . ' nhưng tài khoản là ' . $roleName . '.',
                ])->withInput();
            }

            if ($roleName === 'doctor' && !$user->doctor) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Không tìm thấy thông tin bác sĩ. Vui lòng liên hệ quản trị viên.',
                ])->withInput();
            }

            // Dựa vào vai trò (role), điều hướng người dùng đến dashboard tương ứng
            switch ($roleName) {
                case 'admin':
                    // Nếu là admin, chuyển đến trang admin.dashboard
                    return redirect()->intended(route('admin.dashboard'))
                        ->with('success', 'Đăng nhập quản trị viên thành công!');

                case 'doctor':
                    // Nếu là doctor, chuyển đến trang doctor.dashboard
                    return redirect()->intended(route('doctor.dashboard'))
                        ->with('success', 'Đăng nhập bác sĩ thành công!');

                case 'patient':
                    // Nếu là bệnh nhân (patient), chuyển đến trang patient.dashboard
                    return redirect()->intended(route('patient.dashboard'))
                        ->with('success', 'Đăng nhập thành công!');

                default:
                    // Nếu vai trò không nằm trong danh sách trên, đăng xuất và báo lỗi
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Vai trò người dùng không hợp lệ.',
                    ])->withInput();
            }

        } catch (\Exception $e) {
            // Nếu có lỗi bất ngờ trong quá trình xử lý (vd: lỗi DB, logic, ...)
            // thì đăng xuất và hiển thị thông báo lỗi chi tiết.
            Auth::logout();
            return back()->withErrors([
                'email' => 'Đăng nhập thất bại: ' . $e->getMessage(),
            ])->withInput();
        }
    }

    /**
     * Đăng xuất người dùng và hủy session.
     * 
     * - Gọi Auth::logout() để đăng xuất người dùng.
     * - Invalidate session để xóa toàn bộ dữ liệu session hiện tại.
     * - Regenerate CSRF token để bảo mật cho lần đăng nhập tiếp theo.
     * - Cuối cùng, điều hướng về trang chủ "/".
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();
        $roleName = $user && $user->role ? strtolower(trim($user->role->name)) : null;

        // Đăng xuất người dùng khỏi guard 'web'
        Auth::guard('web')->logout();

        // Hủy session hiện tại để tránh tái sử dụng
        $request->session()->invalidate();

        // Tạo lại CSRF token mới
        $request->session()->regenerateToken();

        if ($roleName === 'patient') {
            return redirect()->route('patient.login');
        }

        return redirect('/');
    }
}
