<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'mohamed',
            'last_name' => 'saleh',
            'email' => 'mohamed@example.com',
            'password' => bcrypt('password'),
            'is_under_18' => true, 
            'parent_email' => 'saleh@example.com', 
        ]);

        User::factory()->count(10)->create();
    }
}
