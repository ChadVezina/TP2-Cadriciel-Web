<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTitleIn($locale),
            'filename' => $this->filename,
            'original_filename' => $this->original_filename,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'file_icon' => $this->file_icon,
            'file_url' => $this->getFileUrl(),
            'download_url' => route('documents.show', $this->id),
            'translations' => DocumentTranslationResource::collection($this->whenLoaded('translations')),
            'owner' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
