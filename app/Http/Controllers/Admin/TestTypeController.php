<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\TestType;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    /**
     * Hiển thị danh sách loại xét nghiệm.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $types = TestType::with('department')->latest()->paginate(10);

        return view('admin.test_types.index', compact('types'));
    }

    /**
     * Hiển thị form tạo loại xét nghiệm mới.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();

        return view('admin.test_types.create', compact('departments'));
    }

    /**
     * Lưu loại xét nghiệm mới vào database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        // Tạo loại xét nghiệm mới
        TestType::create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.test-types.index')->with('success', 'Đã thêm loại xét nghiệm mới!');
    }

    /**
     * Hiển thị form chỉnh sửa loại xét nghiệm.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $type = TestType::findOrFail($id);
        $departments = Department::all();

        return view('admin.test_types.edit', compact('type', 'departments'));
    }

    /**
     * Cập nhật thông tin loại xét nghiệm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $type = TestType::findOrFail($id);

        // Cập nhật thông tin loại xét nghiệm
        $type->update($request->only('name', 'description', 'department_id'));

        return redirect()->route('admin.test-types.index')->with('success', 'Đã cập nhật loại xét nghiệm!');
    }

    /**
     * Xóa loại xét nghiệm khỏi database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        TestType::findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa loại xét nghiệm.');
    }
}
