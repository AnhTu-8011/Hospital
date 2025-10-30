<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có role admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Tạo tài khoản admin mặc định
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now()
            ]
        );
    }
}
