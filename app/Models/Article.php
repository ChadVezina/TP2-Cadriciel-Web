<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'language',
        'user_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the translations for the article.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    /**
     * Scope a query to only include articles by a specific user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include articles in a specific language.
     */
    public function scopeInLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Scope a query to search articles by title or content.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Get translation for specific locale.
     */
    public function getTranslation(string $locale): ?ArticleTranslation
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get title in specific language.
     */
    public function getTitleIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->title ?? $this->title;
    }

    /**
     * Get content in specific language.
     */
    public function getContentIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->content ?? $this->content;
    }

    /**
     * Check if translation exists for locale.
     */
    public function hasTranslation(string $locale): bool
    {
        return $this->translations()->where('locale', $locale)->exists();
    }

    /**
     * Check if article has complete translations.
     */
    public function isFullyTranslated(): bool
    {
        $frTranslation = $this->getTranslation('fr');
        $enTranslation = $this->getTranslation('en');

        if (!$frTranslation || !$enTranslation) {
            return false;
        }

        return !str_starts_with($frTranslation->title, '[Translation needed]')
            && !str_starts_with($enTranslation->title, '[Translation needed]');
    }

    /**
     * Get the article's excerpt.
     */
    public function getExcerptAttribute(?int $length = 150): string
    {
        return str($this->content)->limit($length);
    }
}
