<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            "id" => "01a133e8-9a09-736d-a09f-3bd5b6db1536",
            "name" => "beverages",
        ]);
    }
}
