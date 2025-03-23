<?php

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class TeamUpdateRequest extends FormRequest
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
        $team_id = $this->route('id');
        $teamExists = Team::where('id', $team_id)->exists();
        if (!$teamExists) {
            return [];
        }
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('teams', 'name')->ignore($team_id),
            ],
            'organization_id' => 'required',
            'department' => 'nullable',
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
