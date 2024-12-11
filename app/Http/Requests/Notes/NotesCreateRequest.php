<?php

namespace App\Http\Requests\Notes;

use Illuminate\Foundation\Http\FormRequest;

class NotesCreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'notes_id' => 'required|exists:notes,id',
            'parent_id' => 'nullable|exists:list_items,id',
            'description' => 'required|string',
             'sub_items' => 'nullable|array', // Menambahkan validasi untuk sub_items
             'sub_items.*.description' => 'required|string', // Validasi untuk setiap sub-item
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
            'sub_items.*.description.required' => 'The sub_item description field is required.',
            'sub_items.*.description.string' => 'The sub_item description must be a string.',
            'sub_items.*.is_completed.boolean' => 'The sub_item is_completed must be a boolean.',
        ];
    }
}
