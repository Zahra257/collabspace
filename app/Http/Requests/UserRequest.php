<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $userId   = $this->route('user')?->id;

        return [
            'name' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'email' => [
                $isUpdate ? 'sometimes' : 'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
            'role' => [
                'sometimes',
                Rule::in(['admin', 'manager', 'member', 'guest']),
            ],
            'job_title'           => ['nullable', 'string', 'max:255'],
            'phone'               => ['nullable', 'string', 'max:20'],
            'experience_years'    => ['nullable', 'integer', 'min:0', 'max:50'],
            'avatar'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'availability_status' => [
                'sometimes',
                Rule::in(['available', 'busy', 'away', 'offline']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'      => 'Cet email est déjà utilisé.',
            'password.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
            'avatar.image'      => 'L\'avatar doit être une image valide.',
        ];
    }
}