<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // ✅ thêm dòng này
use App\Models\Department;

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
        //
                // Ví dụ: truyền biến $departments cho view welcome
                View::composer('welcome', function ($view) {
                    $view->with('departments', Department::all());
                });
    }
}
