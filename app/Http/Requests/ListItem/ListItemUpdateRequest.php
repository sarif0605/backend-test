<?php

namespace App\Http\Requests\ListItem;

use Illuminate\Foundation\Http\FormRequest;

class ListItemUpdateRequest extends FormRequest
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
            'description' => 'sometimes|required|string',
            'is_completed' => 'sometimes|required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'is_completed.required' => 'The is_completed field is required.',
            'is_completed.boolean' => 'The is_completed must be a boolean.',
        ];
    }
}
