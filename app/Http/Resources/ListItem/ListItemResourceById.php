<?php

namespace App\Http\Resources\ListItem;

use App\Http\Resources\Notes\NotesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListItemResourceById extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message" => "Berhasil Menampilkan Data Notes Dengan ID $this->id",
            "data" => [
                'id' => $this->id,
                'title' => $this->title,
                'content' => $this->content,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'notes' => new NotesResource($this->whenLoaded('notes')) ?? null,
                'children' => ListItemResource::collection($this->whenLoaded('children')) ?? null,
            ]
        ];
    }
}
