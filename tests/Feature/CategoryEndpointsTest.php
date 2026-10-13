<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(), "sanctum");
});

it("lists categories with pagination", function () {
    Category::factory()->count(20)->create();

    $this->getJson("/api/categories")
        ->assertOk()
        ->assertJsonStructure([
            "data" => [
                "*" => ["id", "name"],
            ],
            "meta",
        ]);
});

it("filters categories by search term", function () {
    Category::factory()->create(["name" => "Beverages"]);
    Category::factory()->create(["name" => "Snacks"]);
    Category::factory()->create(["name" => "Frozen"]);

    $this->getJson("/api/categories?search=Bever")
        ->assertOk()
        ->assertJsonCount(1, "data")
        ->assertJsonPath("data.0.name", "Beverages");
});

it("shows a single category", function () {
    $category = Category::factory()->create();

    $this->getJson("/api/categories/{$category->id}")
        ->assertOk()
        ->assertJsonPath("data.id", $category->id)
        ->assertJsonPath("data.name", $category->name);
});

it("returns 404 for a non-existent category", function () {
    $this->getJson(
        "/api/categories/01a134ee-0000-0000-0000-000000000000",
    )->assertNotFound();
});

it("creates a category with valid data", function () {
    $this->postJson("/api/categories", [
        "name" => "Beverages",
    ])
        ->assertCreated()
        ->assertJsonPath("data.name", "Beverages");

    $this->assertDatabaseHas("categories", ["name" => "Beverages"]);
});

it("rejects duplicate category name", function () {
    Category::factory()->create(["name" => "Beverages"]);

    $this->postJson("/api/categories", [
        "name" => "Beverages",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["name"]);
});

it("validates required fields when creating a category", function () {
    $this->postJson("/api/categories", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["name"]);
});

it("rejects a name longer than 255 characters", function () {
    $this->postJson("/api/categories", [
        "name" => str_repeat("a", 256),
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["name"]);
});

it("updates a category", function () {
    $category = Category::factory()->create(["name" => "Old Name"]);

    $this->patchJson("/api/categories/{$category->id}", [
        "name" => "New Name",
    ])
        ->assertOk()
        ->assertJsonPath("data.name", "New Name");

    $this->assertDatabaseHas("categories", [
        "id" => $category->id,
        "name" => "New Name",
    ]);
});

it("rejects updating a category to a duplicate name", function () {
    Category::factory()->create(["name" => "Beverages"]);
    $category = Category::factory()->create(["name" => "Snacks"]);

    $this->patchJson("/api/categories/{$category->id}", [
        "name" => "Beverages",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["name"]);
});

it("soft-deletes a category with no products", function () {
    $category = Category::factory()->create();

    $this->deleteJson("/api/categories/{$category->id}")->assertOk();

    $this->assertSoftDeleted("categories", ["id" => $category->id]);
});

it("refuses to delete a category that has products", function () {
    $category = Category::factory()->create();
    Product::factory()->create(["category_id" => $category->id]);

    $this->deleteJson("/api/categories/{$category->id}")
        ->assertStatus(422)
        ->assertJsonValidationErrors(["category"]);

    $this->assertDatabaseHas("categories", [
        "id" => $category->id,
        "deleted_at" => null,
    ]);
});

it(
    "restores a soft-deleted category when creating with same name",
    function () {
        $category = Category::factory()->create(["name" => "Beverages"]);
        $category->delete();

        $this->assertSoftDeleted("categories", ["id" => $category->id]);

        $this->postJson("/api/categories", [
            "name" => "Beverages",
        ])
            ->assertCreated()
            ->assertJsonPath("data.id", $category->id);

        $this->assertDatabaseHas("categories", [
            "id" => $category->id,
            "deleted_at" => null,
        ]);
    },
);

it("requires authentication", function () {
    $this->app["auth"]->forgetGuards();

    $this->getJson("/api/categories")->assertUnauthorized();
});
