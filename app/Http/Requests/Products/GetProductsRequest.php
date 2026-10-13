<?php

namespace App\Http\Requests\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
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
            "per_page" => ["sometimes", "integer", "min:1", "max:100"],
            "cursor" => ["sometimes", "string"],
            "page" => ["sometimes", "integer", "min:1"],
            "search" => ["sometimes", "string", "max:255"],
            "sort" => ["sometimes", "string", "in:id,name,created_at"],
            "order" => ["sometimes", "string", "in:asc,desc"],
        ];
    }
}
