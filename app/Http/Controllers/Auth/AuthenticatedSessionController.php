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
     * 
     * Phương thức này chỉ đơn giản là trả về view "auth.login"
     * chứa form đăng nhập cho người dùng nhập email/mật khẩu.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập.
     * 
     * Đây là nơi xử lý logic đăng nhập người dùng:
     * - Xác thực thông tin người dùng.
     * - Kiểm tra quyền/role.
     * - Điều hướng đến trang phù hợp sau khi đăng nhập.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Gọi phương thức authenticate() từ LoginRequest để xác thực người dùng.
            // Nếu thông tin không hợp lệ, hàm này sẽ tự động ném ra ValidationException.
            $request->authenticate();

            // Sau khi đăng nhập thành công, regenerate session ID để tránh session fixation attack.
            $request->session()->regenerate();
            
            // Lấy thông tin người dùng hiện tại từ Auth
            $user = Auth::user();
    
            // Kiểm tra xem người dùng có role (vai trò) hay chưa
            if (!$user->role) {
                // Nếu chưa có, đăng xuất ngay và báo lỗi
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản chưa được gán vai trò. Vui lòng liên hệ quản trị viên.',
                ])->withInput();
            }
    
            // Lấy tên vai trò (role name) và chuyển về chữ thường để so sánh dễ hơn
            $roleName = strtolower(trim($user->role->name));
    
            // Nếu là bác sĩ (doctor) thì kiểm tra xem có bản ghi bác sĩ liên kết không
            if ($roleName === 'doctor' && !$user->doctor) {
                // Nếu không có thông tin bác sĩ tương ứng, đăng xuất và báo lỗi
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
                    // Nếu là bệnh nhân (patient), chuyển đến trang home
                    return redirect()->intended(route('home'))
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
        // Đăng xuất người dùng khỏi guard 'web'
        Auth::guard('web')->logout();

        // Hủy session hiện tại để tránh tái sử dụng
        $request->session()->invalidate();

        // Tạo lại CSRF token mới
        $request->session()->regenerateToken();

        // Chuyển hướng về trang chủ
        return redirect('/');
    }
}
