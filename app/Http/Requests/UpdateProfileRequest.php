<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'     => ['sometimes', 'string', 'min:2', 'max:100'],
            'email'    => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'bio'      => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
