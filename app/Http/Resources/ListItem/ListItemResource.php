<?php

namespace App\Http\Resources\ListItem;

use App\Http\Resources\Notes\NotesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'notes_id' => $this->notes_id,
            'description' => $this->description,
            'is_completed' => $this->is_completed,
            'children' => $this->whenLoaded('children') ?? null,
            'parent_id' => $this->parent_id ?? null,
            'notes' => new NotesResource($this->whenLoaded('notes')) ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
