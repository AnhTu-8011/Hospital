<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Department;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('department')->get();
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
        ]);

        Service::create($request->only('name', 'description', 'price', 'department_id'));

        return redirect()->route('admin.services.index')
                         ->with('success', 'Thêm dịch vụ thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $departments = Department::all();
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
        ]);

        $service->update($request->only('name', 'description', 'price', 'department_id'));

        return redirect()->route('admin.services.index')
                         ->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Xóa dịch vụ thành công!');
    }
}
