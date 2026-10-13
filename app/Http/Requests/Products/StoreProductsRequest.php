<?php

namespace App\Http\Requests\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "max:255"],
            "stock_quantity" => ["required", "integer", "min:0", "max:10000"],
            "price" => ["required", "numeric", "decimal:2", "min:0"],
            "status" => ["required", "in:available,unavailable,out_of_stock"],
            "category_id" => [
                "required",
                "string",
                "uuid",
                "exists:categories,id",
            ],
            "supplier_id" => [
                "required",
                "string",
                "uuid",
                "exists:suppliers,id",
            ],
        ];
    }
}
