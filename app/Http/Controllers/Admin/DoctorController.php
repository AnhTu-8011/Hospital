<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DoctorController extends Controller
{
    /**
     * Hiển thị danh sách bác sĩ (admin).
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'department'])->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Hiển thị form thêm mới bác sĩ.
     */
    public function create()
    {
        $departments = Department::all();
        return view('admin.doctors.create', compact('departments'));
    }

    /**
     * Lưu bác sĩ mới vào CSDL.
     */
    public function store(Request $request)
    {
        $request->merge([
            'email' => is_string($request->email) ? strtolower(trim($request->email)) : $request->email,
        ]);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', Rule::unique('users', 'email')],
            'password'       => 'required|min:6',
            'phone'          => 'nullable|string|max:20',
            'gender'         => 'nullable|in:male,female,other',
            'address'        => 'nullable|string|max:255',
            'department_id'  => 'required|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
        ]);

        DB::transaction(function () use ($request) {
            // Tạo tài khoản user cho bác sĩ
            $user = User::create([
                'role_id'   => 2, // 2 = bác sĩ
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
                'phone'     => $request->phone,
                'gender'    => $request->gender,
                'address'   => $request->address,
            ]);

            // Tạo hồ sơ bác sĩ liên kết với user
            Doctor::create([
                'user_id'        => $user->id,
                'department_id'  => $request->department_id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
            ]);
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Thêm bác sĩ thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa bác sĩ.
     */
    public function edit(Doctor $doctor)
    {
        $departments = Department::all();
        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    /**
     * Cập nhật thông tin bác sĩ.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->merge([
            'email' => is_string($request->email) ? strtolower(trim($request->email)) : $request->email,
        ]);

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($doctor->user_id)],
            'phone'          => 'nullable|string|max:20',
            'gender'         => 'nullable|in:male,female,other',
            'address'        => 'nullable|string|max:255',
            'department_id'  => 'required|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            // Cập nhật thông tin user
            $doctor->user->update([
                'name'    => $request->name,
                'email'   => $request->email,
                'phone'   => $request->phone,
                'gender'  => $request->gender,
                'address' => $request->address,
            ]);

            // Cập nhật thông tin bác sĩ
            $doctor->update([
                'department_id'  => $request->department_id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
            ]);
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật bác sĩ thành công!');
    }

    /**
     * Hiển thị chi tiết bác sĩ (tạm thởi chuyển về danh sách).
     * Tránh lỗi thiếu phương thức khi dùng Route::resource.
     */
    public function show(Doctor $doctor)
    {
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Đã chuyển về danh sách bác sĩ.');
    }

    /**
     * Xóa bác sĩ và tài khoản liên kết.
     */
    public function destroy(Doctor $doctor)
    {
        // Ngăn xóa khi còn lịch hẹn liên quan để tránh lỗi ràng buộc FK
        $hasAppointments = Appointment::where('doctor_id', $doctor->id)->exists();
        if ($hasAppointments) {
            return back()->with('error', 'Không thể xóa bác sĩ vì vẫn còn lịch hẹn liên quan. Vui lòng xóa/chuyển lịch hẹn trước.');
        }

        DB::transaction(function () use ($doctor) {
            // Xóa hồ sơ bác sĩ trước (tránh FK từ doctors -> users)
            $doctor->delete();
            // Sau đó xóa tài khoản user liên kết
            $doctor->user->delete();
        });

        return redirect()->route('admin.doctors.index')->with('success', 'Xóa bác sĩ thành công!');
    }

    /**
     * Lấy danh sách bác sĩ theo khoa (API).
     */
    public function getDoctorsByDepartment($department_id)
    {
        $doctors = Doctor::with('user')
            ->where('department_id', $department_id)
            ->get()
            ->map(function ($doctor) {
                return [
                    'id'            => $doctor->id,
                    'name'          => $doctor->user->name, // Lấy tên từ bảng users
                    'department_id' => $doctor->department_id,
                ];
            });

        return response()->json($doctors);
    }

    public function schedule($doctorId)
    {
        $doctor = \App\Models\Doctor::with('user')->findOrFail($doctorId);
        $today = Carbon::today()->toDateString();

        // Đếm số ca sáng và chiều của hôm nay
        $morningCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->where('medical_examination', 'Ca sáng (07:30 - 11:30)')
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->count();

        $afternoonCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today)
            ->where('medical_examination', 'Ca chiều (13:00 - 17:00)')
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->count();

        return view('admin.doctors.schedule', compact('doctor', 'today', 'morningCount', 'afternoonCount'));
    }
}
