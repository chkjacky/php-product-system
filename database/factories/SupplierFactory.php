<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->company(),
            "email" => fake()->unique()->companyEmail(),
            "is_active" => true,
        ];
    }
}
