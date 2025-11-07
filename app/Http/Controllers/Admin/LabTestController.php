<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Department;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    // 📋 Danh sách xét nghiệm
    public function index()
    {
        $labTests = LabTest::with(['department', 'record', 'doctor'])
            ->latest()
            ->paginate(10);

        return view('admin.lab_tests.index', compact('labTests'));
    }

    // 💾 Lưu xét nghiệm mới
    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'department_id' => 'required|exists:departments,id',
            'test_name' => 'required|string|max:255',
        ]);

        $data = $request->only(['medical_record_id', 'department_id', 'test_name', 'note']);
        $data['requested_by'] = auth()->id();
        $data['uploaded_by'] = auth()->id();
        $data['status'] = 'completed';

        // Upload ảnh chính
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('lab_tests', 'public');
        }

        // Upload ảnh phụ
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $paths[] = $file->store('lab_tests', 'public');
            }
            $data['images'] = $paths;
        }

        LabTest::create($data);

        return redirect()->route('admin.lab_tests.index')
            ->with('success', 'Thêm xét nghiệm thành công!');
    }


    // 🔄 Cập nhật xét nghiệm
    public function update(Request $request, $id)
    {
        $test = LabTest::findOrFail($id);

        $request->validate([
            'test_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $test->update([
            'test_name' => $request->test_name,
            'department_id' => $request->department_id,
            'note' => $request->note,
        ]);

        return redirect()->route('admin.lab_tests.index')
            ->with('success', 'Cập nhật xét nghiệm thành công!');
    }

    // 📤 Upload kết quả xét nghiệm
    public function uploadResult($id)
    {
        $test = LabTest::findOrFail($id);
        return view('admin.lab_tests.upload_result', compact('test'));
    }

    // 💾 Lưu kết quả xét nghiệm (ảnh)
    public function saveUpload(Request $request, $id)
    {
        $test = LabTest::findOrFail($id);

        if ($request->hasFile('image')) {
            $test->image = $request->file('image')->store('lab_tests', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('lab_tests', 'public');
            }
            $test->images = $images;
        }

        $test->status = 'completed';
        $test->uploaded_by = auth()->id();
        $test->save();

        return redirect()->route('admin.lab_tests.index')
            ->with('success', 'Đã upload kết quả thành công.');
    }

    // ❌ Xóa xét nghiệm
    public function destroy($id)
    {
        LabTest::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa xét nghiệm.');
    }
}
