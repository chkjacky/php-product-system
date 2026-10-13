<?php

namespace App\Http\Requests\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $categoryId = $this->route("category")?->id;

        return [
            "name" => ["sometimes", "required", "string", "max:255"],
            "stock_quantity" => [
                "sometimes",
                "required",
                "integer",
                "max:10000",
            ],
            "price" => [
                "sometimes",
                "required",
                "numeric",
                "decimal:2",
                "min:0",
            ],
            "status" => [
                "sometimes",
                "required",
                "in:available,unavailable,out_of_stock",
            ],
        ];
    }
}
