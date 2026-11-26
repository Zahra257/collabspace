<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'subject' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'start_time' => [
                $isUpdate ? 'sometimes' : 'required',
                'date',
            ],
            'duration_minutes' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'min:5',
                'max:1440',
            ],
            'note'       => ['nullable', 'string'],
            'project_id' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'exists:projects,id',
            ],
            'user_ids'   => ['sometimes', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'duration_minutes.min' => 'La durée minimale est de 5 minutes.',
            'duration_minutes.max' => 'La durée maximale est de 1440 minutes (24h).',
        ];
    }
}