<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HallSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('halls')->insert([
            ['name' => 'South Hall', 'code' => 'SOUTH', 'capacity' => 500],
            ['name' => 'North Hall', 'code' => 'NORTH', 'capacity' => 600],
        ]);
    }
}