<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $basePath = 'v1/product';

        if ($this->is("{$basePath}") && $this->isMethod('post')) {
            // Validation rules for storing a new product
            $rules = [
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                // add more rules as needed
            ];
        }

    }
}
