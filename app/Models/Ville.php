<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Ville
 * 
 * Représente une ville dans le système.
 * Une ville peut être associée à plusieurs étudiants qui y résident.
 * 
 * @property int $id Identifiant unique de la ville
 * @property string $name Nom de la ville
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|Etudiant[] $students Étudiants résidant dans cette ville
 */
class Ville extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Récupère tous les étudiants qui résident dans cette ville.
     * 
     * @return HasMany Collection d'étudiants de la ville
     */
    public function students(): HasMany
    {
        return $this->hasMany(Etudiant::class, 'city_id');
    }
}
