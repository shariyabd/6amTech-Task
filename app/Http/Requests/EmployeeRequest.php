<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employeeId = $this->route('id');
        $emailRule = 'required|email|max:255|unique:employees,email';

        // If it's an update, exclude the current employee from unique validation
        if ($employeeId) {
            $emailRule .= ',' . $employeeId;
        }

        return [
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'team_id' => 'required|exists:teams,id',
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'position' => 'nullable|string|max:255',
        ];
    }
}
