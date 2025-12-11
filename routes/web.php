<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\{
    DashboardController,
    DepartmentController as AdminDepartmentController,
    ReportController,
    DoctorController as AdminDoctorController,
    AppointmentController as AdminAppointmentController,
    DiseaseController as AdminDiseaseController
};
use App\Http\Controllers\{
    AIChatController,
    ServiceController,
    PatientController,
    UserController,
    AppointmentController,
    PaymentController,
    ChatController,
    ForgotPasswordController,
    ResetPasswordController,
    HomeController
};
use App\Http\Controllers\Doctor\{
    DoctorDashboardController,
    DoctorProfileController,
    DoctorRecordController,
    HistoryController
};
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\DoctorAuthController;
use App\Http\Controllers\Auth\PatientAuthController;

/*
|--------------------------------------------------------------------------
| 🏠 Trang chính (Home)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/doctors', [HomeController::class, 'doctorsPage'])->name('doctors.index');
Route::get('/departments', [HomeController::class, 'departmentsPage'])->name('departments.index');
Route::get('/services', [HomeController::class, 'servicesPage'])->name('services.index');
Route::view('/introduces', 'home.introduces.index')->name('introduces.index');

// 🔎 Trang tư vấn theo triệu chứng
Route::get('/advisor', [HomeController::class, 'advisorPage'])->name('advisor.index');

// 📅 Trang / popup đặt lịch hẹn
Route::get('/appointment/modal', function () {
    $departments = \App\Models\Department::all();
    $services = \App\Models\Service::with('department')->get();
    $doctors = \App\Models\Doctor::with(['user', 'department'])->get();
    return view('home.booking', compact('departments', 'services', 'doctors'));
})->name('modal.appointment');

/*
//--------------------------------------------------------------------------
| Dashboard mặc định: nếu là admin/doctor thì chuyển sang dashboard riêng,
| còn lại (patient/user thường) thì tới patient.dashboard
//--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user && method_exists($user, 'hasRole')) {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }
    }

    return redirect()->route('patient.dashboard');
})->name('dashboard');

/*
//--------------------------------------------------------------------------
| ADMIN AUTH
//--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
//--------------------------------------------------------------------------
| 🧑‍💼 ADMIN ROUTES
//--------------------------------------------------------------------------
*/
Route::middleware(['auth:web_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 📊 Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ⚙️ CRUD quản lý
        Route::resource('departments', AdminDepartmentController::class);
        Route::resource('doctors', AdminDoctorController::class);
        Route::resource('appointments', AdminAppointmentController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('diseases', AdminDiseaseController::class);
        Route::resource('patients', PatientController::class);
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // 🧪 Loại xét nghiệm (Danh mục)
        Route::resource('test-types', \App\Http\Controllers\Admin\TestTypeController::class)->except(['show']);

        // 🔄 Cập nhật trạng thái lịch hẹn
        Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
            ->name('appointments.status');

        // 📈 Trang thống kê
        Route::get('/statistics', fn() => view('admin.statistics'))->name('statistics.index');

        /*
        |--------------------------------------------------------------------------
        | 🧪 Quản lý xét nghiệm (Lab Tests)
        |--------------------------------------------------------------------------
        */
        Route::get('/lab-tests', [\App\Http\Controllers\Admin\LabTestController::class, 'index'])
            ->name('lab_tests.index');
        Route::get('/lab-tests/{id}/upload', [\App\Http\Controllers\Admin\LabTestController::class, 'uploadResult'])
            ->name('lab_tests.upload');
        Route::post('/lab-tests/{id}/upload', [\App\Http\Controllers\Admin\LabTestController::class, 'saveUpload'])
            ->name('lab_tests.saveUpload');
        Route::delete('/lab-tests/{id}', [\App\Http\Controllers\Admin\LabTestController::class, 'destroy'])
            ->name('lab_tests.destroy');
    });

/*
//--------------------------------------------------------------------------
| 🔐 DOCTOR AUTH
//--------------------------------------------------------------------------
*/
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/login', [DoctorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DoctorAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [DoctorAuthController::class, 'logout'])->name('logout');
});

/*
//--------------------------------------------------------------------------
| 🩺 DOCTOR ROUTES
//--------------------------------------------------------------------------
*/
Route::middleware(['auth:web_doctor'])
    ->prefix('doctor')
    ->name('doctor.')
    ->group(function () {

        // 📊 Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

        // 👤 Hồ sơ cá nhân
        Route::get('/profile', [DoctorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [DoctorProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [DoctorProfileController::class, 'updatePassword'])->name('password.update');

        // 📅 Lịch hẹn
        Route::get('/appointments', [AppointmentController::class, 'doctorAppointments'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::post('/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');

        // 👨‍⚕️ Bệnh nhân
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');

        // 📋 Hồ sơ bệnh án
        Route::get('/patients/{patient}/records', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'index'])->name('patients.records');
        Route::post('/patients/{patient}/records', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'store'])->name('patients.records.store');

        // 🧪 Bác sĩ yêu cầu xét nghiệm
        Route::get('/records/{record}/lab-tests/create', [\App\Http\Controllers\Doctor\LabTestController::class, 'create'])
            ->name('lab_tests.create');
        Route::post('/records/{record}/lab-tests', [\App\Http\Controllers\Doctor\LabTestController::class, 'store'])
            ->name('lab_tests.store');
        Route::get('/records/{record}/lab-tests', function (\App\Models\MedicalRecord $record) {
            return redirect()->route('doctor.patient.record', $record->appointment_id)
                ->with('error', 'Vui lòng gửi yêu cầu bằng biểu mẫu.');
        });
    });

/*
//--------------------------------------------------------------------------
| 🔐 PATIENT AUTH
//--------------------------------------------------------------------------
*/
Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/login', [PatientAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PatientAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [PatientAuthController::class, 'logout'])->name('logout');
});

/*
//--------------------------------------------------------------------------
| 👩‍🦰 PATIENT ROUTES (guard mặc định web)
| Hiện tại không dùng dashboard riêng, bệnh nhân sau khi đăng nhập sẽ
| sử dụng trang chính (route 'home'). Nếu sau này cần, có thể thêm lại
| route patient.dashboard tại đây.
//--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        // Các route riêng cho bệnh nhân (nếu có) thêm tại đây
    });

/*
|--------------------------------------------------------------------------
| 🔐 Shared Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 👤 Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📅 Lịch hẹn
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{appointment}/cancel', function (\App\Models\Appointment $appointment) {
        return redirect()->route('appointments.show', $appointment->id)
            ->with('error', 'Hành động hủy lịch hẹn yêu cầu gửi biểu mẫu (PATCH).');
    })->name('appointments.cancel.view');
    Route::get('/appointments/{appointment}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

    // 📋 Hồ sơ khám bệnh
    Route::get('/appointments/{id}/record', [AppointmentController::class, 'viewRecord'])->name('appointments.record');

    // API lấy bác sĩ theo khoa
    Route::get('/appointments/doctors/{departmentId}', [AppointmentController::class, 'getDoctors']);
    Route::get('/departments/{id}/doctors', [AdminDoctorController::class, 'getDoctorsByDepartment']);

    // Trang kết quả thanh toán
    Route::view('/appointments/success', 'appointments.success')->name('appointments.success');
    Route::view('/appointments/fail', 'appointments.fail')->name('appointments.fail');

    // 💬 Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{receiverId}', [ChatController::class, 'getMessages'])->name('chat.get');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
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
| 🔓 Simple GET Logout (logs out all guards)
|--------------------------------------------------------------------------
*/
Route::get('/logout', function () {
    foreach (['web', 'web_admin', 'web_doctor', 'web_patient'] as $guard) {
        try {
            \Illuminate\Support\Facades\Auth::guard($guard)->logout();
        } catch (\Throwable $e) {
            // ignore
        }
    }

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
});

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
| 🧠 AI Chat (Giao diện AI)
|--------------------------------------------------------------------------
*/
Route::get('/ai-chat', fn() => view('chat.ai_chat'))->name('ai.chat');

/*
|--------------------------------------------------------------------------
| 🔧 Khác
|--------------------------------------------------------------------------
*/
Route::get('/admin/doctors/{doctor}/schedule', [AdminDoctorController::class, 'schedule'])
    ->name('admin.doctors.schedule');

// 👩‍💻 Doanh thu (Dashboard admin)
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// Quên MK
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.store');
/*
|--------------------------------------------------------------------------
| 🔑 Auth Routes (Laravel Breeze / Jetstream / Fortify)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
