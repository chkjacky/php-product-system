<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            "name" => "F&N Soursop",
            "stock_quantity" => "100",
            "price" => "3.50",
            "status" => "available",
            "category_id" => "01a133e8-9a09-736d-a09f-3bd5b6db1536",
            "supplier_id" => "01a133c9-e250-71a4-98d1-7f827bf2a5c2",
        ]);
    }
}
