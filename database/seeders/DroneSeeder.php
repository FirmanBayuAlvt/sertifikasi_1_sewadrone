<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drone;
use Illuminate\Support\Facades\DB;

class DroneSeeder extends Seeder
{
    public function run(): void
    {
        // optional: kosongkan table sebelum seed (hati-hati di production)
        DB::table('drones')->truncate();

        $now = now();

        $drones = [
            [
                'model' => 'DJI Mini 3',
                'serial_no' => 'MINI3-001',
                'specs' => json_encode([
                    'camera' => '12MP',
                    'flight_time' => '38min',
                    'weight' => '249g'
                ], JSON_UNESCAPED_UNICODE),
                'description' => 'Drone ringan, cocok untuk pemotretan prewedding dan konten sosial media.',
                'hourly_rate' => 150000,
                'daily_rate' => 1000000,
                'deposit' => 200000,
                'status' => 'available',
                'image' => 'https://example.com/images/dji-mini-3.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'model' => 'DJI Air 2S',
                'serial_no' => 'AIR2S-001',
                'specs' => json_encode([
                    'camera' => '20MP',
                    'flight_time' => '31min',
                    'weight' => '595g'
                ], JSON_UNESCAPED_UNICODE),
                'description' => 'Drone profesional menengah dengan sensor 1-inch, cocok untuk video sinematik.',
                'hourly_rate' => 300000,
                'daily_rate' => 2000000,
                'deposit' => 400000,
                'status' => 'available',
                'image' => 'https://example.com/images/dji-air-2s.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'model' => 'DJI Mavic 3 Classic',
                'serial_no' => 'MAVIC3-001',
                'specs' => json_encode([
                    'camera' => '4/3 CMOS 20MP Hasselblad',
                    'flight_time' => '46min',
                    'weight' => '895g'
                ], JSON_UNESCAPED_UNICODE),
                'description' => 'Drone premium dengan kualitas kamera tinggi dan stabilisasi terbaik.',
                'hourly_rate' => 500000,
                'daily_rate' => 3500000,
                'deposit' => 700000,
                'status' => 'available',
                'image' => 'https://example.com/images/dji-mavic-3.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'model' => 'Autel Evo Lite+',
                'serial_no' => 'EVO-LITE-001',
                'specs' => json_encode([
                    'camera' => '50MP RYYB sensor',
                    'flight_time' => '40min',
                    'weight' => '835g'
                ], JSON_UNESCAPED_UNICODE),
                'description' => 'Alternatif DJI dengan performa tinggi dan warna kamera tajam.',
                'hourly_rate' => 400000,
                'daily_rate' => 2800000,
                'deposit' => 600000,
                'status' => 'available',
                'image' => 'https://example.com/images/autel-evo-lite.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('drones')->insert($drones);
    }
}
