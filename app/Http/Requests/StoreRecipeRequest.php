<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'                    => ['required', 'string', 'min:3', 'max:200'],
            'description'              => ['required', 'string', 'min:20'],
            'category'                 => ['required', 'string', Rule::in([
                                              'breakfast','pasta','soup','salad',
                                              'meat','dessert','vegetarian','other',
                                          ])],
            'difficulty'               => ['required', Rule::in(['easy','medium','hard'])],
            'prep_time'                => ['required', 'integer', 'min:0'],
            'cook_time'                => ['required', 'integer', 'min:0'],

            // Steps: at least 1 non-empty string
            'steps'                    => ['required', 'array', 'min:1'],
            'steps.*'                  => ['required', 'string', 'min:1'],

            'servings'                 => ['nullable', 'integer', 'min:1', 'max:100'],
            'ingredients'              => ['required', 'array', 'min:1'],
            'ingredients.*.name'       => ['required', 'string', 'min:1', 'max:255'],
            'ingredients.*.quantity'   => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'ingredients.*.unit'       => ['nullable', 'string', 'max:50'],

            // Optional image upload
            'image'                    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'status'                   => 'sometimes|in:published,draft,private',
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
}
