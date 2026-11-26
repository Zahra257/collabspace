<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'user_id' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
                'exists:users,id',
            ],
            'title' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'message' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:1000',
            ],
            'type' => [
                'sometimes',
                Rule::in(['info', 'success', 'warning', 'error', 'task', 'project', 'meeting']),
            ],
            'is_read' => ['sometimes', 'boolean'],
        ];
    }
}