<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_filename',
        'file_path',
        'file_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les traductions
     */
    public function translations()
    {
        return $this->hasMany(DocumentTranslation::class);
    }

    /**
     * Obtenir la traduction pour une langue spécifique
     */
    public function getTranslation($locale)
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Obtenir le titre dans une langue spécifique
     */
    public function getTitleIn($locale)
    {
        $translation = $this->getTranslation($locale);
        return $translation ? $translation->title : $this->original_filename;
    }

    /**
     * Vérifier si l'utilisateur est le propriétaire du document
     */
    public function isOwnedBy($user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Obtenir l'URL complète du fichier
     */
    public function getFileUrl()
    {
        // Build the public URL via the storage symlink (public/storage)
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    /**
     * Obtenir le chemin complet du fichier
     */
    public function getFilePath()
    {
        return Storage::disk('public')->path($this->file_path);
    }

    /**
     * Supprimer le fichier du stockage
     */
    public function deleteFile()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }
}
