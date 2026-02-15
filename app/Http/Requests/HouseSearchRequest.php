<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'bedrooms' => ['sometimes', 'integer', 'min:1'],
            'bathrooms' => ['sometimes', 'integer', 'min:1'],
            'storeys' => ['sometimes', 'integer', 'min:1'],
            'garages' => ['sometimes', 'integer', 'min:0'],
            'price_min' => ['sometimes', 'numeric', 'min:0'],
            'price_max' => ['sometimes', 'numeric', 'min:0', 'gte:price_min'],
        ];
    }
}
