<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'title' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'description' => ['nullable', 'string'],
            'project_id'  => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'exists:projects,id',
            ],
            'team_id' => ['nullable', 'integer', 'exists:teams,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'status'  => [
                'sometimes',
                Rule::in(['pending', 'in_progress', 'completed', 'cancelled']),
            ],
            'priority' => [
                'sometimes',
                Rule::in(['low', 'medium', 'high', 'urgent']),
            ],
            'start_date' => ['nullable', 'date'],
            'due_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
            'guest_ids'   => ['sometimes', 'array'],
            'guest_ids.*' => ['integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.after_or_equal' => 'La date d\'échéance doit être après ou égale à la date de début.',
            'project_id.exists'       => 'Le projet sélectionné est introuvable.',
            'guest_ids.*.exists'      => 'Un des invités sélectionnés est introuvable.',
        ];
    }
}