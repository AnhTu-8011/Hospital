<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Share admin menu data with all admin views
        View::composer('layouts.admin', function ($view) {
            $menu = [
                [
                    'title' => 'Tổng quan',
                    'icon' => 'fas fa-home',
                    'route' => 'admin.dashboard',
                    'active' => request()->routeIs('admin.dashboard'),
                ],
                [
                    'title' => 'Quản lý bác sĩ',
                    'icon' => 'fas fa-user-md',
                    'route' => '#',
                    'active' => request()->routeIs('admin.doctors.*'),
                    'children' => [
                        ['title' => 'Danh sách bác sĩ', 'route' => '#'],
                        ['title' => 'Thêm bác sĩ', 'route' => '#'],
                        ['title' => 'Lịch làm việc', 'route' => '#'],
                    ],
                ],
                [
                    'title' => 'Quản lý bệnh nhân',
                    'icon' => 'fas fa-user-injured',
                    'route' => '#',
                    'active' => request()->routeIs('admin.patients.*'),
                ],
                [
                    'title' => 'Lịch hẹn',
                    'icon' => 'fas fa-calendar-check',
                    'route' => '#',
                    'active' => request()->routeIs('admin.appointments.*'),
                ],
                [
                    'title' => 'Dịch vụ',
                    'icon' => 'fas fa-procedures',
                    'route' => '#',
                    'active' => request()->routeIs('admin.services.*'),
                ],
                [
                    'title' => 'Quản lý người dùng',
                    'icon' => 'fas fa-users-cog',
                    'route' => '#',
                    'active' => request()->routeIs('admin.users.*'),
                ],
                [
                    'title' => 'Cài đặt',
                    'icon' => 'fas fa-cog',
                    'route' => '#',
                    'active' => request()->routeIs('admin.settings.*'),
                ],
            ];

            $view->with('adminMenu', $menu);
        });
    }
}
