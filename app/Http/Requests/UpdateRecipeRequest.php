<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'                => ['sometimes', 'string', 'min:3', 'max:200'],
            'description'          => ['sometimes', 'string', 'min:20'],
            'category'             => ['sometimes', 'string', Rule::in([
                                          'breakfast','pasta','soup','salad',
                                          'meat','dessert','vegetarian','other',
                                      ])],
            'difficulty'           => ['sometimes', Rule::in(['easy','medium','hard'])],
            'prep_time'            => ['sometimes', 'integer', 'min:0'],
            'cook_time'            => ['sometimes', 'integer', 'min:0'],
            'steps'                => ['sometimes', 'array', 'min:1'],
            'steps.*'              => ['required_with:steps', 'string', 'min:1'],
            'ingredients'          => ['sometimes', 'array', 'min:1'],
            'ingredients.*.name'   => ['required_with:ingredients', 'string', 'min:1'],
            'ingredients.*.amount' => ['required_with:ingredients', 'string', 'min:1'],
            'image'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }
}
