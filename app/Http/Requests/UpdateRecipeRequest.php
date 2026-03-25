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
            'title'                     => ['sometimes', 'string', 'min:3', 'max:200'],
            'description'               => ['sometimes', 'string', 'min:20'],
            'category'                  => ['sometimes', 'string', Rule::in([
                                          'breakfast','pasta','soup','salad',
                                          'meat','dessert','vegetarian','other',
                                        ])],

            'difficulty'                => ['sometimes', Rule::in(['easy','medium','hard'])],
            'prep_time'                 => ['sometimes', 'integer', 'min:0'],
            'cook_time'                 => ['sometimes', 'integer', 'min:0'],
            'steps'                     => ['sometimes', 'array', 'min:1'],
            'steps.*'                   => ['required_with:steps', 'string', 'min:1'],

            'servings'                  => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
            'ingredients'               => ['sometimes', 'array', 'min:1'],
            'ingredients.*.name'        => ['required_with:ingredients', 'string', 'min:1', 'max:255'],
            'ingredients.*.quantity'    => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'ingredients.*.unit'        => ['nullable', 'string', 'max:50'],

            'image'                     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status'                    => 'sometimes|in:published,draft,private',

            'nutritional_info'          => ['nullable', 'array'],
            'nutritional_info.calories' => ['nullable', 'integer', 'min:0'],
            'nutritional_info.protein'  => ['nullable', 'numeric', 'min:0'],
            'nutritional_info.carbs'    => ['nullable', 'numeric', 'min:0'],
            'nutritional_info.fat'      => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (($this->input('status') ?? 'published') === 'private') {
                    if (!$this->user()->isPremium()) {
                        $validator->errors()->add('status', 'Private recipes are a Premium feature.');
                    }
                }
            }
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->has('nutritional_info') && is_string($this->nutritional_info)) {
            $this->merge([
                'nutritional_info' => json_decode($this->nutritional_info, true),
            ]);
        }
    }
}
