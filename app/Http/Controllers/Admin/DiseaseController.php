<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Disease, Department, DiseaseSymptom};

class DiseaseController extends Controller
{
    public function index()
    {
        $diseases = Disease::with(['department', 'symptoms'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
        return view('admin.diseases.index', compact('diseases'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.diseases.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'description', 'department_id');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('diseases', 'public');
        }

        $disease = Disease::create($data);

        $symptoms = array_filter($request->input('symptoms', []), fn($s) => !empty(trim($s)));
        foreach ($symptoms as $symptomName) {
            DiseaseSymptom::create([
                'disease_id' => $disease->id,
                'symptom_name' => trim($symptomName),
            ]);
        }

        return redirect()->route('admin.diseases.index')->with('success', 'Thêm bệnh thành công!');
    }

    public function edit(Disease $disease)
    {
        $departments = Department::all();
        $disease->load('symptoms');
        return view('admin.diseases.edit', compact('disease', 'departments'));
    }

    public function update(Request $request, Disease $disease)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'symptoms' => 'nullable|array',
            'symptoms.*' => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'description', 'department_id');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('diseases', 'public');
        }

        $disease->update($data);

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

    public function destroy(Disease $disease)
    {
        $disease->delete();
        return redirect()->route('admin.diseases.index')->with('success', 'Xóa bệnh thành công!');
    }
}
