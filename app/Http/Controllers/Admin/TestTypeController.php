<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use App\Models\Department;
use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function index()
    {
        $types = TestType::with('department')->latest()->paginate(10);
        return view('admin.test_types.index', compact('types'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.test_types.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        TestType::create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.test-types.index')->with('success', 'Đã thêm loại xét nghiệm mới!');
    }

    public function edit($id)
    {
        $type = TestType::findOrFail($id);
        $departments = Department::all();
        return view('admin.test_types.edit', compact('type', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $type = TestType::findOrFail($id);
        $type->update($request->only('name', 'description', 'department_id'));
        return redirect()->route('admin.test-types.index')->with('success', 'Đã cập nhật loại xét nghiệm!');
    }

    public function destroy($id)
    {
        TestType::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa loại xét nghiệm.');
    }
}
