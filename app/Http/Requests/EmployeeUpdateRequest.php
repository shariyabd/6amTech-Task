<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeUpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($this->route('id'))
            ],
            'team_id'           => 'sometimes|required|exists:teams,id',
            'organization_id'   => 'sometimes|required|exists:organizations,id',
            'salary'            => 'sometimes|required|numeric|min:0',
            'start_date'        => 'sometimes|required|date',
            'position'          => 'sometimes|nullable|string',
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
