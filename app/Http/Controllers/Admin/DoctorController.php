<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Role ID cho bác sĩ trong hệ thống.
     */
    private const DOCTOR_ROLE_ID = 2;

    /**
     * Hiển thị danh sách bác sĩ.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'department'])->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Hiển thị form thêm mới bác sĩ.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();

        return view('admin.doctors.create', compact('departments'));
    }

    /**
     * Lưu bác sĩ mới vào database.
     * - Sử dụng transaction để đảm bảo toàn vẹn dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Chuẩn hóa email và validate dữ liệu
        $this->normalizeEmail($request);
        $this->validateDoctorData($request);

        // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
        DB::transaction(function () use ($request) {
            $user = $this->createUserForDoctor($request);
            $this->createDoctorProfile($user->id, $request);
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Thêm bác sĩ thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa bác sĩ.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\View\View
     */
    public function edit(Doctor $doctor)
    {
        $departments = Department::all();

        return view('admin.doctors.edit', compact('doctor', 'departments'));
    }

    /**
     * Cập nhật thông tin bác sĩ.
     * - Sử dụng transaction để đảm bảo toàn vẹn dữ liệu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Doctor $doctor)
    {
        // Chuẩn hóa email và validate dữ liệu
        $this->normalizeEmail($request);
        $this->validateDoctorData($request, $doctor);

        // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
        DB::transaction(function () use ($request, $doctor) {
            $this->updateUserInfo($doctor, $request);
            $this->updateDoctorProfile($doctor, $request);
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Cập nhật bác sĩ thành công!');
    }

    /**
     * Hiển thị chi tiết bác sĩ (tạm thời chuyển về danh sách).
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Doctor $doctor)
    {
        return redirect()->route('admin.doctors.index')
            ->with('success', 'Đã chuyển về danh sách bác sĩ.');
    }

    /**
     * Xóa bác sĩ và tài khoản liên kết.
     * - Không cho phép xóa nếu bác sĩ còn lịch hẹn.
     * - Sử dụng transaction để đảm bảo toàn vẹn dữ liệu.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Doctor $doctor)
    {
        // Kiểm tra nếu bác sĩ còn lịch hẹn thì không cho xóa
        if ($this->hasAppointments($doctor)) {
            return back()->with(
                'error',
                'Không thể xóa bác sĩ vì vẫn còn lịch hẹn liên quan. Vui lòng xóa/chuyển lịch hẹn trước.'
            );
        }

        // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
        DB::transaction(function () use ($doctor) {
            $user = $doctor->user;
            $doctor->delete();
            $user->delete();
        });

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Xóa bác sĩ thành công!');
    }

    /**
     * Lấy danh sách bác sĩ theo khoa (API endpoint).
     *
     * @param  int  $departmentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsByDepartment($departmentId)
    {
        $doctors = Doctor::with('user')
            ->where('department_id', $departmentId)
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'department_id' => $doctor->department_id,
                ];
            });

        return response()->json($doctors);
    }

    /**
     * Hiển thị lịch làm việc của bác sĩ trong ngày.
     *
     * @param  int  $doctorId
     * @return \Illuminate\View\View
     */
    public function schedule($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);
        $today = Carbon::today()->toDateString();

        // Đếm số lịch hẹn theo ca
        $morningCount = $this->countAppointmentsByShift($doctorId, $today, 'morning');
        $afternoonCount = $this->countAppointmentsByShift($doctorId, $today, 'afternoon');

        return view('admin.doctors.schedule', compact('doctor', 'today', 'morningCount', 'afternoonCount'));
    }

    /**
     * Chuẩn hóa email: chuyển về chữ thường và loại bỏ khoảng trắng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function normalizeEmail(Request $request): void
    {
        $request->merge([
            'email' => is_string($request->email) ? strtolower(trim($request->email)) : $request->email,
        ]);
    }

    /**
     * Validate dữ liệu bác sĩ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor|null  $doctor
     * @return void
     */
    private function validateDoctorData(Request $request, ?Doctor $doctor = null): void
    {
        // Chuẩn bị rule cho email (unique hoặc ignore nếu đang update)
        $emailRule = ['required', 'email', Rule::unique('users', 'email')];

        if ($doctor) {
            $emailRule = ['required', 'email', Rule::unique('users', 'email')->ignore($doctor->user_id)];
        }

        // Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $doctor ? 'nullable|min:6' : 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
        ]);
    }

    /**
     * Tạo tài khoản user cho bác sĩ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User
     */
    private function createUserForDoctor(Request $request): User
    {
        return User::create([
            'role_id' => self::DOCTOR_ROLE_ID,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);
    }

    /**
     * Tạo hồ sơ bác sĩ.
     *
     * @param  int  $userId
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Doctor
     */
    private function createDoctorProfile(int $userId, Request $request): Doctor
    {
        return Doctor::create([
            'user_id' => $userId,
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
        ]);
    }

    /**
     * Cập nhật thông tin user của bác sĩ.
     *
     * @param  \App\Models\Doctor  $doctor
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function updateUserInfo(Doctor $doctor, Request $request): void
    {
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
        ];

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $doctor->user->update($updateData);
    }

    /**
     * Cập nhật thông tin hồ sơ bác sĩ.
     *
     * @param  \App\Models\Doctor  $doctor
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function updateDoctorProfile(Doctor $doctor, Request $request): void
    {
        $doctor->update([
            'department_id' => $request->department_id,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
        ]);
    }

    /**
     * Kiểm tra xem bác sĩ có lịch hẹn hay không.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return bool
     */
    private function hasAppointments(Doctor $doctor): bool
    {
        return Appointment::where('doctor_id', $doctor->id)->exists();
    }

    /**
     * Đếm số lượng lịch hẹn theo ca khám.
     *
     * @param  int  $doctorId
     * @param  string  $date
     * @param  string  $shift  'morning' hoặc 'afternoon'
     * @return int
     */
    private function countAppointmentsByShift(int $doctorId, string $date, string $shift): int
    {
        // Map shift name
        $shiftMap = [
            'morning' => 'Ca sáng (07:30 - 11:30)',
            'afternoon' => 'Ca chiều (13:00 - 17:00)',
        ];

        return Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->where('medical_examination', $shiftMap[$shift])
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->count();
    }
}
