<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'file' => [
                $isUpdate ? 'nullable' : 'required',
                'file',
                'max:10240', // 10 MB
                'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,txt',
            ],
            'fileable_id' => [
                $isUpdate ? 'sometimes' : 'required',
                'integer',
            ],
            'fileable_type' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                Rule::in(['App\Models\Task', 'App\Models\Project']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max'         => 'Le fichier ne doit pas dépasser 10 MB.',
            'file.mimes'       => 'Type de fichier non autorisé.',
            'fileable_type.in' => 'Le fichier doit être lié à une tâche ou un projet.',
        ];
    }
}