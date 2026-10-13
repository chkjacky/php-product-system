<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "stock_quantity",
        "price",
        "status",
        "category_id",
        "supplier_id",
    ];

    protected $hidden = ["deleted_at"];

    protected $casts = [
        "stock_quantity" => "integer",
        "price" => "decimal:2",
    ];

    protected $attributes = [
        "status" => "available",
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
