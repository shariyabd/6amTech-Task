<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            'name'                  => 'required|string|max:255',
            'role_id'               => 'nullable|integer|in:1,2',
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|string|min:6',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'  => false,
            'message' => 'Validation Error',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
