<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ingredient_id' => 'required|exists:ingredients,id',
            'type' => 'required|in:IN,OUT,ADJUST',
            'qty' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ];
    }
}
