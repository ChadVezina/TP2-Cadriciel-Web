<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Etudiant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the city that owns the student.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(Ville::class, 'city_id');
    }

    /**
     * Get the user that owns the student profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope a query to only include students from a specific city.
     */
    public function scopeFromCity($query, int $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope a query to search students by name, email, or phone.
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
     * Get the student's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the student's age based on birthdate.
     * Get the student's age based on birthdate.
     */
    public function getAgeAttribute(): ?int
    {
        if (empty($this->birthdate)) {
            return null;
        }

        // Use Carbon to reliably compute the age whether birthdate is a string or a Date instance
        return Carbon::parse($this->birthdate)->age;
    }

}
