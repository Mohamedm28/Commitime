<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScreenTime;
class ScreenTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScreenTime::create([
            'user_id' => 1, // Ensure the user exists
            'record_date' => now(),
            'total_screen_time_minutes' => 120,
            'app_usage' => json_encode([
                'Facebook' => 40,
                'Instagram' => 80,
            ]),
        ]);
    }
}
