<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function rules()
    {
        return [
            'product_name' => 'required|max:255',
            'company_id' => [
                'required',
                Rule::exists('companies', 'id'),
            ],
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }
}
