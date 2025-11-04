<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Article
 * 
 * Représente un article de blog avec support multilingue.
 * Chaque article possède une langue principale et peut avoir des traductions
 * dans d'autres langues via la relation translations.
 * 
 * @property int $id Identifiant unique de l'article
 * @property string $title Titre de l'article dans sa langue principale
 * @property string $content Contenu de l'article dans sa langue principale
 * @property string $language Langue principale de l'article (fr ou en)
 * @property int $user_id Identifiant de l'utilisateur auteur
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read User $user Utilisateur auteur de l'article
 * @property-read \Illuminate\Database\Eloquent\Collection|ArticleTranslation[] $translations Traductions de l'article
 * @property-read string $excerpt Extrait du contenu de l'article
 */
class Article extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
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
     * Récupère les attributs qui doivent être castés.
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
     * Récupère l'utilisateur qui possède l'article.
     *
     * @return BelongsTo Relation BelongsTo vers le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère les traductions de l'article.
     *
     * @return HasMany Collection de traductions de l'article
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    /**
     * Limite la requête aux articles d'un utilisateur spécifique.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @param int $userId Identifiant de l'utilisateur
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Limite la requête aux articles dans une langue spécifique.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @param string $language Code de la langue (fr ou en)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLanguage($query, string $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Recherche les articles par titre ou contenu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @param string|null $search Terme de recherche
     * @return \Illuminate\Database\Eloquent\Builder
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
     * Récupère la traduction pour une locale spécifique.
     *
     * @param string $locale Code de la locale (fr ou en)
     * @return ArticleTranslation|null Traduction de l'article ou null si non trouvée
     */
    public function getTranslation(string $locale): ?ArticleTranslation
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Récupère le titre dans une langue spécifique.
     *
     * @param string $locale Code de la locale (fr ou en)
     * @return string Titre traduit ou titre original si pas de traduction
     */
    public function getTitleIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->title ?? $this->title;
    }

    /**
     * Récupère le contenu dans une langue spécifique.
     *
     * @param string $locale Code de la locale (fr ou en)
     * @return string Contenu traduit ou contenu original si pas de traduction
     */
    public function getContentIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->content ?? $this->content;
    }

    /**
     * Vérifie si une traduction existe pour une locale donnée.
     *
     * @param string $locale Code de la locale (fr ou en)
     * @return bool True si la traduction existe
     */
    public function hasTranslation(string $locale): bool
    {
        return $this->translations()->where('locale', $locale)->exists();
    }

    /**
     * Vérifie si l'article possède des traductions complètes.
     *
     * Un article est considéré comme complètement traduit s'il possède
     * des traductions en français et en anglais sans marqueurs de traduction manquante.
     *
     * @return bool True si l'article est complètement traduit
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
     * Récupère un extrait de l'article.
     * 
     * @param int|null $length Longueur maximale de l'extrait (150 caractères par défaut)
     * @return string Extrait du contenu de l'article
     */
    public function getExcerptAttribute(?int $length = 150): string
    {
        return str($this->content)->limit($length);
    }
}
