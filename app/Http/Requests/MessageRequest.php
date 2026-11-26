<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'message' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:5000',
            ],
            'receiver_id' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'exists:users,id',
                'different:' . $this->user()?->id,
            ],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.max'         => 'Le message ne doit pas dépasser 5000 caractères.',
            'receiver_id.exists'  => 'Le destinataire est introuvable.',
            'receiver_id.different' => 'Vous ne pouvez pas vous envoyer un message à vous-même.',
        ];
    }
}