<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class EtudiantService
{
    /**
     * Get paginated list of students with optional filtering and sorting.
     */
    public function getPaginatedStudents(array $filters = []): LengthAwarePaginator
    {
        $perPage = $this->getValidatedPerPage($filters['per_page'] ?? 10);
        $search = $filters['search'] ?? null;
        $nameOrder = $filters['name_order'] ?? null;
        $cityOrder = $filters['city_order'] ?? null;

        $query = Etudiant::with('city')->select('etudiants.*');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (in_array($nameOrder, ['asc', 'desc'])) {
            $query->orderBy('name', $nameOrder);
        }

        if (in_array($cityOrder, ['asc', 'desc'])) {
            $query->leftJoin('villes', 'villes.id', '=', 'etudiants.city_id')
                  ->orderBy('villes.name', $cityOrder)
                  ->select('etudiants.*');
        }

        return $query->paginate($perPage)->appends($filters);
    }

    /**
     * Create a new student with associated user.
     */
    public function createStudent(array $data): Etudiant
    {
        $user = $this->findOrCreateUser($data['email'], $data['name']);
        $data['user_id'] = $user->id;

        return Etudiant::create($data);
    }

    /**
     * Update an existing student.
     */
    public function updateStudent(Etudiant $etudiant, array $data): Etudiant
    {
        $user = $this->findOrCreateUser($data['email'], $data['name']);
        $data['user_id'] = $user->id;

        $etudiant->update($data);
        return $etudiant->fresh();
    }

    /**
     * Delete a student.
     */
    public function deleteStudent(Etudiant $etudiant): bool
    {
        return $etudiant->delete();
    }

    /**
     * Find or create a user by email.
     */
    protected function findOrCreateUser(string $email, string $name): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Str::random(12),
            ]
        );
    }

    /**
     * Validate and bound pagination size.
     */
    protected function getValidatedPerPage(int $perPage): int
    {
        return max(10, min(100, $perPage));
    }
}
