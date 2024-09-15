<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'sometimes|required|array',
            'items.*.id' => 'exists:purchase_order_items,id',
            'items.*.brand' => 'nullable|max:255',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.measurement_unit' => 'required|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'payment_details' => 'nullable|max:255',
            'delivery_date' => 'nullable|date',
            'status' => Rule::in(['pending', 'delivered', 'cancelled']),
        ];
    }

    public function messages()
    {
        return [
            'items.*.id' => [
                'exists' => 'Item not found.',
            ],
            'items.*.quantity' => [
                'required' => 'Quantity must not be empty.',
                'numeric' => 'Quantity must be numeric.',
                'min' => 'Quantity must not be below 0.',
            ],
            'items.*.measurement_unit' => [
                'required' => 'Measurement unit must not be empty.',
            ],
            'items.*.price' => [
                'required' => 'Price must not be empty.',
                'numeric' => 'Price must be numeric.',
                'min' => 'Price must not be below 0.',
            ],
        ];
    }
}
