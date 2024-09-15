<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewQuotationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'contact_number' => 'required|max:255',
            'viber_id' => 'sometimes|nullable|numeric',
            'items' => 'required|array',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.brand' => 'nullable|max:255',
            'items.*.id' => 'exists:products',
        ];
    }

    public function messages()
    {
        return [
            'items.*.quantity' => [
                'required' => 'Quantity must not be empty.',
                'numeric' => 'Quantity must be numeric.',
                'min' => 'Quantity must not be below 1.',
            ],
            'items.*.id' => [
                'exists' => 'Product not found.',
            ],
        ];
    }
}
