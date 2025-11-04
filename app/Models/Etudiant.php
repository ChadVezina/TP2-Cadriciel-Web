<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modèle Etudiant
 * 
 * Représente un étudiant dans le système avec ses informations personnelles.
 * Chaque étudiant est associé à une ville et à un compte utilisateur.
 * 
 * @property int $id Identifiant unique de l'étudiant
 * @property string $name Nom complet de l'étudiant
 * @property string $address Adresse de résidence
 * @property string $phone Numéro de téléphone
 * @property string $email Adresse courriel
 * @property \Illuminate\Support\Carbon|null $birthdate Date de naissance
 * @property int $city_id Identifiant de la ville
 * @property int|null $user_id Identifiant du compte utilisateur associé
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read Ville $city Ville de résidence de l'étudiant
 * @property-read User|null $user Compte utilisateur associé
 * @property-read string $full_name Nom complet de l'étudiant
 * @property-read int|null $age Âge de l'étudiant calculé à partir de la date de naissance
 */
class Etudiant extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'birthdate',
        'city_id',
        'user_id',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Récupère la ville de résidence de l'étudiant.
     * 
     * @return BelongsTo Relation BelongsTo vers le modèle Ville
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'city_id');
    }

    /**
     * Récupère le compte utilisateur associé à l'étudiant.
     * 
     * @return BelongsTo Relation BelongsTo vers le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Limite la requête aux étudiants d'une ville spécifique.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @param int $cityId Identifiant de la ville
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCity($query, int $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Recherche les étudiants par nom, courriel ou téléphone.
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
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Récupère le nom complet de l'étudiant.
     * 
     * @return string Nom complet de l'étudiant
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Calcule l'âge de l'étudiant à partir de sa date de naissance.
     * 
     * Utilise Carbon pour calculer de manière fiable l'âge, que la date de naissance
     * soit une chaîne ou une instance de Date.
     * 
     * @return int|null Âge de l'étudiant en années, ou null si pas de date de naissance
     */
    public function getAgeAttribute(): ?int
    {
        if (empty($this->birthdate)) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

}
