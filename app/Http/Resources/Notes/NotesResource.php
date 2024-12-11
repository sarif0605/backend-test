<?php

namespace App\Http\Resources\Notes;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotesResource extends JsonResource
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
        'title' => $this->title,
        'content' => $this->content,
        'user' => new UserResource($this->whenLoaded('user')),
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
    }
}
