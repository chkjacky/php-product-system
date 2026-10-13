<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("products", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->string("stock_quantity");
            $table->decimal("price");
            $table
                ->enum("status", ["available", "unavailable", "out_of_stock"])
                ->default("available");
            $table->timestamps();
            $table->softDeletes();

            // belongsTo Category
            $table
                ->foreignUuid("category_id")
                ->constrained("categories")
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // belongsTo Supplier
            $table
                ->foreignUuid("supplier_id")
                ->constrained("suppliers")
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("products");
    }
};
