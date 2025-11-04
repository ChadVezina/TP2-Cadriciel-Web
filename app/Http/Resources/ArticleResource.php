<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = $request->session()->get('article_view_locale', app()->getLocale());

        return [
            'id' => $this->id,
            'title' => $this->getTitleIn($locale),
            'content' => $this->getContentIn($locale),
            'excerpt' => $this->excerpt,
            'language' => $this->language,
            'is_fully_translated' => $this->isFullyTranslated(),
            'translations' => ArticleTranslationResource::collection($this->whenLoaded('translations')),
            'author' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
