<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'is_under_18' => $this->faker->boolean(), 
            'parent_email' => $this->faker->boolean() ? $this->faker->safeEmail() : null, 
        ];
    }
}
