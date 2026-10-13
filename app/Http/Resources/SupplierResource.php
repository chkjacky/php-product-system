<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "website" => $this->website,
            "email" => $this->email,
            "is_active" => $this->is_active,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at,
        ];
    }
}
