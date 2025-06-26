<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:100|min:2',
            'last_name' => 'nullable|string|max:100|min:2',
            'date_of_birth' => 'nullable|date|before:today|after:1900-01-01',
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /** 
     * Get custom validation messages. 
     */
    public function messages(): array
    {
        return [
            'first_name.min' => 'First name must be at least 2 characters long',
            'first_name.max' => 'First name must not exceed 100 characters',
            'last_name.min' => 'Last name must be at least 2 characters long',
            'last_name.max' => 'Last name must not exceed 100 characters',
            'date_of_birth.date' => 'Date of birth must be a valid date',
            'date_of_birth.before' => 'Date of birth must be before today',
            'date_of_birth.after' => 'Invalid date of birth',
            'gender.in' => 'Gender must be male, female, or other',
            'bio.max' => 'Profile must not exceed 1000 characters',
            'profile_image.image' => 'Profile must be Image',
            'profile_image.mimes' => 'The image must be of the following type: jpeg, png, jpg, gif',
            'profile_image.max' => 'The image size must not exceed 2 MB',
        ];
    }
}
