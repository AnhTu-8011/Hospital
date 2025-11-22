<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\ServiceSymptom;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with(['department', 'symptoms'])->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.services.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'image'         => 'nullable|image|max:2048',
            'symptoms'      => 'nullable|array',
            'symptoms.*'    => 'nullable|string',
        ]);

        $data = $request->only('name', 'description', 'price', 'department_id');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        // Lưu triệu chứng
        if ($request->has('symptoms')) {
            $symptoms = array_filter($request->input('symptoms', []), function($symptom) {
                return !empty(trim($symptom));
            });
            
            foreach ($symptoms as $symptomName) {
                ServiceSymptom::create([
                    'service_id' => $service->id,
                    'symptom_name' => trim($symptomName),
                ]);
            }
        }

        return redirect()->route('admin.services.index')
                         ->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $departments = Department::all();
        // Load triệu chứng hiện có
        $service->load('symptoms');
        return view('admin.services.edit', compact('service', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'department_id' => 'required|exists:departments,id',
            'image'         => 'nullable|image|max:2048',
            'symptoms'      => 'nullable|array',
            'symptoms.*'    => 'nullable|string|max:255',
        ]);

        $data = $request->only('name', 'description', 'price', 'department_id');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        // Xóa tất cả triệu chứng cũ và thêm lại
        ServiceSymptom::where('service_id', $service->id)->delete();
        
        if ($request->has('symptoms')) {
            $symptoms = array_filter($request->input('symptoms', []), function($symptom) {
                return !empty(trim($symptom));
            });
            
            foreach ($symptoms as $symptomName) {
                ServiceSymptom::create([
                    'service_id' => $service->id,
                    'symptom_name' => trim($symptomName),
                ]);
            }
        }

        return redirect()->route('admin.services.index')
                         ->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // Không cho phép xóa nếu đã có lịch hẹn tham chiếu tới dịch vụ này
        if (Appointment::where('service_id', $service->id)->exists()) {
            return redirect()->route('admin.services.index')
                             ->with('error', 'Không thể xóa dịch vụ vì đã có lịch hẹn sử dụng dịch vụ này. Vui lòng hủy/đổi lịch liên quan trước.');
        }

        try {
            $service->delete();
            return redirect()->route('admin.services.index')
                             ->with('success', 'Xóa dịch vụ thành công!');
        } catch (\Throwable $e) {
            return redirect()->route('admin.services.index')
                             ->with('error', 'Xóa dịch vụ thất bại. Vui lòng thử lại sau.');
        }
    }
}
