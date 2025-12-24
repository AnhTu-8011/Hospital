<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DepartmentController extends Controller
{
    /**
     * Trang welcome (dành cho người dùng thường).
     * Hiển thị danh sách khoa, bác sĩ, dịch vụ.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        $departments = Department::all();
        $doctors = Doctor::with('user')->get();
        $services = Service::with('department')->get();

        return view('welcome', compact('departments', 'doctors', 'services'));
    }

    /**
     * Hiển thị danh sách tất cả khoa (admin).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Hiển thị form thêm khoa mới.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Lưu khoa mới vào CSDL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Chuẩn bị rules validation
        $rules = [
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];

        // Thêm rule is_active nếu cột tồn tại
        if (Schema::hasColumn('departments', 'is_active')) {
            $rules['is_active'] = 'nullable|boolean';
        }

        // Validate dữ liệu đầu vào
        $request->validate($rules);

        // Lấy dữ liệu hợp lệ
        $data = $request->only('name', 'description');

        // Thêm is_active nếu cột tồn tại
        if (Schema::hasColumn('departments', 'is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('departments', 'public');
        }

        // Tạo khoa mới
        Department::create($data);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Thêm khoa thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa khoa.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\View\View
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Cập nhật thông tin khoa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        // Chuẩn bị rules validation
        $rules = [
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];

        // Thêm rule is_active nếu cột tồn tại
        if (Schema::hasColumn('departments', 'is_active')) {
            $rules['is_active'] = 'nullable|boolean';
        }

        // Validate dữ liệu đầu vào
        $request->validate($rules);

        // Lấy dữ liệu hợp lệ
        $data = $request->only('name', 'description');

        // Thêm is_active nếu cột tồn tại
        if (Schema::hasColumn('departments', 'is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        // Xử lý upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('departments', 'public');
        }

        // Cập nhật khoa
        $department->update($data);

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Cập nhật khoa thành công!');
    }

    /**
     * Xóa khoa.
     * - Không cho phép xóa nếu còn bác sĩ hoặc dịch vụ thuộc khoa này.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department)
    {
        // Kiểm tra nếu còn bác sĩ thuộc khoa này thì không cho xóa
        if ($department->doctors()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with('error', 'Không thể xóa khoa vì vẫn còn bác sĩ thuộc khoa này.');
        }

        // Kiểm tra nếu có dịch vụ thuộc khoa này thì cũng không cho xóa
        if (method_exists($department, 'services') && $department->services()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with('error', 'Không thể xóa khoa vì vẫn còn dịch vụ thuộc khoa này.');
        }

        // Xóa khoa
        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Xóa khoa thành công!');
    }
}
