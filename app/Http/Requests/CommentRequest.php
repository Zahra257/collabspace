<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'description' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:5000',
            ],
            'task_id' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'exists:tasks,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'description.max'  => 'Le commentaire ne doit pas dépasser 5000 caractères.',
            'task_id.exists'   => 'La tâche sélectionnée est introuvable.',
        ];
    }
}