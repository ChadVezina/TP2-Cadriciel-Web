<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'language',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    /**
     * Get translation for specific locale
     */
    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get title in specific language
     */
    public function getTitleIn($locale)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->title : $this->title;
    }

    /**
     * Get content in specific language
     */
    public function getContentIn($locale)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->content : $this->content;
    }

    /**
     * Check if translation exists for locale
     */
    public function hasTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->exists();
    }

    /**
     * Check if article has complete translations (not marked as "[Translation needed]")
     */
    public function isFullyTranslated()
    {
        $frTranslation = $this->getTranslation('fr');
        $enTranslation = $this->getTranslation('en');

        if (!$frTranslation || !$enTranslation) {
            return false;
        }

        // Check if translations are not placeholders
        return !str_starts_with($frTranslation->title, '[Translation needed]') 
            && !str_starts_with($enTranslation->title, '[Translation needed]');
    }
}
