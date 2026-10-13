<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = ["name", "email", "is_active"];

    protected $attributes = [
        "is_active" => true,
    ];

    protected $hidden = ["deleted_at"];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where("is_active", true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $q) use ($term) {
            $q->where(function (Builder $q) use ($term) {
                $q->where("name", "like", "%{$term}%")->orWhere(
                    "email",
                    "like",
                    "%{$term}%",
                );
            });
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(get: fn(string $value) => ucwords($value));
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtolower(trim($value)),
        );
    }
}
