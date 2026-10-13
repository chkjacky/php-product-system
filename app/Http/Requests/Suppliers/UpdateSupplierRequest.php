<?php

namespace App\Http\Requests\Suppliers;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            "website" => ["sometimes", "required", "string", "max:255"],
            "email" => ["sometimes", "required", "string", "max:255"],
            "is_active" => ["sometimes", "required", "boolean"],
        ];
    }
}
