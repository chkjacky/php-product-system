<?php

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, "sanctum");
});

it("lists suppliers with pagination", function () {
    Supplier::factory()->count(20)->create();

    $response = $this->getJson("/api/suppliers");

    $response->assertOk()->assertJsonStructure([
        "data" => [
            "*" => ["id", "name", "email", "is_active"],
        ],
        "meta", // cursor pagination meta
    ]);
});

it("filters suppliers by search term", function () {
    Supplier::factory()->create(["name" => "Gardenia"]);
    Supplier::factory()->create(["name" => "Massimo"]);
    Supplier::factory()->create(["name" => "Silver Bird"]);

    $response = $this->getJson("/api/suppliers?search=Gardenia");

    $response
        ->assertOk()
        ->assertJsonCount(1, "data")
        ->assertJsonPath("data.0.name", "Gardenia");
});

it("filters suppliers by is_active flag", function () {
    Supplier::factory()->create(["is_active" => true]);
    Supplier::factory()->create(["is_active" => true]);
    Supplier::factory()->create(["is_active" => false]);

    $this->getJson("/api/suppliers?is_active=1")
        ->assertOk()
        ->assertJsonCount(2, "data");

    $this->getJson("/api/suppliers?is_active=0")
        ->assertOk()
        ->assertJsonCount(1, "data");
});

it("shows a single supplier", function () {
    $supplier = Supplier::factory()->create();

    $this->getJson("/api/suppliers/{$supplier->id}")
        ->assertOk()
        ->assertJsonPath("data.id", $supplier->id)
        ->assertJsonPath("data.email", $supplier->email);
});

it("returns 404 for a non-existent supplier", function () {
    $this->getJson(
        "/api/suppliers/01a134ee-0000-0000-0000-000000000000",
    )->assertNotFound();
});

it("creates a supplier with valid data", function () {
    $payload = [
        "name" => "Gardenia",
        "email" => "rotty@gardenia.com",
        "is_active" => true,
    ];

    $response = $this->postJson("/api/suppliers", $payload);

    $response
        ->assertCreated()
        ->assertJsonPath("data.name", "Gardenia")
        ->assertJsonPath("data.email", "rotty@gardenia.com");

    $this->assertDatabaseHas("suppliers", [
        "email" => "rotty@gardenia.com",
        "deleted_at" => null,
    ]);
});

it("rejects duplicate supplier email", function () {
    Supplier::factory()->create(["email" => "rotty@gardenia.com"]);

    $this->postJson("/api/suppliers", [
        "name" => "Another Gardenia",
        "email" => "rotty@gardenia.com",
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["email"]);
});

it("validates required fields when creating a supplier", function () {
    $this->postJson("/api/suppliers", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(["name", "email"]);
});

it("normalizes email to lowercase on create", function () {
    $this->postJson("/api/suppliers", [
        "name" => "Gardenia",
        "email" => "  Rotty@Gardenia.COM  ",
    ])->assertCreated();

    $this->assertDatabaseHas("suppliers", ["email" => "rotty@gardenia.com"]);
});

it("updates a supplier", function () {
    $supplier = Supplier::factory()->create(["name" => "Old Name"]);

    $this->putJson("/api/suppliers/{$supplier->id}", [
        "name" => "New Name",
        "email" => $supplier->email,
    ])
        ->assertOk()
        ->assertJsonPath("data.name", "New Name");

    $this->assertDatabaseHas("suppliers", [
        "id" => $supplier->id,
        "name" => "New Name",
    ]);
});

it("soft-deletes a supplier with no products", function () {
    $supplier = Supplier::factory()->create();

    $this->deleteJson("/api/suppliers/{$supplier->id}")->assertOk();

    $this->assertSoftDeleted("suppliers", ["id" => $supplier->id]);
});

it("refuses to delete a supplier that has products", function () {
    $supplier = Supplier::factory()->create();
    Product::factory()->create(["supplier_id" => $supplier->id]);

    $this->deleteJson("/api/suppliers/{$supplier->id}")
        ->assertStatus(422)
        ->assertJsonValidationErrors(["supplier"]);

    $this->assertDatabaseHas("suppliers", [
        "id" => $supplier->id,
        "deleted_at" => null,
    ]);
});

it(
    "restores a soft-deleted supplier when creating with same email",
    function () {
        $supplier = Supplier::factory()->create([
            "email" => "rotty@gardenia.com",
        ]);
        $supplier->delete();

        $this->assertSoftDeleted("suppliers", ["id" => $supplier->id]);

        $this->postJson("/api/suppliers", [
            "name" => "Gardenia Restored",
            "email" => "rotty@gardenia.com",
        ])
            ->assertCreated()
            ->assertJsonPath("data.id", $supplier->id);

        $this->assertDatabaseHas("suppliers", [
            "id" => $supplier->id,
            "deleted_at" => null,
        ]);
    },
);

it("requires authentication", function () {
    // forget the acting user
    $this->app["auth"]->forgetGuards();

    $this->getJson("/api/suppliers")->assertUnauthorized();
});
