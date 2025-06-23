<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // سيتم التحكم في الصلاحيات من خلال middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'production_date'   => 'required|date',
            'expiry_date'       => 'required|date|after:production_date',
            'brand_id'          => 'required|exists:brands,id',
            'category_id'       => 'required|exists:categories,id',
            'unit_id'           => 'required|exists:units,id',
            'is_active'         => 'sometimes|boolean',
        ];

        // إضافة قواعد خاصة بالصورة إذا كانت موجودة
        if ($this->isMethod('post') || $this->hasFile('image')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'; // 5MB كحد أقصى
        }

        return $rules;
    }
}
