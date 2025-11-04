<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get paginated list of users with optional filtering and sorting.
     */
    public function getPaginatedUsers(array $filters = []): LengthAwarePaginator
    {
        $perPage = $this->getValidatedPerPage($filters['per_page'] ?? 10);
        $search = $filters['search'] ?? null;
        $nameOrder = $filters['name_order'] ?? null;
        $cityOrder = $filters['city_order'] ?? null;

        $query = User::query()
            ->leftJoin('etudiants', 'users.id', '=', 'etudiants.user_id')
            ->leftJoin('villes', 'etudiants.city_id', '=', 'villes.id')
            ->select('users.*')
            ->with('etudiant.city');

        if (!empty($search)) {
            $query->where('users.name', 'like', "%{$search}%");
        }

        if (in_array($nameOrder, ['asc', 'desc'])) {
            $query->orderBy('users.name', $nameOrder);
        }

        if (in_array($cityOrder, ['asc', 'desc'])) {
            $query->orderBy('villes.name', $cityOrder);
        }

        return $query->paginate($perPage)->appends($filters);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user->fresh();
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Validate and bound pagination size.
     */
    protected function getValidatedPerPage(int $perPage): int
    {
        return max(10, min(100, $perPage));
    }
}
