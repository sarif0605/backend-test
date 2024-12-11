<?php

namespace App\Http\Requests\ListItem;

use Illuminate\Foundation\Http\FormRequest;

class ListItemCreateRequest extends FormRequest
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
            'notes_id' => 'required|exists:notes,id',
            'parent_id' => 'nullable|exists:list_items,id',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'notes_id.required' => 'The notes_id field is required.',
            'notes_id.exists' => 'The notes_id does not exist.',
            'parent_id.exists' => 'The parent_id does not exist.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
        ];
    }
}
