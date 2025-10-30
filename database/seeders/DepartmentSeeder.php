<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name'=>'Khoa Nội','description'=>'Khoa Nội','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Khoa Sản','description'=>'Khoa Sản','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Khoa Nhi','description'=>'Khoa Nhi','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
