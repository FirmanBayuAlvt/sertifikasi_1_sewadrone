<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drone;
use App\Models\DroneUnit;

class DroneUnitSeeder extends Seeder
{
    public function run()
    {
        $drone = Drone::first(); // atau cari berdasarkan model
        if ($drone) {
            DroneUnit::firstOrCreate(['code' => 'MINI3-001'], [
                'drone_id' => $drone->id,
                'name' => 'Unit 1',
                'status' => 'available'
            ]);
            DroneUnit::firstOrCreate(['code' => 'MINI3-002'], [
                'drone_id' => $drone->id,
                'name' => 'Unit 2',
                'status' => 'available'
            ]);
        }
    }
}
