<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Disease;
use App\Models\DiseaseSymptom;
use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    /**
     * Hiển thị danh sách bệnh.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $diseases = Disease::with(['department', 'symptoms'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.diseases.index', compact('diseases'));
    }

    /**
     * Hiển thị form tạo bệnh mới.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();

        return view('admin.diseases.create', compact('departments'));
    }

    /**
     * Lưu bệnh mới vào database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|string|max:255',
        ]);

        // Lấy dữ liệu hợp lệ
        $data = $request->only('name', 'description', 'department_id');

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('diseases', 'public');
        }

        // Tạo bệnh mới
        $disease = Disease::create($data);

        // Lưu triệu chứng liên quan
        $symptoms = array_filter($request->input('symptoms', []), fn($s) => !empty(trim($s)));

        foreach ($symptoms as $symptomName) {
            DiseaseSymptom::create([
                'disease_id' => $disease->id,
                'symptom_name' => trim($symptomName),
            ]);
        }

        return redirect()->route('admin.diseases.index')->with('success', 'Thêm bệnh thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa bệnh.
     *
     * @param  \App\Models\Disease  $disease
     * @return \Illuminate\View\View
     */
    public function edit(Disease $disease)
    {
        $departments = Department::all();
        $disease->load('symptoms');

        return view('admin.diseases.edit', compact('disease', 'departments'));
    }

    /**
     * Cập nhật thông tin bệnh trong database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Disease  $disease
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Disease $disease)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|string|max:255',
        ]);

        // Lấy dữ liệu hợp lệ
        $data = $request->only('name', 'description', 'department_id');

        // Xử lý upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('diseases', 'public');
        }

        // Cập nhật bệnh
        $disease->update($data);

        // Xóa tất cả triệu chứng cũ và thêm lại
        DiseaseSymptom::where('disease_id', $disease->id)->delete();
        $symptoms = array_filter($request->input('symptoms', []), fn($s) => !empty(trim($s)));

        foreach ($symptoms as $symptomName) {
            DiseaseSymptom::create([
                'disease_id' => $disease->id,
                'symptom_name' => trim($symptomName),
            ]);
        }

        return redirect()->route('admin.diseases.index')->with('success', 'Cập nhật bệnh thành công!');
    }

    /**
     * Xóa bệnh khỏi database.
     *
     * @param  \App\Models\Disease  $disease
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Disease $disease)
    {
        $disease->delete();

        return redirect()->route('admin.diseases.index')->with('success', 'Xóa bệnh thành công!');
    }
}
