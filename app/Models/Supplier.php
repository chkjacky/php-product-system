<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ["name", "email", "is_active", "password"];
    protected $hidden = ["password", "deleted_at"];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
