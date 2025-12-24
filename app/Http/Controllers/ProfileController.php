<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang chính của hồ sơ cá nhân.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Hiển thị trang chỉnh sửa hồ sơ người dùng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // lấy thông tin người dùng đang đăng nhập
        ]);
    }

    /**
     * Cập nhật thông tin hồ sơ người dùng,
     * đồng thời đồng bộ với bảng "patients".
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user(); // Lấy người dùng hiện tại
        $validated = $request->validated(); // Lấy dữ liệu đã được xác thực từ request

        // Gán dữ liệu hợp lệ vào model user
        $user->fill($validated);

        // Nếu email thay đổi → reset trạng thái xác minh email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Lưu thông tin cập nhật vào cơ sở dữ liệu
        $user->save();

        /**
         * ============================
         * XỬ LÝ HÌNH ẢNH (AVATAR)
         * ============================
         */
        $avatarPath = $user->patient->avatar ?? null; // Lấy đường dẫn avatar cũ (nếu có)

        // Nếu người dùng có upload avatar mới
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ khỏi bộ nhớ nếu tồn tại
            if (!empty($avatarPath) && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            // Lưu ảnh mới vào thư mục "patients/avatar" (trong disk public)
            $avatarPath = $request->file('avatar')->store('patients/avatar', 'public');
        }

        /**
         * ============================
         * CẬP NHẬT / TẠO BẢN GHI BỆNH NHÂN
         * ============================
         * Nếu user đã có bản ghi trong bảng patients thì cập nhật,
         * nếu chưa có thì tạo mới (theo user_id).
         */
        $user->patient()->updateOrCreate(
            ['user_id' => $user->id], // điều kiện để tìm bản ghi
            [
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'phone' => $validated['phone'] ?? null,
                'birthdate' => $validated['birthdate'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'insurance_number' => $validated['insurance_number'] ?? null,
                'avatar' => $avatarPath, // cập nhật đường dẫn ảnh đại diện
            ]
        );

        // Chuyển hướng về trang chỉnh sửa kèm thông báo cập nhật thành công
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Đổi mật khẩu người dùng hiện tại.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Xác thực dữ liệu nhập
        $request->validate([
            'current_password' => 'required', // bắt buộc nhập mật khẩu hiện tại
            'password' => 'required|confirmed|min:8', // mật khẩu mới ít nhất 8 ký tự và phải trùng với xác nhận
        ]);

        $user = Auth::user(); // Lấy user hiện tại

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        // Cập nhật mật khẩu mới (sau khi mã hóa)
        $user->password = Hash::make($request->password);
        $user->save();

        // Chuyển hướng kèm thông báo
        return redirect()->route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Xóa tài khoản người dùng (có yêu cầu xác nhận mật khẩu).
     * Sau khi xóa, đăng xuất và hủy session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user(); // Lấy user hiện tại

        // Xác thực mật khẩu trước khi xóa tài khoản
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        // Đăng xuất khỏi hệ thống
        Auth::logout();

        // Xóa user (và có thể cascade xóa dữ liệu liên quan)
        $user->delete();

        // Làm mới session để đảm bảo bảo mật
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng về trang chủ sau khi xóa thành công
        return redirect('/')->with('status', 'Tài khoản đã được xóa thành công.');
    }
}
