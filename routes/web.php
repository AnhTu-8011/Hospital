<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    DashboardController,
    DepartmentController,
    ReportController,
    DoctorController as AdminDoctorController,
    AppointmentController as AdminAppointmentController
};
use App\Http\Controllers\{
    ServiceController,
    PatientController,
    UserController,
    AppointmentController,
    PaymentController,
    ChatController
};
use App\Http\Controllers\Doctor\{
    DoctorDashboardController,
    DoctorProfileController,
    DoctorRecordController,
    HistoryController
};

/*
|--------------------------------------------------------------------------
| 🏠 Trang chính (hiển thị khoa, dịch vụ, bác sĩ)
|--------------------------------------------------------------------------
*/
Route::get('/', [DepartmentController::class, 'welcome'])->name('home');

// 📅 Modal đặt lịch hẹn (hiển thị popup)
Route::get('/appointment/modal', function () {
    $departments = \App\Models\Department::all();
    $services = \App\Models\Service::with('department')->get();
    $doctors = \App\Models\Doctor::with(['user', 'department'])->get();
    return view('modal.appointment', compact('departments', 'services', 'doctors'));
})->name('modal.appointment');

/*
|--------------------------------------------------------------------------
| 📊 Dashboard mặc định - redirect based on role
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->hasRole('doctor')) {
        return redirect()->route('doctor.dashboard');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| 🧑‍💼 Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD
        Route::resource('departments', DepartmentController::class);
        Route::resource('doctors', AdminDoctorController::class);
        Route::resource('appointments', AdminAppointmentController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('patients', PatientController::class);
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // Cập nhật trạng thái lịch hẹn
        Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
            ->name('appointments.status');

        // Trang thống kê
        Route::get('/statistics', fn() => view('admin.statistics'))->name('statistics.index');
    });

/*
|--------------------------------------------------------------------------
| 🩺 Doctor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

        // Hồ sơ cá nhân
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [DoctorProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [DoctorProfileController::class, 'updatePassword'])->name('password.update');

        // Lịch hẹn
        Route::get('/appointments', [AppointmentController::class, 'doctorAppointments'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

        // Bệnh nhân
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

        // Hồ sơ bệnh án
        Route::get('/patients/{patient}/records', [MedicalRecordController::class, 'index'])->name('patients.records');
        Route::post('/patients/{patient}/records', [MedicalRecordController::class, 'store'])->name('patients.records.store');
        
    });

/*
|--------------------------------------------------------------------------
| 👩‍🦰 Patient Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:patient'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        Route::get('/dashboard', [PatientController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| 🔐 Shared Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lịch hẹn (AppointmentController)
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{appointment}/cancel', function (\App\Models\Appointment $appointment) {
        return redirect()->route('appointments.show', $appointment->id)
            ->with('error', 'Hành động hủy lịch hẹn yêu cầu gửi biểu mẫu (PATCH).');
    })->name('appointments.cancel.view');
    Route::get('/appointments/{appointment}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // API lấy bác sĩ theo khoa
    Route::get('/appointments/doctors/{departmentId}', [AppointmentController::class, 'getDoctors']);
    Route::get('/departments/{id}/doctors', [AdminDoctorController::class, 'getDoctorsByDepartment']);

    // Trang kết quả thanh toán
    Route::view('/appointments/success', 'appointments.success')->name('appointments.success');
    Route::view('/appointments/fail', 'appointments.fail')->name('appointments.fail');
});

/*
|--------------------------------------------------------------------------
| 💰 VNPay Payment Routes
|--------------------------------------------------------------------------
*/
Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment'])->name('vnpay_payment');
Route::get('/vnpay_return', [PaymentController::class, 'vnpay_return'])->name('vnpay.return');

/*
|--------------------------------------------------------------------------
| 🩻 Doctor Extra Routes (bên ngoài group)
|--------------------------------------------------------------------------
*/
Route::get('/doctor/patient-record/{appointment}', [DoctorRecordController::class, 'showPatientRecord'])->name('doctor.patient.record');
Route::put('/doctor/records/{record}', [DoctorRecordController::class, 'update'])->name('doctor.records.update');
Route::get('/doctor/patient-history', [HistoryController::class, 'history'])->name('doctor.patient.history');

/*
|--------------------------------------------------------------------------
| 📋 Hồ sơ khám bệnh (Appointment record)
|--------------------------------------------------------------------------
*/
Route::get('/appointments/{id}/record', [AppointmentController::class, 'viewRecord'])->name('appointments.record');

/*
|--------------------------------------------------------------------------
| 💬 Chat Routes
|--------------------------------------------------------------------------
*/
// Patient gửi tin nhắn cho admin
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

// Lấy tin nhắn giữa patient và admin
Route::get('/chat/messages/{receiverId}', [ChatController::class, 'getMessages'])->name('chat.messages');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{receiverId}', [ChatController::class, 'getMessages'])->name('chat.get');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
});

// doanh thu
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| 🔑 Auth Routes (Laravel Breeze / Jetstream / Fortify)
|--------------------------------------------------------------------------
*/

Route::get('/admin/doctors/{doctor}/schedule', [App\Http\Controllers\Admin\DoctorController::class, 'schedule'])
    ->name('admin.doctors.schedule');

require __DIR__ . '/auth.php';
