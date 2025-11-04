<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle ArticleTranslation
 * 
 * Représente une traduction d'un article dans une langue spécifique.
 * Permet de stocker le titre et le contenu d'un article dans différentes langues.
 * 
 * @property int $id Identifiant unique de la traduction
 * @property int $article_id Identifiant de l'article traduit
 * @property string $locale Code de la langue de la traduction (fr ou en)
 * @property string $title Titre traduit de l'article
 * @property string $content Contenu traduit de l'article
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read Article $article Article auquel appartient cette traduction
 */
class ArticleTranslation extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'article_id',
        'locale',
        'title',
        'content',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Récupère l'article auquel appartient cette traduction.
     * 
     * @return BelongsTo Relation BelongsTo vers le modèle Article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
