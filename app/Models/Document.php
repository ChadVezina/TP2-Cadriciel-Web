<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
     * Get the user that owns the document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the translations for the document.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(DocumentTranslation::class);
    }

    /**
     * Scope a query to only include documents by a specific user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include documents of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    /**
     * Get translation for a specific locale.
     */
    public function getTranslation(string $locale): ?DocumentTranslation
    {
        return $this->translations()->where('locale', $locale)->first();
    }

    /**
     * Get title in a specific language.
     */
    public function getTitleIn(string $locale): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->title ?? $this->original_filename;
    }

    /**
     * Check if the user owns the document.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get the file URL.
     */
    public function getFileUrl(): string
    {
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    /**
     * Get the full file path.
     */
    public function getFilePath(): string
    {
        return Storage::disk('public')->path($this->file_path);
    }

    /**
     * Delete the file from storage.
     */
    public function deleteFile(): void
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
    }

    /**
     * Get the human-readable file size.
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
     * Get the file icon based on type.
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
