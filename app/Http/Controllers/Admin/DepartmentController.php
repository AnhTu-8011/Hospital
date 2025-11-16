<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Trang welcome (dành cho người dùng thường).
     * Hiển thị danh sách khoa, bác sĩ, dịch vụ.
     */ 
    public function welcome()
    {
        $departments = Department::all();
        $doctors     = Doctor::with('user')->get(); 
        $services    = Service::with('department')->get();
        
        return view('welcome', compact('departments', 'doctors', 'services'));
    }

    /**
     * Danh sách tất cả khoa (admin).
     */
    public function index()
    {
        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Form thêm khoa mới.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Lưu khoa mới vào CSDL.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        Department::create($request->only('name', 'description'));

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Thêm khoa thành công!');
    }

    /**
     * Form chỉnh sửa khoa.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Cập nhật thông tin khoa.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($request->only('name', 'description'));

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Cập nhật khoa thành công!');
    }

    /**
     * Xóa khoa.
     */
    public function destroy(Department $department)
    {
        // Nếu còn bác sĩ thuộc khoa này thì không cho xóa
        if ($department->doctors()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with('error', 'Không thể xóa khoa vì vẫn còn bác sĩ thuộc khoa này.');
        }

        // Nếu có dịch vụ thuộc khoa này thì cũng không cho xóa (nếu có quan hệ services)
        if (method_exists($department, 'services') && $department->services()->exists()) {
            return redirect()
                ->route('admin.departments.index')
                ->with('error', 'Không thể xóa khoa vì vẫn còn dịch vụ thuộc khoa này.');
        }

        $department->delete();

        return redirect()
            ->route('admin.departments.index')
            ->with('success', 'Xóa khoa thành công!');
    }
}

