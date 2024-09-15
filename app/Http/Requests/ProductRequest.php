<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                ...$this->isPrecognitive() ? [] : ['nullable'],
                'file',
                'image',
            ],
            'name' => [
                'required',
                Rule::unique('products')->ignore($this->route('product')?->id),
            ],
            'description' => 'nullable|max:255',
            'price' => 'required|numeric|min:0',
            'measurement_unit' => 'required|max:255',
            'main_category' => 'required|max:255',
            'sub_category' => 'nullable|max:255',
            'status' => [
                'required',
                Rule::in(['available', 'unavailable', 'archived']),
            ],
        ];
    }
}
