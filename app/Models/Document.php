<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Modèle Document
 * 
 * Représente un document téléchargé par un utilisateur avec support multilingue.
 * Gère le stockage des fichiers et leurs métadonnées, ainsi que les traductions
 * des titres dans différentes langues.
 * 
 * @property int $id Identifiant unique du document
 * @property int $user_id Identifiant de l'utilisateur propriétaire
 * @property string $filename Nom du fichier stocké sur le serveur
 * @property string $original_filename Nom original du fichier téléchargé
 * @property string $file_path Chemin relatif du fichier dans le stockage
 * @property string $file_type Extension/type du fichier (pdf, zip, doc, docx)
 * @property \Illuminate\Support\Carbon $created_at Date de création
 * @property \Illuminate\Support\Carbon $updated_at Date de dernière modification
 * 
 * @property-read User $user Utilisateur propriétaire du document
 * @property-read \Illuminate\Database\Eloquent\Collection|DocumentTranslation[] $translations Traductions du document
 * @property-read string $file_size Taille du fichier formatée
 * @property-read string $file_icon Icône représentant le type de fichier
 */
class Document extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
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
     * Récupère l'utilisateur qui possède le document.
     * 
     * @return BelongsTo Relation BelongsTo vers le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Récupère les traductions du document.
     * 
     * @return HasMany Collection de traductions du document
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DocumentTranslation::class);
    }

    /**
     * Limite la requête aux documents d'un utilisateur spécifique.
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
     * Limite la requête aux documents d'un type spécifique.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Constructeur de requête
     * @param string $type Type de fichier (pdf, zip, doc, docx)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    /**
     * Récupère la traduction pour une locale spécifique.
     * 
     * @param string $locale Code de la locale (fr ou en)
     * @return DocumentTranslation|null Traduction du document ou null si non trouvée
     */
    public function getTranslation(string $locale): ?DocumentTranslation
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Récupère le titre dans une langue spécifique.
     * 
     * @param string $locale Code de la locale (fr ou en)
     * @return string Titre traduit ou nom de fichier original si pas de traduction
     */
    public function getTitleIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->title ?? $this->original_filename;
    }

    /**
     * Vérifie si l'utilisateur possède le document.
     * 
     * @param User $user Utilisateur à vérifier
     * @return bool True si l'utilisateur est le propriétaire
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Récupère l'URL publique du fichier.
     * 
     * @return string URL du fichier
     */
    public function getFileUrl(): string
    {
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    /**
     * Récupère le chemin complet du fichier sur le serveur.
     * 
     * @return string Chemin absolu du fichier
     */
    public function getFilePath(): string
    {
        return Storage::disk('public')->path($this->file_path);
    }

    /**
     * Supprime le fichier physique du stockage.
     * 
     * @return void
     */
    public function deleteFile(): void
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    /**
     * Récupère la taille du fichier formatée en format lisible.
     * 
     * @return string Taille formatée (ex: "1.5 MB")
     */
    public function getFileSizeAttribute(): string
    {
        $bytes = Storage::disk('public')->size($this->file_path);
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(($bytes > 0 ? log($bytes) : 0) / log(1024));
        $power = min($power, count($units) - 1);
        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    /**
     * Récupère l'icône correspondant au type de fichier.
     * 
     * @return string Nom de l'icône à afficher
     */
    public function getFileIconAttribute(): string
    {
        return match($this->file_type) {
            'pdf' => 'file-pdf',
            'zip' => 'file-archive',
            'doc', 'docx' => 'file-word',
            default => 'file',
        };
    }
}
