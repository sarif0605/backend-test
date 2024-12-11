<?php

namespace App\Http\Requests\ListItem;

use Illuminate\Foundation\Http\FormRequest;

class ListItemUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notes_id' => 'required|exists:notes,id',
            'parent_id' => 'nullable|exists:list_items,id',
            'description' => 'required|string',
            'sub_items' => 'nullable|array',
            'sub_items.*.id' => 'nullable|exists:list_items,id',  // Pastikan id ada untuk update
            'sub_items.*.description' => 'required|string',
            'sub_items.*.is_completed' => 'nullable|boolean',
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
