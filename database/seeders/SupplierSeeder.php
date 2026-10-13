<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            "id" => "01a133c9-e250-71a4-98d1-7f827bf2a5c2",
            "name" => "F&N",
            "email" => "juice@fn.com",
            "is_active" => true,
        ]);
    }
}
