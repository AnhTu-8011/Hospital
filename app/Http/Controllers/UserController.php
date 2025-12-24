<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Khởi tạo controller.
     * Chỉ cho phép admin truy cập các hành động trong controller này.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Hiển thị danh sách người dùng, có thể lọc theo vai trò (role).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Lấy giá trị role trên URL (vd: ?role=doctor)
        $filter = $request->query('role');

        // Lấy danh sách người dùng, đồng thời nạp quan hệ 'role'
        $query = User::with('role');

        // Các vai trò hợp lệ để lọc
        $validRoles = ['patient', 'doctor', 'admin', 'leader'];

        // Nếu có tham số lọc role và hợp lệ → thêm điều kiện lọc
        if ($filter && in_array($filter, $validRoles, true)) {
            $query->whereHas('role', function ($q) use ($filter) {
                $q->where('name', $filter);
            });
        }

        // Lấy danh sách người dùng (mới nhất trước) và phân trang
        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Tính số lượng người dùng theo từng loại vai trò (dùng cho tab thống kê)
        $counts = [
            'all' => User::count(),
            'patient' => User::whereHas('role', fn($q) => $q->where('name', 'patient'))->count(),
            'doctor' => User::whereHas('role', fn($q) => $q->where('name', 'doctor'))->count(),
            'admin' => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->count(),
            'leader' => User::whereHas('role', fn($q) => $q->where('name', 'leader'))->count(),
        ];

        // Trả về view admin.users.index cùng với dữ liệu người dùng và thống kê
        return view('admin.users.index', [
            'users' => $users,
            'counts' => $counts,
            'currentRole' => $filter, // để biết tab nào đang được chọn
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng cụ thể.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Lấy danh sách tất cả vai trò để hiển thị trong dropdown chọn vai trò
        $roles = Role::orderBy('id')->get();

        // Trả về view chỉnh sửa, truyền user và danh sách roles
        return view('admin.users.edit', [
            'user' => $user->load('role'), // load quan hệ role của user
            'roles' => $roles,
        ]);
    }

    /**
     * Cập nhật thông tin người dùng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'], // tên bắt buộc
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // email duy nhất (trừ chính mình)
            'role_id' => ['required', Rule::exists('roles', 'id')], // role phải tồn tại trong bảng roles
            'phone' => ['nullable', 'string', 'max:50'], // số điện thoại tùy chọn
            'address' => ['nullable', 'string', 'max:255'], // địa chỉ tùy chọn
        ]);

        // Cập nhật dữ liệu vào user
        $user->update($validated);

        // Chuyển hướng về danh sách người dùng kèm thông báo
        return redirect()->route('admin.users.index')->with('status', 'Cập nhật tài khoản thành công.');
    }

    /**
     * Xóa người dùng khỏi hệ thống.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Ngăn admin tự xóa tài khoản của chính mình
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Không thể tự xóa tài khoản của chính bạn.');
        }

        // Xóa người dùng
        $user->delete();

        // Quay lại trang danh sách kèm thông báo
        return redirect()->route('admin.users.index')->with('status', 'Đã xóa tài khoản.');
    }
}
