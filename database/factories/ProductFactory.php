<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            "name" => fake()->words(2, true),
            "stock_quantity" => fake()->numberBetween(0, 100),
            "price" => fake()->randomFloat(2, 1, 100),
            "category_id" => Category::factory(),
            "supplier_id" => Supplier::factory(),
            "status" => "available",
        ];
    }
}
