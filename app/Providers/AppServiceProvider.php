<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\LabTest;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        //
                // Ví dụ: truyền biến $departments cho view welcome
                View::composer('welcome', function ($view) {
                    $view->with('departments', Department::all());
                });

        View::composer('layouts.admin', function ($view) {
            $newAppointmentsCount = Appointment::where('status', 'pending')->count();
            $newLabTestsCount = LabTest::where('status', 'requested')->count();

            $view->with([
                'newAppointmentsCount' => $newAppointmentsCount,
                'newLabTestsCount' => $newLabTestsCount,
            ]);
        });
    }
}
