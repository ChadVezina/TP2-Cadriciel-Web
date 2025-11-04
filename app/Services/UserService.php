<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

/**
 * Service UserService
 * 
 * Gère la logique métier liée aux utilisateurs.
 * Fournit des méthodes pour créer, mettre à jour, supprimer et récupérer
 * des utilisateurs avec filtrage, recherche et tri.
 */
class UserService
{
    /**
     * Récupère une liste paginée d'utilisateurs avec filtrage et tri optionnels.
     * 
     * Supporte les filtres suivants:
     * - search: Recherche par nom
     * - name_order: Tri par nom (asc/desc)
     * - city_order: Tri par ville de l'étudiant associé (asc/desc)
     * - per_page: Nombre d'éléments par page
     * 
     * @param array $filters Filtres de recherche et tri
     * @return LengthAwarePaginator Liste paginée d'utilisateurs avec leur profil étudiant et ville
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
     * Crée un nouvel utilisateur.
     * 
     * Le mot de passe est automatiquement hashé pour la sécurité.
     * 
     * @param array $data Données de l'utilisateur (name, email, password)
     * @return User Utilisateur créé
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
     * Met à jour un utilisateur existant.
     * 
     * Le mot de passe n'est mis à jour que s'il est fourni dans les données.
     * Lorsqu'il est fourni, il est automatiquement hashé.
     * 
     * @param User $user Utilisateur à mettre à jour
     * @param array $data Nouvelles données de l'utilisateur
     * @return User Utilisateur mis à jour avec données rechargées
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
     * Supprime un utilisateur.
     * 
     * @param User $user Utilisateur à supprimer
     * @return bool True si la suppression a réussi
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Valide et limite la taille de pagination entre 10 et 100.
     * 
     * @param int $perPage Nombre d'éléments par page demandé
     * @return int Nombre d'éléments par page validé (entre 10 et 100)
     */
    protected function getValidatedPerPage(int $perPage): int
    {
        return max(10, min(100, $perPage));
    }
}
