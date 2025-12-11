<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PatientAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Login dùng chung: mặc định coi như đăng nhập bệnh nhân
Route::get('login', function () {
    return redirect()->route('patient.login');
})->name('login');

// Cho phép form nào còn POST tới /login vẫn dùng logic đăng nhập bệnh nhân
Route::post('login', [PatientAuthController::class, 'login']);

Route::middleware('auth')->group(function () {

    // removed: email verification routes
    // removed: confirm password routes

    // Update password in profile
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});