<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'Computer Science & Engineering', 'code' => 'CSE', 'has_lab' => true],
            ['name' => 'Electrical & Electronic Engineering', 'code' => 'EEE', 'has_lab' => true],
            ['name' => 'Civil Engineering', 'code' => 'Civil', 'has_lab' => true],
        ]);
    }
}