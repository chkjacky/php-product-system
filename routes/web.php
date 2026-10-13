<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::inertia("/", "welcome")->name("home");

Route::get("/categories", [CategoryController::class, "index"]);
Route::post("/categories", [CategoryController::class, "store"]);
Route::get("/categories/{id}", [CategoryController::class, "show"]);

Route::get("/products", [ProductController::class, "index"]);
Route::post("/products", [ProductController::class, "store"]);
Route::get("/products/{id}", [ProductController::class, "show"]);

Route::get("/suppliers", [SupplierController::class, "index"]);
Route::post("/suppliers", [SupplierController::class, "store"]);
Route::get("/suppliers/{id}", [SupplierController::class, "show"]);

// Grouped with prefix
Route::prefix("api/v1")->group(function () {
    Route::apiResource("categories", CategoryController::class);
    Route::apiResource("products", ProductController::class);
    Route::apiResource("suppliers", SupplierController::class);
});
