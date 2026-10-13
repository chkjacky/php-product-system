<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(), "sanctum");
});

it("creates a product with valid data", function () {
    $category = Category::factory()->create();
    $supplier = Supplier::factory()->create();

    $payload = [
        "name" => "Chocolate Cake",
        "stock_quantity" => 10,
        "price" => 9.99,
        "category_id" => $category->id,
        "supplier_id" => $supplier->id,
        "status" => "available",
    ];

    $this->postJson("/api/products", $payload)
        ->assertCreated()
        ->assertJsonPath("data.name", "Chocolate Cake")
        ->assertJsonPath("data.stock_quantity", 10);

    $this->assertDatabaseHas("products", [
        "name" => "Chocolate Cake",
        "stock_quantity" => 10,
    ]);
});

it("rejects a product with an invalid category", function () {
    $supplier = Supplier::factory()->create();

    $this->postJson("/api/products", [
        "name" => "Cake",
        "stock_quantity" => 5,
        "price" => 10.01,
        "category_id" => "01a134ee-0000-0000-0000-000000000000",
        "supplier_id" => $supplier->id,
        "status" => "available",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["category_id"]);
});

it("rejects negative stock", function () {
    $category = Category::factory()->create();
    $supplier = Supplier::factory()->create();

    $this->postJson("/api/products", [
        "name" => "Cake",
        "stock_quantity" => -1,
        "price" => 10.01,
        "category_id" => $category->id,
        "supplier_id" => $supplier->id,
        "status" => "available",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["stock_quantity"]);
});

it("rejects an invalid status value", function () {
    $category = Category::factory()->create();
    $supplier = Supplier::factory()->create();

    $this->postJson("/api/products", [
        "name" => "Cake",
        "stock_quantity" => 5,
        "price" => 10.01,
        "category_id" => $category->id,
        "supplier_id" => $supplier->id,
        "status" => "banana",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["status"]);
});
