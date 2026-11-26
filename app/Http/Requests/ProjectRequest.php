<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string'],
            'start_date'  => ['nullable', 'date'],
            'deadline'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'      => [
                'sometimes',
                Rule::in(['planning', 'in_progress', 'on_hold', 'completed', 'cancelled']),
            ],
            'priority' => [
                'sometimes',
                Rule::in(['low', 'medium', 'high', 'urgent']),
            ],
            'team_ids'   => ['sometimes', 'array'],
            'team_ids.*' => ['integer', 'exists:teams,id'],
            'user_ids'   => ['sometimes', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'deadline.after_or_equal' => 'La date limite doit être après ou égale à la date de début.',
            'team_ids.*.exists'       => 'Une des équipes sélectionnées est introuvable.',
            'user_ids.*.exists'       => 'Un des utilisateurs sélectionnés est introuvable.',
        ];
    }
}