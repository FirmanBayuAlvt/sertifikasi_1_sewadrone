<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DroneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drones')->insert([
            [
                'name' => 'Drone A',
                'code' => 'DRA001',
                'category_id' => 1,
                'description' => 'High-performance drone for aerial photography.',
                'status' => 'available',
            ],
            [
                'name' => 'Drone B',
                'code' => 'DRA002',
                'category_id' => 2,
                'description' => 'Lightweight drone suitable for racing.',
                'status' => 'available',
            ],
            [
                'name' => 'Drone C',
                'code' => 'DRA003',
                'category_id' => 1,
                'description' => 'Versatile drone for various applications.',
                'status' => 'available',
            ],
            [
                'name' => 'Drone D',
                'code' => 'DRA004',
                'category_id' => 3,
                'description' => 'Professional drone with advanced features.',
                'status' => 'available',
            ],
        ]);
    }
}