<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class sendOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'phone:AUTO',
            ],
        ];
    }

        public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required.',
            'phone.phone' => 'Please provide a valid phone number.',
        ];
    }

    // Return custiome response on failed
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $errors,
            ], 422)
        );
    }
}
