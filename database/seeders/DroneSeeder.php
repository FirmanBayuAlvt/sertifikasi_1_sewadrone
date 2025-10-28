<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drone;

class DroneSeeder extends Seeder
{
    public function run(): void
    {
        Drone::create([
            'model' => 'DJI Mini 3',
            'serial_no' => 'MINI3-001',
            'specs' => ['camera' => '12MP', 'flight_time' => '38min', 'weight' => '249g'],
            'description' => 'Drone ringan, cocok untuk pemotretan prewedding dan konten sosial media.',
            'hourly_rate' => 150000,
            'daily_rate' => 1000000,
            'deposit' => 200000,
            'status' => 'available'
        ]);

        Drone::create([
            'model' => 'DJI Air 2S',
            'serial_no' => 'AIR2S-001',
            'specs' => ['camera' => '20MP', 'flight_time' => '31min', 'weight' => '595g'],
            'description' => 'Drone profesional menengah dengan sensor 1-inch.',
            'hourly_rate' => 300000,
            'daily_rate' => 2000000,
            'deposit' => 400000,
            'status' => 'available'
        ]);
    }
}
