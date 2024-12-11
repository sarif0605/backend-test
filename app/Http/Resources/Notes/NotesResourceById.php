<?php

namespace App\Http\Resources\Notes;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotesResourceById extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message" => "Berhasil Menampilkan Data Genre Dengan ID $this->id",
            "data" => [
                'id' => $this->id,
                'title' => $this->title,
                'content' => $this->content,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'user' => new UserResource($this->whenLoaded('user')),
            ]
        ];
    }
}
