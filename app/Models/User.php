<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modèle User
 * 
 * Représente un utilisateur du système avec authentification.
 * Un utilisateur peut avoir un profil étudiant et peut créer des articles et des documents.
 * 
 * @property int $id Identifiant unique de l'utilisateur
 * @property string $name Nom complet de l'utilisateur
 * @property string $email Adresse courriel unique
 * @property \Illuminate\Support\Carbon|null $email_verified_at Date de vérification du courriel
 * @property string $password Mot de passe hashé
 * @property bool $is_admin Indicateur de statut administrateur
 * @property string|null $remember_token Token pour la fonctionnalité "Se souvenir de moi"
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read Etudiant|null $etudiant Profil étudiant associé
 * @property-read \Illuminate\Database\Eloquent\Collection|Article[] $articles Articles créés par l'utilisateur
 * @property-read \Illuminate\Database\Eloquent\Collection|Document[] $documents Documents téléchargés par l'utilisateur
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * Les attributs qui doivent être masqués pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Récupère les attributs qui doivent être castés.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Récupère le profil étudiant associé à l'utilisateur (si existant).
     * 
     * @return HasOne Relation HasOne vers le modèle Etudiant
     */
    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    /**
     * Récupère tous les articles écrits par l'utilisateur.
     * 
     * @return HasMany Collection d'articles de l'utilisateur
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Récupère tous les documents téléchargés par l'utilisateur.
     * 
     * @return HasMany Collection de documents de l'utilisateur
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Vérifie si l'utilisateur est un étudiant.
     * 
     * @return bool True si l'utilisateur possède un profil étudiant
     */
    public function isStudent(): bool
    {
        return $this->etudiant()->exists();
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     * 
     * @return bool True si l'utilisateur a le statut administrateur
     */
    public function isAdmin(): bool
    {
        return $this->is_admin ?? false;
    }

    /**
     * Limite la requête aux utilisateurs administrateurs.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Limite la requête aux utilisateurs ayant un profil étudiant.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudents($query)
    {
        return $query->whereHas('etudiant');
    }

    /**
     * Recherche les utilisateurs par nom ou courriel.
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
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
