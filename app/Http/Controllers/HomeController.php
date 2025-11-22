<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;   // nếu cần cho modal
use App\Models\Service;  // nếu cần cho modal
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Trang welcome (hoặc home)
    public function welcome()
    {
        $departments = Department::all();
        // nếu modal đặt lịch cần bác sĩ và dịch vụ:
        $doctors = Doctor::with(['user', 'department'])->get();
        // Lấy toàn bộ dịch vụ (sẽ lọc theo khoa ở view)
        $services = Service::with('department')->get();

        return view('welcome', compact('departments', 'doctors', 'services'));
    }

    // Trang danh sách bác sĩ (frontend)
    public function doctorsPage()
    {
        $doctors = Doctor::with(['user', 'department'])->get();
        return view('home.doctors.index', compact('doctors'));
    }

    // Trang danh sách chuyên khoa (frontend)
    public function departmentsPage()
    {
        $departments = Department::all();
        $services = Service::with('department')->get();
        return view('home.departments.index', compact('departments', 'services'));
    }

    // Trang danh sách dịch vụ (frontend)
    public function servicesPage()
    {
        $services = Service::with('department')->get();
        return view('home.services.index', compact('services'));
    }
}
