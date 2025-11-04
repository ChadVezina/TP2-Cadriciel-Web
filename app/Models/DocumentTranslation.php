<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle DocumentTranslation
 * 
 * Représente une traduction du titre d'un document dans une langue spécifique.
 * Permet de fournir des titres descriptifs dans différentes langues pour les documents téléchargés.
 * 
 * @property int $id Identifiant unique de la traduction
 * @property int $document_id Identifiant du document traduit
 * @property string $locale Code de la langue de la traduction (fr ou en)
 * @property string $title Titre traduit du document
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read Document $document Document auquel appartient cette traduction
 */
class DocumentTranslation extends Model
{
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_id',
        'locale',
        'title',
    ];

    /**
     * Récupère le document auquel appartient cette traduction.
     * 
     * @return BelongsTo Relation BelongsTo vers le modèle Document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
