<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'is_student' => $this->isStudent(),
            'etudiant' => new EtudiantResource($this->whenLoaded('etudiant')),
            'articles_count' => $this->when($this->relationLoaded('articles'), fn() => $this->articles->count()),
            'documents_count' => $this->when($this->relationLoaded('documents'), fn() => $this->documents->count()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
