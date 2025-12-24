<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo các roles trước
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'doctor']);
        Role::firstOrCreate(['name' => 'patient']);
        Role::firstOrCreate(['name' => 'leader']);

        // Sau đó chạy các seeders khác
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            DoctorUserSeeder::class,
            LeaderUserSeeder::class,
        ]);
    }
}
